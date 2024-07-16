<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function create(){
        $countries=Country::get();
        $data['countries']=$countries;
        $shippingCharges = Shipping::with('country')->get();
        return view('admin.shipping.create',$data,[
            'shippingCharges' => $shippingCharges,
        ]);
    }
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'country' => 'required|string',
            'amount' => 'required|numeric',
            'options' => 'required|string|in:normal,express,free', // Validate the options field
        ]);
    
        // Create a new shipping charge record
        $shippingCharge = new Shipping();
        $shippingCharge->country_id = $request->country;
        $shippingCharge->amount = $request->amount;
        $shippingCharge->options = $request->options; // Save the selected option
        $shippingCharge->save();
    
        // Redirect back with a success message
        return redirect()->route('shipping.create')->with('success', 'Shipping charge added successfully.');
    }
    public function edit($id)
    {
        $shippingCharge = Shipping::find($id);
    
        if ($shippingCharge) {
            return response()->json([
                'success' => true,
                'shippingCharge' => $shippingCharge,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Shipping charge not found.',
            ], 404);
        }
    }
public function update(Request $request, $id)
{
    $request->validate([
        'country' => 'required|string',
        'amount' => 'required|numeric',
        'options' => 'required|string|in:normal,express,free',
    ]);

    $shippingCharge = Shipping::findOrFail($id);
    $shippingCharge->country_id = $request->country;
    $shippingCharge->amount = $request->amount;
    $shippingCharge->options = $request->options;
    $shippingCharge->save();

    return response()->json(['success' => true, 'message' => 'Shipping charge updated successfully.', 'charge' => $shippingCharge]);
}

public function destroy($id)
{
    $shippingCharge = Shipping::findOrFail($id);
    $shippingCharge->delete();

    return response()->json(['success' => true, 'message' => 'Shipping charge deleted successfully.']);
}

    
}
