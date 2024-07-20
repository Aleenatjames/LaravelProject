@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
              
                <h3 class="text-dark mt-4">Create New Customer</h3>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.customers.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Customer Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select name="country_id" class="form-control" required>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address1">Address Line 1</label>
                        <input type="text" name="address1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="address2">Address Line 2</label>
                        <input type="text" name="address2" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" name="state" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="pincode">Pincode</label>
                        <input type="text" name="pincode" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Create Customer</button>
                </form>
            </div>
        </div>
    </div>
@endsection
