@extends('layouts.app')

@section('page-title', 'Create Order for Customer')

@section('content')
<div class="container-flex">
    <div class="row justify-content-center mt-4">
        <div class="col-md-10">
           
            <h3 class="text-dark mt-4">Create Order for {{ $customers->name }}</h3>

            @if(Session::has('success'))
                <div class="col-md-10 mt-4">
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                </div>
            @endif

            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#productModal">
                Add Products
            </button>

            <div class="card border-0 shadow-1g my-4">
    <div class="card-header bg-dark">
        <h3 class="text-white">Products</h3>
    </div>
    <div class="card-body">
    <table class="table table-responsive-sm">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Image</th>
                <th>Customization</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody id="cart-products">
            @foreach($cartItems as $cartItem)
                @php
                    $product = $products->find($cartItem->product_id);
                    $options = json_decode($cartItem->option, true);
                @endphp
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ $product->price }}</td>
                    <td><img src="{{ asset('uploads/products/' . $product->image) }}" width="50" alt="Product Image"></td>
                    <td>
                        <!-- Display customization options dynamically -->
                        @if (!empty($options))
                            @foreach ($options as $optionId => $optionValue)
                                @php
                                    $option = App\Models\Option::find($optionId);
                                    $optionValueModel = App\Models\ProductOptionValue::where('option_id', $optionId)->where('value', $optionValue)->first();
                                @endphp
                                @if ($option && $optionValueModel)
                                    <strong>{{ ucfirst($option->name) }}:</strong> {{ $optionValueModel->value }}
                                    @if ($option->type === 'color')
                                        <div class="color-box" style="background-color: {{ strtolower($optionValueModel->value) }}; width: 20px; height: 20px; display: inline-block;"></div>
                                    @endif
                                    <br>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <input type="number" name="products[{{ $product->id }}]" class="form-control form-control-sm quantity" value="{{ $cartItem->quantity }}" min="1" data-price="{{ $product->price }}">
                    </td>
                    <td class="subtotal">${{ $cartItem->price * $cartItem->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right">
        <strong>Total: $<span id="total" name="total">0</span></strong>
    </div>
</div>




</div>

            <div class="card border-0 shadow-1g my-4">
    <div class="card-header bg-dark">
        <h3 class="text-white">Shipping Details</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.store',$customers->id) }}" method="POST">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customers->id }}">
            <input type="hidden" name="total" id="order-total">
            <input type="hidden" name="total_quantity" value="0">
            <input type="hidden" name="status" value="Pending">
            <div id="quantity-inputs"></div> 

            <div class="row">
                <!-- Shipping Address Section -->
                <div class="col-md-6">
                    <h5>Shipping Address</h5>
                    <div class="form-group">
                        <label for="shipping_address1">Address Line 1</label>
                        <input type="text" name="shipping_address1" class="form-control" value="{{ $customers->address->address1 }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_address2">Address Line 2</label>
                        <input type="text" name="shipping_address2" class="form-control" value="{{ $customers->address->address2 }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_city">City</label>
                        <input type="text" name="shipping_city" class="form-control" value="{{ $customers->address->city }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_state">State</label>
                        <input type="text" name="shipping_state" class="form-control" value="{{ $customers->address->state }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_pincode">Pincode</label>
                        <input type="text" name="shipping_pincode" class="form-control" value="{{ $customers->address->pincode }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping_country">Country</label>
                        <input type="text" name="shipping_country" class="form-control" value="{{ $customers->address->country->name }}" required>
                    </div>
                </div>

                <!-- Billing Address Section -->
                <div class="col-md-6">
                    <h5>Billing Address</h5>
                    <div class="form-group">
                        <input type="checkbox" id="same_as_shipping" onclick="copyShippingAddress()">
                        <label for="same_as_shipping">Billing address same as shipping</label>
                    </div>
                    <div class="form-group">
                        <label for="billing_address1">Address Line 1</label>
                        <input type="text" name="billing_address1" class="form-control" id="billing_address1" required>
                    </div>
                    <div class="form-group">
                        <label for="billing_address2">Address Line 2</label>
                        <input type="text" name="billing_address2" class="form-control" id="billing_address2">
                    </div>
                    <div class="form-group">
                        <label for="billing_city">City</label>
                        <input type="text" name="billing_city" class="form-control" id="billing_city" required>
                    </div>
                    <div class="form-group">
                        <label for="billing_state">State</label>
                        <input type="text" name="billing_state" class="form-control" id="billing_state" required>
                    </div>
                    <div class="form-group">
                        <label for="billing_pincode">Pincode</label>
                        <input type="text" name="billing_pincode" class="form-control" id="billing_pincode" required>
                    </div>
                    <div class="form-group">
                        <label for="billing_country">Country</label>
                        <input type="text" name="billing_country" class="form-control" id="billing_country" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="shipping_option">Shipping Option</label>
                <select name="shipping_option" class="form-control" required>
                    <option value="Standard">Normal</option>
                    <option value="Express">Express</option>
                </select>
            </div>
            <div class="form-group">
                <label for="delivery_date">Delivery Date</label>
                <input type="date" name="delivery_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Create Order</button>
        </form>
    </div>
</div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Select Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-products-form" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $customers->id }}">

                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Customization</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            @foreach($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="product-checkbox" name="products[]" value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ asset('uploads/products/' . $product->image) }}">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td><img src="{{ asset('uploads/products/' . $product->image) }}" width="50" alt="Product Image"></td>
                                    <td>${{ $product->price }}</td>
                                    <td>
                                        <!-- Customization Options -->
                                        @foreach ($product->options as $option)
                                            @if ($option->status == 1)
                                                <div class="form-group">
                                                    <label><strong>{{ $option->name }}</strong></label><br>
                                                    @if ($option->type === 'radio')
                                                        <div class="size-selector">
                                                            @foreach ($option->values as $value)
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="customization_options[{{ $product->id }}][{{ $option->id }}]" id="option_{{ $product->id }}_{{ $option->id }}_{{ $value->id }}" value="{{ $value->value }}" data-price="{{ $value->price }}">
                                                                    <label class="form-check-label" for="option_{{ $product->id }}_{{ $option->id }}_{{ $value->id }}">{{ $value->value }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @elseif ($option->type === 'checkbox')
                                                        <div class="color-selector">
                                                            @foreach ($option->values as $value)
                                                                <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" name="customization_options[{{ $product->id }}][{{ $option->id }}]" id="option_{{ $product->id }}_{{$value->id }}" value="{{ $value->value }}" data-price="{{ $value->price }}">

                                                                    <label class="form-check-label" for="option_{{ $product->id }}_{{ $option->id }}_{{ $value->id }}" style="background-color: {{ strtolower($value->value) }}; width: 30px; height: 30px; display: inline-block; margin-right: 10px; border-radius: 50%;"></label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @elseif ($option->type === 'dropdown')
                                                        <select class="form-control" name="customization_options[{{ $product->id }}][{{ $option->id }}]">
                                                            <option value="" disabled selected>Select a {{ strtolower($option->name) }}</option>
                                                            @foreach ($option->values as $value)
                                                                <option value="{{ $value->value }}" data-image="{{ asset('uploads/products/' . $value->image) }}" data-price="{{ $value->price }}">
                                                                    {{ $value->value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="no-products" class="text-danger" style="display:none;">No products selected.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add-selected-products">Add Selected Products</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add these in the head section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('add-selected-products').addEventListener('click', function(event) {
    event.preventDefault();

    const formData = new FormData(document.getElementById('add-products-form'));
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const selectedProducts = [];

    if (checkboxes.length === 0) {
        document.getElementById('no-products').style.display = 'block';
        return;
    } else {
        document.getElementById('no-products').style.display = 'none';
    }

    checkboxes.forEach(checkbox => {
        const productId = checkbox.value;
        selectedProducts.push(productId);

        // Log product ID
        console.log(`Processing product ID: ${productId}`);

        // Fetch customization options for the product
        const customizationOptions = {};
        const options = document.querySelectorAll(`input[name^="customization_options[${productId}]"], select[name^="customization_options[${productId}]"]`);

        options.forEach(option => {
            const optionName = option.name;
            const optionId = optionName.split('][')[1].replace(']', '');

            if (option.type === 'radio' && option.checked) {
                customizationOptions[optionId] = option.value;
                console.log(`Radio option selected - Product ID: ${productId}, Option ID: ${optionId}, Value: ${option.value}`);
            } else if (option.type === 'checkbox' && option.checked) {
                // If a checkbox is selected, include its value in customizationOptions
                if (!customizationOptions[optionId]) {
                    customizationOptions[optionId] = [];
                }
                customizationOptions[optionId].push(option.value);
                console.log(`Checkbox option selected - Product ID: ${productId}, Option ID: ${optionId}, Value: ${option.value}`);
            } else if (option.tagName === 'SELECT') {
                customizationOptions[optionId] = option.value;
                console.log(`Dropdown option selected - Product ID: ${productId}, Option ID: ${optionId}, Value: ${option.value}`);
            }
        });

        // Log customization options
        console.log(`Customization options for product ID ${productId}:`, customizationOptions);

        // Append customization options as JSON string to formData
        formData.append(`customizations[${productId}]`, JSON.stringify(customizationOptions));
    });

    // Log formData entries to verify the data
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    fetch("{{ route('cart.add') }}", {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            $('#productModal').modal('hide'); // Close the modal after successful addition
            // Optionally, refresh cart items or update the UI
        }
    })
    .catch(error => {
        console.error('Error adding products to cart:', error);
    });
});

function updateTotal() {
    let total = 0;
    let totalQuantity = 0;
    const quantities = {};

    // Calculate total and total quantity
    document.querySelectorAll('.subtotal').forEach(subtotal => {
        total += parseFloat(subtotal.innerText.replace('$', ''));
    });

    document.querySelectorAll('.quantity').forEach(quantityInput => {
        const quantity = parseInt(quantityInput.value);
        totalQuantity += quantity;
        const productId = quantityInput.name.match(/\d+/)[0]; // Extract product ID from input name
        quantities[productId] = quantity;
    });

    // Update total display and hidden input fields
    document.getElementById('total').innerText = total.toFixed(2);
    document.querySelector('input[name="total"]').value = total.toFixed(2);
    document.querySelector('input[name="total_quantity"]').value = totalQuantity;

    // Update hidden quantity inputs
    const quantityInputsContainer = document.getElementById('quantity-inputs');
    quantityInputsContainer.innerHTML = ''; // Clear existing inputs
    for (const productId in quantities) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `products[${productId}]`;
        input.value = quantities[productId];
        quantityInputsContainer.appendChild(input);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateTotal(); // Calculate total on page load
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity')) {
        const quantity = e.target.value;
        const price = e.target.getAttribute('data-price');
        const subtotal = parseFloat(price) * parseInt(quantity);
        e.target.closest('tr').querySelector('.subtotal').innerText = '$' + subtotal.toFixed(2);
        updateTotal();
    }
});


</script>
<script>
function copyShippingAddress() {
    const isChecked = document.getElementById('same_as_shipping').checked;
    
    if (isChecked) {
        document.getElementById('billing_address1').value = document.querySelector('input[name="shipping_address1"]').value;
        document.getElementById('billing_address2').value = document.querySelector('input[name="shipping_address2"]').value;
        document.getElementById('billing_city').value = document.querySelector('input[name="shipping_city"]').value;
        document.getElementById('billing_state').value = document.querySelector('input[name="shipping_state"]').value;
        document.getElementById('billing_pincode').value = document.querySelector('input[name="shipping_pincode"]').value;
        document.getElementById('billing_country').value = document.querySelector('input[name="shipping_country"]').value;
    } else {
        document.getElementById('billing_address1').value = '';
        document.getElementById('billing_address2').value = '';
        document.getElementById('billing_city').value = '';
        document.getElementById('billing_state').value = '';
        document.getElementById('billing_pincode').value = '';
        document.getElementById('billing_country').value = '';
    }
}
</script>
@endsection
