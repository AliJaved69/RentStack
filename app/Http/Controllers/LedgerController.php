<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $entries = Ledger::latest()->paginate(10);
        return view('ledger.index', compact('entries'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:borrowed,returned',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
        ]);

        Ledger::create($validated);

        return redirect()->route('ledger.index')->with('success', 'Ledger entry recorded.');
    }
}
