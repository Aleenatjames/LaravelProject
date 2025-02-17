<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4>Order Details</h4>
                </div>
                <div class="card-body">
                    <h5>Order ID: {{ $order->id }}</h5>
                    <p>Customer Name: {{ $order->customer_name }}</p>
                    <p>Customer Address: {{ $order->customer_address }}</p>
                    <p>Status: {{ $order->status }}</p>
                    <p>Total Amount: ${{ $order->total_amount }}</p>
                    <p>Delivery Date: {{ $order->delivery_date}}</p>
                    <p>Shipping Option: {{ $order->shipping_option }}</p>

                    <h5>Products:</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orderItems as $item)
                    @php
        
                 $options = json_decode($item->option, true);
                      @endphp
                            <tr>
                                <td>{{ $item->product->name }}
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
                                <td>{{ $item->qty }}</td>
                                <td>${{ $item->price }}</td>
                                <td>${{ $item->price * $item->qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
