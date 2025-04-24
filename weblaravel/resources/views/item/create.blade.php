@extends('layout')

@section('content')

<style>
    .container {
      max-width: 450px;
    }
    .push-top {
      margin-top: 50px;
    }
</style>

<div class="card push-top">
  <div class="card-header">
    Add Item
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
      <form method="post" action="{{ route('item.store') }}">
          <div class="form-group">
              @csrf
              <label for="item_name">Item Name</label>
              <input type="text" class="form-control" name="item_name" value="{{ old('item_name') }}"/>
          </div>
          <div class="form-group">
              <label for="list_price">Price</label>
              <input type="number" step="0.01" class="form-control" name="list_price" value="{{ old('list_price') }}"/>
          </div>
          <div class="form-group">
              <label for="qtyInStock">Quantity in Stock</label>
              <input type="number" class="form-control" name="qtyInStock" value="{{ old('qtyInStock') }}"/>
          </div>
          <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
          </div>
          <button type="submit" class="btn btn-block btn-danger">Create Item</button>
      </form>
  </div>
</div>
@endsection
