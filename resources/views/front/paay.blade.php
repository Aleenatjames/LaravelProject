<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Summary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-fluid{
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .container2{
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .btn-proceed {
            background-color: rgb(224, 70, 9);
            border-color: rgb(224, 70, 9);
        }
        .btn-proceed:hover {
            background-color: #23272b;
            border-color: #23272b;
        }
        .payment-options {
            margin-top: 20px;
            display: none;
        }
        .red {
            color: red;
        }
        *{
            margin-top: 10px;
        }
    </style>
</head>
<body>
    
                    @if(Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                {{Session::get('success')}}
                </div>
            </div>
            @endif
    <div class="container">
       <div class="row">
        <div class="col-sm-6">
        <h2> Address</h2>

<form action="{{ route('front.updateAddress') }}" method="POST">
    @csrf
    <input type="hidden" name="code" value="{{ request()->input('code') }}">

    <div class="form-group">
        <label for="inputFullName">Full Name</label>
        <input type="text" class="form-control" id="inputFullName" name="name" required placeholder="Enter your full name" value="{{ $address ? $address->name : '' }}">
    </div>
    <div class="form-group">
        <label for="inputAddress">Address Line 1</label>
        <input type="text" class="form-control" id="inputAddress" name="address1" placeholder="1234 Main St" value="{{ $address ? $address->address1 : '' }}">
    </div>
    <div class="form-group">
        <label for="inputAddress2">Address Line 2</label>
        <input type="text" class="form-control" id="inputAddress2" name="address2" placeholder="Apartment, studio, or floor" value="{{ $address ? $address->address2 : '' }}">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputCity">City</label>
            <input type="text" name="city" class="form-control" id="inputCity" value="{{ $address ? $address->city : '' }}">
        </div>
        <div class="form-group col-md-4">
            <label for="inputState">State</label>
            <select id="inputState" name="state" class="form-control">
                <option value="New South Wales" {{ $address && $address->state == 'New South Wales' ? 'selected' : '' }}>New South Wales</option>
                <option value="Victoria" {{ $address && $address->state == 'Victoria' ? 'selected' : '' }}>Victoria</option>
                <option value="Queensland" {{ $address && $address->state == 'Queensland' ? 'selected' : '' }}>Queensland</option>
                <!-- Add your state options here -->
            </select>
        </div>
        <div class="col-sm-4">
        <label for="inputCountry">Country</label>
        <select name="country" id="country" class="form-control">
            <option>Select a Country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ $address && $address->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
        </select>
    </div>
        <div class="form-group col-md-2">
            <label for="inputZip">PinCode</label>
            <input type="text" name="pincode" class="form-control" id="inputZip" value="{{ $address ? $address->pincode : '' }}">
        </div>
    </div>
    <div class="form-group">
        <label for="inputLandmark">Landmark</label>
        <input type="text" class="form-control" id="inputLandmark" name="landmark" placeholder="Nearby landmark" value="{{ $address ? $address->landmark : '' }}">
    </div>
    <div class="form-group">
        <label for="inputPhoneNumber">Mobile Number</label>
        <input type="tel" name="mobileno" class="form-control" id="inputPhoneNumber" placeholder="Enter your phone number" value="{{ $address ? $address->mobileno : '' }}">
    </div>
    <button type="submit" class="btn btn-primary">Save Address</button>
</form>

           
        </div>
        <div class="col-sm-6">
        <div class="container2">
           
                
                    @if(Session::has('error'))
                        <div class="col-md-10 mt-4">
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                        </div>
                    @endif

                    <h1>Cart Summary</h1>
                    <!-- Display items in the cart -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through cart items -->
                                @foreach($cartItems as $cartItem)
                                @php
                                    $options = json_decode($cartItem->option, true);
                                @endphp
                                @if($cartItem->generated_by !== 'admin')
                                <tr>
                                    <td>{{ $cartItem->name }}
                                        @if(!empty($options['size']))
                                            <strong>Size:</strong> {{ $options['size'] }}<br>
                                        @endif
                                        @if(!empty($options['color']))
                                            <strong>Color:</strong> {{ $options['color'] }}
                                            <div class="color-box" style="background-color: {{ $options['color'] }}; width: 20px; height: 20px; display: inline-block;"></div>
                                            <br>
                                        @endif
                                        @if(!empty($options['flavor']))
                                            <strong>Flavor:</strong> {{ $options['flavor'] }}<br>
                                        @endif
                                    </td>
                                    <td>
                                        <img width="50" src="{{ asset('uploads/products/' . $cartItem->image) }}">
                                    </td>
                                    <td>${{ $cartItem->price }}</td>
                                    <td>{{ $cartItem->quantity }}</td>
                                    <td>${{ $cartItem->price * $cartItem->quantity }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cart summary details -->
                    @php
                        $subtotal = 0;
                        foreach ($cartItems as $cartItem) {
                            $subtotal += $cartItem->price * $cartItem->quantity;
                        }
                        $total = $subtotal; // Initialize total with subtotal
                        if (isset($giftCard)) {
                            // Reduce total by gift card amount
                            $total -= $giftCard['amount'];
                            // Ensure total doesn't go below zero
                            if ($total < 0) {
                                $total = 0;
                            }
                        }
                    @endphp

                    <!-- Display adjusted subtotal, shipping, and total -->
                    <div class="d-flex justify-content-between pb-2">
                        <div>Subtotal</div>
                        <div>${{ number_format($subtotal, 2) }}</div>
                    </div>
                    @if(isset($giftCard))
                        <div class="d-flex justify-content-between pb-2">
                            <div>Gift Card Applied</div>
                            <div class="red">-${{ number_format($giftCard['amount'], 2) }}</div>
                        </div>
                    @else 
                    <form onsubmit="return validateShipping()">
                    <div class="d-flex justify-content-between pb-2">
    <div>Shipping</div>
    <div class="d-flex">
        @php
            $shippingAmount = 0;
        @endphp
        @if($address->country_id == 100 && $total > 500)
            <div class="form-check form-check-inline mr-3 d-flex align-items-center">
                <input class="form-check-input" type="radio" name="shipping_option" id="freeShipping" value="0" checked onchange="updateShipping(0)">
                <label class="form-check-label ml-1" for="freeShipping">Free</label>
            </div>
            @php
                $shippingAmount = 0;
            @endphp
        @endif
        
        @foreach($shippingCharges as $charge)
            @if($charge->options != 'free') <!-- Exclude free option -->
                <div class="form-check form-check-inline mr-3 d-flex align-items-center">
                    <input class="form-check-input" type="radio" name="shipping_option" id="{{ $charge->options }}Shipping" value="{{ $charge->amount }}" onchange="updateShipping({{ $charge}})">
                    <label class="form-check-label ml-1" for="{{ $charge->options }}Shipping">{{ ucfirst($charge->options) }} (${{ number_format($charge->amount, 2) }})</label>
                </div>
            @endif
        @endforeach

    </div>
</div>
<input type="hidden" name="shipping_amount" id="hiddenShippingAmount" value="{{ $shippingAmount }}">
    <input type="hidden" name="total_amount" id="hiddenTotalAmount" value="{{ $total + $shippingAmount }}">
    <input type="hidden" name="shipping_option_selected" id="hiddenShippingOption" value="free">
@endif
    </form>
<div class="d-flex justify-content-between summery-end">
    <div>Total</div>
    <div id="totalAmount">${{ number_format($total + $shippingAmount, 2) }}</div>
</div>
<div class="form-group">
    <label for="deliveryDate">Select Delivery Date</label>
    <input type="text" id="deliveryDate" name="delivery_date" class="form-control">
</div>

                    <!-- Adjusted button based on gift card presence -->
                    @if(isset($giftCard))
                        <form method="post" action="{{ route('front.success') }}">
                            @csrf
                            <input type="hidden" name="status" value="Pending">
                            <input type="hidden" name="gift_card_id" value="{{ $giftCard->id }}">
                            <input type="hidden" name="gift_card_code" value="{{ $giftCard->code }}">
                            <input type="hidden" name="shipping_amount" id="hiddenShippingAmount" value="{{ $shippingAmount }}">
                            <input type="hidden" name="total_amount" id="hiddenTotalAmount" value="{{ $total + $shippingAmount }}">
                            <input type="hidden" name="shipping_option_selected" id="hiddenShippingOption" value="free">
                            <input type="hidden" name="delivery_date" id="hiddenDeliveryDate">

        
                            @if($total == 0)
                                <button type="submit" class="btn btn-dark btn-block btn-proceed">Buy with Gift Card</button>
                            @endif
                        </form>
                        @if($total > 0)
                            <a class="btn btn-dark btn-block btn-proceed" data-toggle="modal" data-target="#paymentModal">Proceed to pay</a>
                        @endif
                    @else
                        @if($total > 0)
                            <a class="btn btn-dark btn-block btn-proceed" data-toggle="modal" data-target="#paymentModal">Proceed to pay</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <!-- Payment Modal -->
@if($total > 0)
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Enter Payment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('front.success') }}">
                    @csrf
                    <input type="hidden" name="shipping_amount" id="modalShippingAmount" value="{{ $shippingAmount }}">
                    <input type="hidden" name="total_amount" id="modalTotalAmount" value="{{ $total + $shippingAmount }}">
                    <input type="hidden" name="shipping_option_selected" id="modalShippingOption" value="free">
                    <input type="hidden" name="delivery_date" id="modalHiddenDeliveryDate">


                    <div class="modal-body">
                        <div class="form-group">
                            <label for="holderName">Cardholder Name</label>
                            <input type="text" class="form-control" name="name" id="holderName" placeholder="Enter cardholder name">
                        </div>
                        <div class="form-group">
                            <label for="cardNumber">Card Number</label>
                            <input type="text" class="form-control" name="card_number" id="cardNumber" placeholder="Enter card number">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Enter CVV">
                        </div>
                        <div class="form-group">
                            <label for="expiryDate">Expiry Date</label>
                            <input type="text" class="form-control" name="expiry_date" id="expiryDate" placeholder="MM/YYYY">
                        </div>
                        <input type="hidden" name="status" value="Pending">
                        @if(isset($giftCard))
                            <input type="hidden" name="gift_card_id" value="{{ $giftCard->id }}">
                            <input type="hidden" name="gift_card_code" value="{{ $giftCard->code }}">
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const deliveryDateInput = document.querySelector('#deliveryDate');
    const hiddenDeliveryDate = document.querySelector('#hiddenDeliveryDate');
    const modalHiddenDeliveryDate = document.querySelector('#modalHiddenDeliveryDate');

    deliveryDateInput.addEventListener('change', function() {
        const selectedDate = deliveryDateInput.value;
        console.log(selectedDate);
        if (hiddenDeliveryDate) {
            hiddenDeliveryDate.value = selectedDate;
        }
        if (modalHiddenDeliveryDate) {
            modalHiddenDeliveryDate.value = selectedDate;
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        const selectedDate = deliveryDateInput.value;
        if (hiddenDeliveryDate) {
            hiddenDeliveryDate.value = selectedDate;
        }
        if (modalHiddenDeliveryDate) {
            modalHiddenDeliveryDate.value = selectedDate;
        }
    });
});
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script>
        
function updateShipping(charge) {
    const shippingAmount = charge.amount;
    const shippingOption = charge.options;

    console.log("Shipping Amount: ", shippingAmount);
    console.log("Shipping Option: ", shippingOption);

    // Update hidden inputs for the main form
    const hiddenShippingAmountMain = document.getElementById('hiddenShippingAmount');
    const hiddenShippingOptionMain = document.getElementById('hiddenShippingOption');
    const hiddenTotalAmountMain = document.getElementById('hiddenTotalAmount');

    if (hiddenShippingAmountMain) hiddenShippingAmountMain.value = shippingAmount;
    if (hiddenShippingOptionMain) hiddenShippingOptionMain.value = shippingOption;

    // Update hidden inputs for the modal form
    const modalShippingAmount = document.getElementById('modalShippingAmount');
    const modalShippingOption = document.getElementById('modalShippingOption');
    const modalTotalAmount = document.getElementById('modalTotalAmount');

    if (modalShippingAmount) modalShippingAmount.value = shippingAmount;
    if (modalShippingOption) modalShippingOption.value = shippingOption;

    // Update total amount display
    let subtotal = {{ $total }};
    let total = subtotal + shippingAmount;
    const totalAmountDisplay = document.getElementById('totalAmount');
    
    if (totalAmountDisplay) totalAmountDisplay.innerText = `$${total.toFixed(2)}`;
    if (hiddenTotalAmountMain) hiddenTotalAmountMain.value = total;
    if (modalTotalAmount) modalTotalAmount.value = total;
    console.log("Hidden Shipping Amount Main: ", document.getElementById('hiddenShippingAmount').value);
console.log("Hidden Total Amount Main: ", document.getElementById('hiddenTotalAmount').value);
console.log("Hidden Shipping Option Main: ", document.getElementById('hiddenShippingOption').value);

console.log("Modal Shipping Amount: ", document.getElementById('modalShippingAmount').value);
console.log("Modal Total Amount: ", document.getElementById('modalTotalAmount').value);
console.log("Modal Shipping Option: ", document.getElementById('modalShippingOption').value);

}
$('#paymentModal').on('shown.bs.modal', function () {
    updateModalShippingFields();
});

function updateModalShippingFields() {
    const hiddenShippingAmountMain = document.getElementById('hiddenShippingAmount').value;
    const hiddenShippingOptionMain = document.getElementById('hiddenShippingOption').value;
    const hiddenTotalAmountMain = document.getElementById('hiddenTotalAmount').value;

    const modalShippingAmount = document.getElementById('modalShippingAmount');
    const modalShippingOption = document.getElementById('modalShippingOption');
    const modalTotalAmount = document.getElementById('modalTotalAmount');

    modalShippingAmount.value = hiddenShippingAmountMain;
    modalShippingOption.value = hiddenShippingOptionMain;
    modalTotalAmount.value = hiddenTotalAmountMain;

    console.log("Modal Shipping Amount: ", modalShippingAmount.value);
    console.log("Modal Shipping Option: ", modalShippingOption.value);
    console.log("Modal Total Amount: ", modalTotalAmount.value);
}

function validateShipping() {
    const shippingOptions = document.getElementsByName('shipping_option');
    let isSelected = false;

    for (let option of shippingOptions) {
        if (option.checked) {
            isSelected = true;
            break;
        }
    }

    if (!isSelected) {
        alert('Please select a shipping option before proceeding.');
        return false;
    }

    return true;
}
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Calculate the maximum estimated delivery days from the cart items
        const cartItems = @json($cartItems);
        let maxEstimatedDeliveryDays = 0;

        cartItems.forEach(item => {
            if (item.product.estimated_delivery > maxEstimatedDeliveryDays) {
                maxEstimatedDeliveryDays = item.product.estimated_delivery;
            }
        });

        const currentDate = new Date();

        flatpickr("#deliveryDate", {
            dateFormat: "Y-m-d",
            minDate: currentDate,
            disable: [
                function(date) {
                    const selectedDate = new Date(date);
                    const minDeliveryDate = new Date();
                    minDeliveryDate.setDate(currentDate.getDate() + maxEstimatedDeliveryDays);

                    // Exclude Sundays
                    const isSunday = selectedDate.getDay() === 0;

                    return selectedDate < minDeliveryDate || isSunday;
                }
            ]
        });
    });
</script>







</script>

</body>
</html>

