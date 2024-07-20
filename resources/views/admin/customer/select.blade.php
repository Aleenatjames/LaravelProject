@extends('layouts.app')

@section('page-title', 'Orders')

@section('content')
    <div class="container-flex">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 d-flex justify-content-end">
            <div class="col-md">
            <a href="{{route('order.index')}}" class="btn btn-dark">Back</a>
            </div>
                <a href="{{route('admin.customers.create')}}" class="btn btn-dark">Create Customer</a>
            </div>
        
       
            @if(Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                {{Session::get('success')}}
                </div>
            </div>
            @endif
            <div class="col-md-11">
                <div class="card borde-0 shadow-1g my-4">
                    <div class="card-header bg-dark">
                        <h3 class="text-white">Customers List</h3>
                    </div>  
                    <div class="card-body">
                    <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                             <th>Pincode</th>
                             <th>Country</th>
                             <th>Mobile Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                    @if($customer->address)
                        {{ $customer->address->address1 }}, {{ $customer->address->city }}, {{ $customer->address->state }} 
                    @else
                        No address available
                    @endif
                </td>
        
                <td>@if($customer->address){{ $customer->address->pincode }} 
            @else
                      
                    @endif
            </td>
            <td>@if(isset($customer->address) && isset($customer->address->country))
                {{ $customer->address->country->name}} 
            @else
                      
                    @endif
            </td>
            <td>@if($customer->address){{ $customer->address->mobileno }} 
            @else
                      
                    @endif
            </td>
                                <td>
                                    <a href="{{ route('orders.CustomerSelected', $customer->id) }}" class="btn btn-dark">Select</a>
                                </td>
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
