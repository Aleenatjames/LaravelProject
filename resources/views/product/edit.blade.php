@extends('layouts.app')

@section('page-title', 'Orders')

@section('content')
    <div class="bg-dark py-3">
        <h3 class="text-white text-center">Product Details</h3>
    </div>
    @if(Session::has('success'))
        <div class="col-md-10 mt-4">
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="col-md-10 mt-4">
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        </div>
    @endif
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
                        <h3 class="text-white">Edit Product</h3>
                    </div>
                    <form method="post" action="{{ route('product.update', $products->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label h5">Name</label>
                                <input value="{{ old('name', $products->name) }}" type="text" class="form-control form-control-lg" placeholder="Name" name="name">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label h5">Price</label>
                                <input value="{{ old('price', $products->price) }}" type="text" class="form-control form-control-lg" placeholder="Price" name="price">
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label h5">Category</label>
                                <input value="{{ old('category', $products->category) }}" type="text" class="form-control form-control-lg" placeholder="Category" name="category">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label h5">Description</label>
                                <textarea class="form-control form-control-lg" placeholder="Description" name="description" cols="30" rows="4">{{ old('description', $products->description) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="estimated_delivery" class="form-label h5">Estimated Delivery</label>
                                <input value="{{ old('estimated_delivery', $products->estimated_delivery) }}" type="text" class="form-control form-control-lg" placeholder="Estimated Delivery" name="estimated_delivery">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label h5">Image</label>
                                <input type="file" class="form-control form-control-lg" placeholder="Image" name="image">
                                <img class="w-50 my-3" src="{{ asset('uploads/products/' . $products->image) }}">
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-lg btn-secondary">Update</button>
                            </div>
                        </div>
                    </form>
                    <div class="card border-0 shadow-lg my-4">
                                <div class="card-header bg-dark">
                                    <h3 class="text-white">Product Options</h3>
                                </div>
                                <div class="card-body">
                                    @if ($options->isNotEmpty())
                                        <h5>Options:</h5>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Values</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($options as $option)
                                                    <tr>
                                                        <td>{{ $option->name }}</td>
                                                        <td>{{ $option->type }}</td>
                                                        <td>
                                                            <ul>
                                                                @foreach ($option->values as $value)
                                                                    <li>{{ $value->value }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <!-- Edit Option Form -->
                                                            <form action="{{ route('options.update', $option->id) }}" method="post" class="d-inline">
                                                                @csrf
                                                                @method('put')
                                                                <input type="hidden" name="product_id" value="{{ $products->id }}">
                                                                <button type="button" class="btn btn-warning btn-sm" onclick="showEditForm({{ $option->id }}, '{{ $option->name }}', '{{ $option->type }}', {{ json_encode($option->values->pluck('value')) }})">Edit</button>
                                                            </form>

                                                            <!-- Delete Option Form -->
                                                            <form action="{{ route('options.destroy', $option->id) }}" method="post" class="d-inline">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No options found for this product.</p>
                                    @endif

                                    <!-- Add New Option Form -->
                                    <div class="mt-3">
                                        <h5>Add New Option:</h5>
                                        <form action="{{ route('options.create', $products->id) }}" method="get">
                                            @csrf
                                          <input type="hidden" name="product_id" value="{{ $products->id }}">
                   
                                             <button type="submit" class="btn btn-lg btn-secondary">Add Option</button>
                                       </form>
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Option Modal -->
    <div class="modal fade" id="editOptionModal" tabindex="-1" role="dialog" aria-labelledby="editOptionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editOptionForm" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOptionModalLabel">Edit Option</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="optionName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="optionName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="optionType" class="form-label">Type</label>
                            <input type="text" class="form-control" id="optionType" name="type">
                        </div>
                        <div class="mb-3">
                            <label for="optionValues" class="form-label">Values</label>
                            <textarea class="form-control" id="optionValues" name="values" rows="3"></textarea>
                            <small class="form-text text-muted">Separate values with commas.</small>
                        </div>
                        <input type="hidden" name="product_id" value="{{ $products->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(optionId, name, type, values) {
            document.getElementById('editOptionForm').action = `/options/${optionId}`;
            document.getElementById('optionName').value = name;
            document.getElementById('optionType').value = type;
            document.getElementById('optionValues').value = values.join(',');
            $('#editOptionModal').modal('show');
        }
    
    </script>
        <script>
      function addOptionInput(button) {
    var optionInputs = document.getElementById('optionInputs');
    var newInput = document.createElement('div');
    newInput.classList.add('mb-3', 'option-input');
    newInput.innerHTML = `
        <label for="name" class="form-label h5">Option Value</label>
        <div class="input-group">
            <input type="text" class="form-control form-control-lg" placeholder="Option Value" name="value[]">
            <button type="button" class="btn btn-primary" onclick="addOptionInput(this)">Add Option</button>
        </div>
    `;
    optionInputs.appendChild(newInput);
    button.remove(); // Remove the button after adding the input field
}

        function toggleStatus(checkbox) {
            var statusLabel = document.getElementById('statusLabel');
            var statusHiddenInput = document.getElementById('statusHiddenInput');
            
            if (checkbox.checked) {
                statusLabel.innerText = 'Enabled';
                statusHiddenInput.value = '1';
            } else {
                statusLabel.innerText = 'Disabled';
                statusHiddenInput.value = '0';
            }
        }
    </script>
@endsection
