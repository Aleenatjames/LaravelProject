<!doctype html>
@extends('layouts.app')

@section('page-title', 'Orders')

@section('content')
    
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
                        <h3 class="text-white">Shipping Management</h3>
                    </div>
                    @if(Session::has('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success">
                {{Session::get('success')}}
                </div>
            </div>
            @endif
                    <form enctype="multipart/form-data" action="{{ route('shipping.store') }}" method="post" name="shippingForm" id="shippingForm">
                        @csrf
                      
                        <div class="card-body">
                        
                            <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-3">
                            
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select a Country</option>
                                    @if($countries->isNotEmpty())
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                    <option value="rest_of_world">Rest of the World</option>
                                    @endif
                                </select>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" name="amount" class="form-control" placeholder="Amount">
                                </div>
                                <div class="col-sm-3">
                    <select name="options" id="options" class="form-control">
                        <option value="">Select Shipping Option</option>
                        <option value="normal">Normal</option>
                        <option value="express">Express</option>
                        <option value="free">Free</option>
                    </select>
                </div>
                            <div class="col-sm-3">
                                <button class="btn btn-lg btn-secondary">Submit</button>
                            </div>
                        </div>
                        </div>
                    </form><br><br>

                    @if($shippingCharges->isNotEmpty())
    <table class="table" id="shipping-charges-table">
        <thead>
            <tr>
                <th>Country</th>
                <th>Amount</th>
                <th>Options</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shippingCharges as $charge)
                <tr>
                    <td>{{ $charge->country->name ?? 'Rest of the World' }}</td>
                    <td>{{ $charge->amount }}</td>
                    <td>{{ $charge->options }}</td>
                    <td>
                    <button class="btn btn-secondary" onclick="editShippingCharge({{ $charge->id }})">Edit</button>
                     <button class="btn btn-dark" onclick="deleteShippingCharge({{ $charge->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No shipping charges found.</p>
@endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function submitShippingForm() {
        var formData = new FormData($('#shippingForm')[0]);

        $.ajax({
            url: "{{ route('shipping.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    // Append the new shipping charge to the table
                    $('#shipping-charges-table tbody').append(
                        '<tr>' +
                        '<td>' + (response.charge.country ? response.charge.country.name : 'Rest of the World') + '</td>' +
                        '<td>' + response.charge.amount + '</td>' +
                        '<td>' + response.charge.options + '</td>' +
                        '<td></td>' +  // Add actions if needed
                        '</tr>'
                    );
                    // Clear the form
                    $('#shippingForm')[0].reset();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(response) {
                alert('Error: ' + response.responseJSON.message);
            }
        });
    }
    function editShippingCharge(id) {
    $.ajax({
        url: "{{ route('shipping.edit', '') }}/" + id,
        type: "GET",
        success: function(response) {
            if (response.success) {
                $('select[name=country]').val(response.shippingCharge.country_id);
                $('input[name=amount]').val(response.shippingCharge.amount);
                $('select[name=options]').val(response.shippingCharge.options);

                // Remove existing hidden inputs if any
                $('input[name="_method"]').remove();
                $('input[name="id"]').remove();

                // Append hidden inputs for method and id
                $('#shippingForm').append('<input type="hidden" name="_method" value="PUT">');
                $('#shippingForm').append('<input type="hidden" name="id" value="' + id + '">');

                $('#shippingForm').attr('action', "{{ route('shipping.update', '') }}/" + id);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(response) {
            alert('Error: ' + response.responseJSON.message);
        }
    });
}


        function deleteShippingCharge(id) {
            if(confirm('Are you sure you want to delete this shipping charge?')) {
                $.ajax({
                    url: "{{ route('shipping.destroy', '') }}/" + id,
                    type: "POST",
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#shipping-charge-' + id).remove();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            }
        }
</script>

@endsection