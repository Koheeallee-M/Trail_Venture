@extends('layout')

@section('content')

<style>
    .container {
      max-width: 650px;
    }
    .push-top {
      margin-top: 50px;
    }
</style>

<div class="card push-top">
  <div class="card-header">
    Create New Purchase
  </div>

  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('purchases.store') }}">
          @csrf
          <div class="form-group">
              <label for="cust_id">Customer</label>
              <select class="form-control" name="cust_id">
                <option value="">Select a customer</option>
                @foreach($customers as $customers)
                  <option value="{{ $customers->cust_id }}" {{ old('cust_id') == $customers->cust_id ? 'selected' : '' }}>
                    {{ $customers->name }}
                  </option>
                @endforeach
              </select>
          </div>
          <div class="form-group">
              <label for="date">Purchase Date</label>
              <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}"/>
          </div>
          <div class="form-group">
              <label for="total">Total Amount</label>
              <input type="number" step="0.01" class="form-control" name="total" value="{{ old('total') }}"/>
          </div>
          <button type="submit" class="btn btn-block btn-danger">Create Purchase</button>
      </form>
  </div>
</div>
@endsection