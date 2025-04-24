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
    <h2>Purchase Records</h2>
    <a href="{{ route('purchases.create') }}" class="btn btn-success">New Purchase</a>
  </div>
  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>Purchase ID</td>
          <td>Customer</td>
          <td>Date</td>
          <td>Total</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($purchases as $purchases)
        <tr>
            <td>{{ $purchases->pur_id }}</td>
            <td>{{ $purchases->customers->name ?? 'N/A' }}</td>
            <td>{{ $purchases->date }}</td>
            <td>${{ number_format($purchases->total, 2) }}</td>
            <td class="text-center">
                <a href="{{ route('purchases.show', $purchases) }}" class="btn btn-info btn-sm">View</a>
                <a href="{{ route('purchases.edit', $purchases) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('purchases.destroy', ['purchases' => $purchases]) }}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
@endsection
