@extends('layouts.app')

@section('page-title', 'Order Details')

@section('content')
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

                    <h5>Products:</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td><img src="{{ asset('uploads/products/'.$item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px;"></td>
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
@endsection
