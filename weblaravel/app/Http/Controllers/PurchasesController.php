<?php

namespace App\Http\Controllers;

use App\Models\Purchases;
use App\Models\Customers;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function index()
    {
        $purchases = Purchases::with('customers')->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $customers = Customers::all();
        return view('purchases.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cust_id' => 'required|exists:customers,cust_id',
            'date'    => 'required|date',
        ]);

        // total will be set later by the details seeder or logic
        $purchase = Purchases::create(array_merge($validated, ['total' => 0]));

        return redirect()->route('purchases.show', $purchase)
                         ->with('success', 'Purchase created.');
    }

    public function show(Purchases $purchase)
    {
        $purchase->load(['customers','purchase_details']);
        return view('purchases.show', compact('purchases'));
    }

    public function edit(Purchases $purchase)
    {
        $customers = Customers::all();
        return view('purchases.edit', compact('purchases','customers'));
    }

    public function update(Request $request, Purchases $purchases)
    {
        $validated = $request->validate([
            'cust_id' => 'required|exists:customers,cust_id',
            'date'    => 'required|date',
            'total'   => 'required|numeric',
        ]);

        $purchases->update($validated);

        return redirect()->route('purchases.index')
                         ->with('success', 'Purchase updated.');
    }

    public function destroy(Purchases $purchases)
    {
        $purchases->delete();

        return redirect()->route('purchases.index')
                         ->with('success', 'Purchase deleted.');
    }
}

