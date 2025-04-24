@extends('layout')

@section('content')

<style>
  .push-top {
    margin-top: 50px;
  }
</style>

<div class="push-top">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif

  <div class="d-flex justify-content-between mb-3">
    <h2>Purchase Details</h2>
    <a href="{{ route('purchases_details.create') }}" class="btn btn-success">Add Purchase Detail</a>
  </div>

  <table class="table">
    <thead>
      <tr class="table-warning">
        <td>ID</td>
        <td>Purchase ID</td>
        <td>Customer ID</td>
        <td>Item ID</td>
        <td>Item Name</td>
        <td>Quantity</td>
        <td>Price Paid</td>
        <td>Line Total</td>
        <td class="text-center">Action</td>
      </tr>
    </thead>
    <tbody>
      @foreach($purchases_details as $detail)
        <tr>
          <td>{{ $detail->id }}</td>
          <td>
            <a href="{{ route('purchases.show', ['purchases' => $detail->pur_id]) }}">
              #{{ $detail->pur_id }}
            </a>
          </td>
          <td>{{ $detail->purchases->cust_id ?? 'N/A' }}</nobr>
          <td>{{ $detail->item_id }}</td>
          <td>{{ $detail->item_name }}</td>
          <td>{{ $detail->qty }}</td>
          <td>${{ number_format($detail->price_paid, 2) }}</td>
          <td>${{ number_format($detail->qty * $detail->price_paid, 2) }}</td>
          <td class="text-center">
            <a href="{{ route('purchases_details.show', $detail->id) }}" class="btn btn-info btn-sm">View</a>
            <a href="{{ route('purchases_details.edit', $detail->id) }}" class="btn btn-primary btn-sm">Edit</a>
            <form action="{{ route('purchases_details.destroy', $detail->id) }}" method="post" style="display: inline-block">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Pagination --}}
  <div class="d-flex justify-content-between mt-3">
    @if(!$purchases_details->onFirstPage())
      <a href="{{ $purchases_details->previousPageUrl() }}" class="btn btn-primary">Prev</a>
    @else
      <span></span>
    @endif

    @if($purchases_details->hasMorePages())
      <a href="{{ $purchases_details->nextPageUrl() }}" class="btn btn-primary">Next</a>
    @endif
  </div>
</div>
@endsection