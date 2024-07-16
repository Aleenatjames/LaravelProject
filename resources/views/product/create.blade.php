<!doctype html>
@extends('layouts.app')

@section('page-title', 'Orders')

@section('content')
    <div class="bg-dark py-3">
        <h3 class="text-white text-center">Product Details</h3>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 d-flex justify-content-end">
                <a href="{{ route('product.index') }}" class="btn btn-dark">Back</a>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg my-4">
                    <div class="card-header bg-dark">
                        <h3 class="text-white">Add Product</h3>
                    </div>
                    @if(Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                {{Session::get('success')}}
                </div>
            </div>
            @endif
                    <form enctype="multipart/form-data" action="{{ route('product.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label h5">Name</label>
                                <input value="{{ old('name') }}" type="text" class="@error('name') is-invalid @enderror form-control form-control-lg" placeholder="Name" name="name">
                                @error('name')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label h5">Price</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Price" name="price">
                                @error('price')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label h5">Category</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Category" name="category" id="category">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label h5">Description</label>
                                <textarea placeholder="Description" type="text" class="form-control form-control-lg" name="description" cols="30" rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label h5">Estimated Delivery</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Estimated Delivery" name="estimated_delivery">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label h5">Image</label>
                                <input value="{{ old('image') }}" type="file" class="@error('image') is-invalid @enderror form-control form-control-lg" name="image">
                                @error('image')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-lg btn-secondary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection