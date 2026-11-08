-- ====================================================================
-- RentStack: Fresh Clean & Setup Script for Supabase PostgreSQL
-- Copy and paste this ENTIRE script into Supabase SQL Editor and click RUN
-- ====================================================================

-- STEP 1: Safely drop all existing tables (will NOT error if any table doesn't exist)
DROP TABLE IF EXISTS public.payments CASCADE;
DROP TABLE IF EXISTS public.invoices CASCADE;
DROP TABLE IF EXISTS public.leases CASCADE;
DROP TABLE IF EXISTS public.property_units CASCADE;
DROP TABLE IF EXISTS public.tenants CASCADE;
DROP TABLE IF EXISTS public.properties CASCADE;
DROP TABLE IF EXISTS public.users CASCADE;
DROP TABLE IF EXISTS public.owners CASCADE;
DROP TABLE IF EXISTS public.ledgers CASCADE;

-- STEP 2: Create fresh, clean tables matching the app exactly

-- 1. Owners
CREATE TABLE public.owners (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_info TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- 2. Users
CREATE TABLE public.users (
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

-- 3. Properties (Buildings & Plazas)
CREATE TABLE public.properties (
    id BIGSERIAL PRIMARY KEY,
    owner_id BIGINT NOT NULL REFERENCES public.owners(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    image_path VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'occupied',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- 4. Property Units (Portions, Shops, Floors, Flats)
CREATE TABLE public.property_units (
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

-- 5. Tenants
CREATE TABLE public.tenants (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    cnic_or_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- 6. Leases
CREATE TABLE public.leases (
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

-- 7. Invoices (Monthly Rent Bills with Arrears & Utilities)
CREATE TABLE public.invoices (
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

-- 8. Payments (Split Rent & Security Collections)
CREATE TABLE public.payments (
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

-- 9. Ledgers
CREATE TABLE public.ledgers (
    id BIGSERIAL PRIMARY KEY,
    type VARCHAR(50) NOT NULL CHECK (type IN ('borrowed', 'returned')),
    amount NUMERIC(15, 2) NOT NULL,
    description TEXT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now()),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT timezone('utc'::text, now())
);

-- STEP 3: Grant full permissions to Supabase roles
GRANT ALL ON ALL TABLES IN SCHEMA public TO postgres, service_role, anon, authenticated;
GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO postgres, service_role, anon, authenticated;
