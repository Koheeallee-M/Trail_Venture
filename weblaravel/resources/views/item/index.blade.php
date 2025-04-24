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
    <h2>Items</h2>
    <a href="{{ route('item.create') }}" class="btn btn-success">Add Item</a>
  </div>
  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>ID</td>
          <td>Name</td>
          <td>Price</td>
          <td>Quantity</td>
          <td>Description</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($item as $item)
        <tr>
            <td>{{$item->item_id}}</td>
            <td>{{$item->item_name}}</td>
            <td>${{number_format($item->list_price, 2)}}</td>
            <td>{{$item->qtyInStock}}</td>
            <td>{{Str::limit($item->description, 30)}}</td>
            <td class="text-center">
                <a href="{{ route('item.edit', $item->item_id)}}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('item.destroy', $item->item_id)}}" method="post" style="display: inline-block">
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