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
    <h2>Customers</h2>
    <a href="{{ route('customers.create') }}" class="btn btn-success">Add Customer</a>
  </div>
  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>ID</td>
          <td>Name</td>
          <td>Email</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customers)
        <tr>
            <td>{{$customers->cust_id}}</td>
            <td>{{$customers->name}}</td>
            <td>{{$customers->email}}</td>
            <td class="text-center">
                <a href="{{ route('customers.edit', $customers->cust_id)}}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('customers.destroy', $customers->cust_id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <div class="mt-3">
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
@endsection