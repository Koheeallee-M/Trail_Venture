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
    Add Item to Purchase
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
      <form method="post" action="{{ route('purchases_details.store') }}">
          @csrf
          <input type="hidden" name="pur_id" value="{{ request()->pur_id ?? '' }}">
          <input type="hidden" name="cust_id" value="{{ $purchases->cust_id ?? '' }}">
          
          @if(empty(request()->pur_id))
          <div class="form-group">
              <label for="pur_id">Purchase</label>
              <select class="form-control" name="pur_id" required>
                <option value="">Select a purchase</option>
                @foreach($purchases as $purchases)
                  <option value="{{ $purchases->pur_id }}">
                    #{{ $purchases->pur_id }} - {{ $purchases->customers->name ?? 'Unknown' }} ({{ $purchases->date }})
                  </option>
                @endforeach
              </select>
          </div>
          @else
          <div class="form-group">
              <label>Purchase</label>
              <input type="text" class="form-control" value="Purchases #{{ request()->pur_id }}" disabled>
          </div>
          @endif
          
          <div class="form-group">
              <label for="item_id">Item</label>
              <select class="form-control" name="item_id" id="item_select" required>
                <option value="">Select an item</option>
                @foreach($items as $item)
                  <option value="{{ $item->item_id }}" 
                          data-price="{{ $item->list_price }}" 
                          data-name="{{ $item->item_name }}">
                    {{ $item->item_name }} - ${{ number_format($item->list_price, 2) }}
                  </option>
                @endforeach
              </select>
          </div>
          
          <div class="form-group">
              <label for="item_name">Item Name</label>
              <input type="text" class="form-control" name="item_name" id="item_name" value="{{ old('item_name') }}" required/>
          </div>
          
          <div class="form-group">
              <label for="price_paid">Price</label>
              <input type="number" step="0.01" class="form-control" name="price_paid" id="price_paid" value="{{ old('price_paid') }}" required/>
          </div>
          
          <div class="form-group">
              <label for="qty">Quantity</label>
              <input type="number" min="1" class="form-control" name="qty" value="{{ old('qty', 1) }}" required/>
          </div>
          
          <button type="submit" class="btn btn-block btn-danger">Add Item to Purchase</button>
      </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_select');
    const itemName = document.getElementById('item_name');
    const pricePaid = document.getElementById('price_paid');
    
    if (itemSelect) {
        itemSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                itemName.value = selectedOption.getAttribute('data-name');
                pricePaid.value = selectedOption.getAttribute('data-price');
            } else {
                itemName.value = '';
                pricePaid.value = '';
            }
        });
    }
});
</script>
@endsection