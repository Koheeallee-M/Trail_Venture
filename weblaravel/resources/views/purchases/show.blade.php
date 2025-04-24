@extends('layout')

@section('content')

<style>
  .push-top {
    margin-top: 50px;
  }
</style>

<div class="push-top">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
      <h4>Purchase Details #{{ $purchases->pur_id }}</h4>
      <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back to Purchases</a>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <p><strong>Customer:</strong> {{ $purchases->customers->name ?? 'N/A' }}</p>
          <p><strong>Purchase Date:</strong> {{ $purchases->date }}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Purchase ID:</strong> {{ $purchases->pur_id }}</p>
          <p><strong>Total Amount:</strong> ${{ number_format($purchases->total, 2) }}</p>
        </div>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h4>Items in this Purchase</h4>
      <a href="{{ route('purchases_details.create', ['pur_id' => $purchases->pur_id]) }}" class="btn btn-success">Add Item</a>
    </div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr class="table-warning">
            <td>Item</td>
            <td>Price</td>
            <td>Quantity</td>
            <td>Subtotal</td>
            <td class="text-center">Action</td>
          </tr>
        </thead>
        <tbody>
          @forelse($purchases->purchases_details as $detail)
          <tr>
            <td>{{ $detail->item_name }}</td>
            <td>${{ number_format($detail->price_paid, 2) }}</td>
            <td>{{ $detail->qty }}</td>
            <td>${{ number_format($detail->price_paid * $detail->qty, 2) }}</td>
            <td class="text-center">
              <a href="{{ route('purchases_details.edit', $detail->id) }}" class="btn btn-primary btn-sm">Edit</a>
              <form action="{{ route('purchases_details.destroy', $detail->id) }}" method="post" style="display: inline-block">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" type="submit">Remove</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center">No items found in this purchase.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection