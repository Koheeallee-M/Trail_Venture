<?php

namespace App\Http\Controllers;

use App\Models\PurchasesDetails;
use App\Models\Purchases;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasesDetailsController extends Controller
{
    public function index()
    {
        $purchases_details = PurchasesDetails::with(['purchases','item'])->orderBy('created_at', 'desc')->paginate(3);
        return view('purchases_details.index', compact('purchases_details'));
    }

    public function create()
    {
        $purchases = Purchases::all();
        $items     = Item::all();
        return view('purchases_details.create', compact('purchases','items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pur_id'     => 'required|exists:purchases,pur_id',
            'cust_id'    => 'required|exists:customers,cust_id',
            'item_id'    => 'required|exists:items,item_id',
            'item_name'  => 'required|string',
            'price_paid' => 'required|numeric',
            'qty'        => 'required|integer',
        ]);

        PurchasesDetails::create($validated);

        // Optionally recalc total
        $total = DB::table('purchases_details')
            ->where('pur_id', $validated['pur_id'])
            ->sum(DB::raw('price_paid * qty'));

        DB::table('purchases')
          ->where('pur_id', $validated['pur_id'])
          ->update(['total' => $total]);

        return redirect()->route('purchases_details.index')
                         ->with('success', 'Detail added.');
    }

    public function show(PurchasesDetails $purchases_details)
    {
        return view('purchases_details.show', compact('purchases_details'));
    }

    public function edit(PurchasesDetails $purchases_details)
    {
        $purchases = Purchases::all();
        $items     = Item::all();
        return view('purchases_details.edit', compact('purchases_details','purchases','items'));
    }

    public function update(Request $request, PurchasesDetails $purchases_details)
    {
        $validated = $request->validate([
            'item_name'  => 'required|string',
            'price_paid' => 'required|numeric',
            'qty'        => 'required|integer',
        ]);

        $purchases_details->update($validated);

        // Recalc total again
        $total = DB::table('purchases_details')
            ->where('pur_id', $purchases_details->pur_id)
            ->sum(DB::raw('price_paid * qty'));

        DB::table('purchases')
          ->where('pur_id', $purchases_details->pur_id)
          ->update(['total' => $total]);

        return redirect()->route('purchases_details.index')
                         ->with('success', 'Detail updated.');
    }

    public function destroy(PurchasesDetails $purchases_details)
    {
        $purId = $purchases_details->pur_id;
        $purchases_details->delete();

        // Recalc total one last time
        $total = DB::table('purchases_details')
            ->where('pur_id', $purId)
            ->sum(DB::raw('price_paid * qty'));

        DB::table('purchases')
          ->where('pur_id', $purId)
          ->update(['total' => $total]);

        return redirect()->route('purchases_details.index')
                         ->with('success', 'Detail deleted.');
    }
}

