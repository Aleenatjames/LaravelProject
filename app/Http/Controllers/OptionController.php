<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\ProductOptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OptionController extends Controller
{
    public function create($productId)
    {
        return view('product.options', ['productId' => $productId]);
    }
    public function store(Request $request)
    {
        Log::debug('Received request to store option:', ['request' => $request->all()]);
        
        // Validate incoming data
        $validateData = $request->validate([
            'type' => 'required|in:radio,text,dropdown,checkbox',
            'name' => 'required', // Option title
            'value.*' => 'required', // Array of option values
            'price.*' => 'nullable|numeric',
            'quantity.*' => 'nullable|integer',
            'product_type.*' => 'nullable',
            'status' => 'required|boolean',
            'product_id' => 'required|exists:product,id', // Validate product_id exists in products table
        ]);
    
        // Create a new option
        $option = new Option();
        $option->product_id = $validateData['product_id'];
        $option->name = $validateData['name'];
        $option->type = $validateData['type'];
        $option->status = $validateData['status'];
        $option->save();
    
        // Save option values
        $values = $validateData['value'];
        $prices = $validateData['price'];
        $quantities = $validateData['quantity'];
        $product_types = $validateData['product_type'];
    
        foreach ($values as $index => $value) {
            $optionValue = new ProductOptionValue();
            $optionValue->option_id = $option->id;
            $optionValue->value = $value;
            $optionValue->price = $prices[$index] ?? null;
            $optionValue->quantity = $quantities[$index] ?? null;
            $optionValue->product_type = $product_types[$index] ?? null;
            $optionValue->save();
        }
    
        return redirect()->route('options.create', ['productId' => $validateData['product_id']])
                         ->with('success', 'Option added successfully');
    }
    
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:radio,text,dropdown,checkbox',
            'values' => 'required|string',
        ]);

        // Find the option and update its details
        $option = Option::findOrFail($id);
        $option->name = $validated['name'];
        $option->type = $validated['type'];
        $option->save();

        // Update option values
        $values = explode(',', $validated['values']);
        ProductOptionValue::where('option_id', $option->id)->delete();
        foreach ($values as $value) {
            ProductOptionValue::create([
                'option_id' => $option->id,
                'value' => trim($value),
            ]);
        }

        return redirect()->route('product.edit', $request->product_id)->with('success', 'Option updated successfully');
    }

    public function destroy($id)
{
    // Find and delete the option
    $option = Option::findOrFail($id);

    // Delete associated option values
    $option->values()->delete();

    // Delete the option itself
    $option->delete();

    return redirect()->back()->with('success', 'Option deleted successfully');
}

    
}
