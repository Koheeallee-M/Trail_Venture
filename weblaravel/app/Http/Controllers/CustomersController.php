<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customers::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);

        Customers::create($validated);

        return redirect()->route('customers.index')
                         ->with('success', 'Customer created.');
    }

    public function show(Customers $customers)
    {
        $customers->load('purchases'); // eager‐load
        return view('customers.show', compact('customers'));
    }

    public function edit(Customers $customers)
    {
        return view('customers.edit', compact('customers'));
    }

    public function update(Request $request, Customers $customers)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:customers,email,{$customers->cust_id},cust_id",
        ]);

        $customers->update($validated);

        return redirect()->route('customers.index')
                         ->with('success', 'Customers updated.');
    }

    public function destroy(Customers $customers)
    {
        $customers->delete();

        return redirect()->route('customers.index')
                         ->with('success', 'Customer deleted.');
    }
}

