-- ====================================================================
-- RentStack Exact Schema for Supabase PostgreSQL
-- Run this in your Supabase Dashboard: SQL Editor -> New Query -> Run
-- ====================================================================

-- 1. Owners Table
CREATE TABLE IF NOT EXISTS public.owners (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_info TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- 2. Users Table
CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP WITH TIME ZONE NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    owner_id BIGINT NULL REFERENCES public.owners(id) ON DELETE CASCADE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- 3. Properties Table
CREATE TABLE IF NOT EXISTS public.properties (
    id BIGSERIAL PRIMARY KEY,
    owner_id BIGINT NOT NULL REFERENCES public.owners(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    image_path VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'occupied',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- Ensure image_path exists if table was already created
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='properties' AND column_name='image_path') THEN
        ALTER TABLE public.properties ADD COLUMN image_path VARCHAR(255) NULL;
    END IF;
END $$;

-- 4. Property Units (Portions, Shops, Floors, Flats)
CREATE TABLE IF NOT EXISTS public.property_units (
    id BIGSERIAL PRIMARY KEY,
    property_id BIGINT NOT NULL REFERENCES public.properties(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) DEFAULT 'portion',
    default_rent NUMERIC(15, 2) DEFAULT 0.00,
    status VARCHAR(50) DEFAULT 'vacant',
    notes TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- 5. Tenants Table
CREATE TABLE IF NOT EXISTS public.tenants (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    cnic_or_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- Ensure cnic_or_id exists if tenants table was created earlier with national_id
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='tenants' AND column_name='national_id') THEN
        ALTER TABLE public.tenants RENAME COLUMN national_id TO cnic_or_id;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='tenants' AND column_name='cnic_or_id') THEN
        ALTER TABLE public.tenants ADD COLUMN cnic_or_id VARCHAR(50);
    END IF;
END $$;

-- 6. Leases Table
CREATE TABLE IF NOT EXISTS public.leases (
    id BIGSERIAL PRIMARY KEY,
    property_id BIGINT NOT NULL REFERENCES public.properties(id) ON DELETE CASCADE,
    unit_id BIGINT NULL REFERENCES public.property_units(id) ON DELETE SET NULL,
    tenant_id BIGINT NOT NULL REFERENCES public.tenants(id) ON DELETE CASCADE,
    start_date DATE NOT NULL,
    base_rent NUMERIC(15, 2) NOT NULL,
    security_deposit_expected NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    security_deposit_paid NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    e_stamp_file_path VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='leases' AND column_name='unit_id') THEN
        ALTER TABLE public.leases ADD COLUMN unit_id BIGINT NULL REFERENCES public.property_units(id) ON DELETE SET NULL;
    END IF;
    IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='leases' AND column_name='rent_amount') THEN
        ALTER TABLE public.leases RENAME COLUMN rent_amount TO base_rent;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='leases' AND column_name='security_deposit_expected') THEN
        ALTER TABLE public.leases ADD COLUMN security_deposit_expected NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='leases' AND column_name='security_deposit_paid') THEN
        ALTER TABLE public.leases ADD COLUMN security_deposit_paid NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
END $$;

-- 7. Invoices Table
CREATE TABLE IF NOT EXISTS public.invoices (
    id BIGSERIAL PRIMARY KEY,
    lease_id BIGINT NOT NULL REFERENCES public.leases(id) ON DELETE CASCADE,
    billing_month DATE NOT NULL,
    base_rent NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    arrears NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    utility_charges NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    utility_notes VARCHAR(255) NULL,
    security_due NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    security_paid NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    amount_due NUMERIC(15, 2) NOT NULL,
    amount_paid NUMERIC(15, 2) DEFAULT 0.00,
    status VARCHAR(50) DEFAULT 'unpaid',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='invoices' AND column_name='base_rent') THEN
        ALTER TABLE public.invoices ADD COLUMN base_rent NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='invoices' AND column_name='arrears') THEN
        ALTER TABLE public.invoices ADD COLUMN arrears NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='invoices' AND column_name='utility_charges') THEN
        ALTER TABLE public.invoices ADD COLUMN utility_charges NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='invoices' AND column_name='utility_notes') THEN
        ALTER TABLE public.invoices ADD COLUMN utility_notes VARCHAR(255) NULL;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='invoices' AND column_name='security_due') THEN
        ALTER TABLE public.invoices ADD COLUMN security_due NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='invoices' AND column_name='security_paid') THEN
        ALTER TABLE public.invoices ADD COLUMN security_paid NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
END $$;

-- 8. Payments Table
CREATE TABLE IF NOT EXISTS public.payments (
    id BIGSERIAL PRIMARY KEY,
    invoice_id BIGINT NOT NULL REFERENCES public.invoices(id) ON DELETE CASCADE,
    rent_amount NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    security_amount NUMERIC(15, 2) NOT NULL DEFAULT 0.00,
    amount NUMERIC(15, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_date DATE NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='payments' AND column_name='rent_amount') THEN
        ALTER TABLE public.payments ADD COLUMN rent_amount NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='payments' AND column_name='security_amount') THEN
        ALTER TABLE public.payments ADD COLUMN security_amount NUMERIC(15, 2) DEFAULT 0.00;
    END IF;
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name='payments' AND column_name='notes') THEN
        ALTER TABLE public.payments ADD COLUMN notes TEXT NULL;
    END IF;
END $$;

-- 9. Ledgers Table
CREATE TABLE IF NOT EXISTS public.ledgers (
    id BIGSERIAL PRIMARY KEY,
    type VARCHAR(50) NOT NULL CHECK (type IN ('borrowed', 'returned')),
    amount NUMERIC(15, 2) NOT NULL,
    description TEXT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- Grant full API access to authenticated & service_role
GRANT ALL ON ALL TABLES IN SCHEMA public TO postgres, service_role, anon;
GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO postgres, service_role, anon;
