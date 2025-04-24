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
    Edit Purchase Item
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
      <form method="post" action="{{ route('purchases_details.update', $purchases_details->id) }}">
          @csrf
          @method('PATCH')
          <input type="hidden" name="pur_id" value="{{ $purchases_details->pur_id }}">
          <input type="hidden" name="cust_id" value="{{ $purchases_details->cust_id }}">
          
          <div class="form-group">
              <label>Purchase</label>
              <input type="text" class="form-control" value="Purchase #{{ $purchases_details->pur_id }}" disabled>
          </div>
          
          <div class="form-group">
              <label for="item_id">Item</label>
              <select class="form-control" name="item_id" id="item_select">
                <option value="">Select an item</option>
                @foreach($items as $item)
                  <option value="{{ $item->item_id }}" 
                          data-price="{{ $item->list_price }}" 
                          data-name="{{ $item->item_name }}"
                          {{ $purchases_details->item_id == $item->item_id ? 'selected' : '' }}>
                    {{ $item->item_name }} - ${{ number_format($item->list_price, 2) }}
                  </option>
                @endforeach
              </select>
          </div>
          
          <div class="form-group">
              <label for="item_name">Item Name</label>
              <input type="text" class="form-control" name="item_name" id="item_name" value="{{ $purchases_details->item_name }}" required/>
          </div>
          
          <div class="form-group">
              <label for="price_paid">Price</label>
              <input type="number" step="0.01" class="form-control" name="price_paid" id="price_paid" value="{{ $purchases_details->price_paid }}" required/>
          </div>
          
          <div class="form-group">
              <label for="qty">Quantity</label>
              <input type="number" min="1" class="form-control" name="qty" value="{{ $purchases_details->qty }}" required/>
          </div>
          
          <button type="submit" class="btn btn-block btn-danger">Update Item</button>
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