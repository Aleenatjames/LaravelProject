@extends('layouts.app')

@section('page-title', 'Add Option')

@section('content')
    <div class="bg-dark py-3">
        <h3 class="text-white text-center">Add Option</h3>
    </div>
    @if(Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                {{Session::get('success')}}
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
                    <h3 class="text-white">Add Option</h3>
                </div>
                <form action="{{ route('options.store', ['productId' => $productId]) }}" method="post">
    @csrf
    <input type="hidden" name="product_id" value="{{ $productId }}">
    <div class="card-body">
        <div class="mb-3">
            <label for="type" class="form-label h5">Type</label>
            <select class="form-control form-control-lg" name="type" id="type">
                <option value="radio">Radio</option>
                <option value="text">Text</option>
                <option value="dropdown">Dropdown</option>
                <option value="checkbox">Checkbox</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label h5">Option Title</label>
            <input type="text" class="form-control form-control-lg" placeholder="Option Title" name="name">
        </div>
        <div id="optionInputs">
            <div class="mb-3 option-input">
                <label for="name" class="form-label h5">Option Value</label>
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Option Value" name="value[]">
                    <input type="number" class="form-control form-control-lg" placeholder="Price" name="price[]">
                    <input type="number" class="form-control form-control-lg" placeholder="Quantity" name="quantity[]">
                    <select class="form-control form-control-lg" name="product_type[]" id="product_type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="addOptionInput(this)">Add Option</button>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label h5">Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="statusToggle" name="status" value="1" checked onchange="toggleStatus(this)">
                <label class="form-check-label" for="statusToggle" id="statusLabel">Enabled</label>
                <input type="hidden" id="statusHiddenInput" name="status" value="1"> <!-- Hidden input to store status -->
            </div>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-lg btn-secondary">Add Option</button>
        </div>
    </div>
</form>

            </div>
        </div>
    </div>
</div>
    <script>
      function addOptionInput(button) {
    var optionInputs = document.getElementById('optionInputs');
    var newInput = document.createElement('div');
    newInput.classList.add('mb-3', 'option-input');
    newInput.innerHTML = `
        <label for="name" class="form-label h5">Option Value</label>
        <div class="input-group">
            <input type="text" class="form-control form-control-lg" placeholder="Option Value" name="value[]">
            <input type="number" class="form-control form-control-lg" placeholder="Price" name="price[]">
            <input type="number" class="form-control form-control-lg" placeholder="Quantity" name="quantity[]">
            <select class="form-control form-control-lg" name="product_type[]" id="product_type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>            
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
