<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $item = Item::all();
        return view('item.index', compact('item'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255',
            'list_price'  => 'required|numeric',
            'description' => 'nullable|string',
            'qtyInStock'  => 'required|integer',
        ]);

        Item::create($validated);

        return redirect()->route('item.index')
                         ->with('success', 'Item created.');
    }

    public function show(Item $item)
    {
        return view('item.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('item.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name'   => 'required|string|max:255',
            'list_price'  => 'required|numeric',
            'description' => 'nullable|string',
            'qtyInStock'  => 'required|integer',
        ]);

        $item->update($validated);

        return redirect()->route('item.index')
                         ->with('success', 'Item updated.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('item.index')
                         ->with('success', 'Item deleted.');
    }
}

