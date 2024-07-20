<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Option;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Product;
use App\Models\ProductOptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

class CustomerOrderController extends Controller
{
    public function selectedCustomer($customerId)
{
    Log::info('Selected customer ID: ' . $customerId);
    $customers = Customer::with('address.country')->findOrFail($customerId);
    
    Log::info('Customer details: ', $customers->toArray());
    $cartItems = Cart::where('customer_id', $customerId)
    ->where('generated_by', 'admin')
    ->get();
    $products = Product::all();
    $options = Option::all()->keyBy('id');
    $productOptionValues = ProductOptionValue::all()->groupBy('option_id');
    return view('admin.orders.selectedCustomer', [
        'customers' => $customers,
        'products' => $products,
        'cartItems' => $cartItems,
        'options' => $options,
        'productOptionValues' => $productOptionValues,
    ]);
}
public function addToCart(Request $request)
{
    $customerId = $request->input('customer_id');
    $selectedProducts = $request->input('products');
    $customizations = $request->input('customizations');

    Log::info('Received customizations:', [$customizations]);

    foreach ($selectedProducts as $productId) {
        // Fetch the product details
        $product = Product::findOrFail($productId);

        // Prepare customization options
        $options = isset($customizations[$productId]) ? json_decode($customizations[$productId], true) : [];

        // Add to cart with customization options
        Cart::create([
            'customer_id' => $customerId,
            'product_id' => $productId,
            'name' => $product->name,
            'image' => $product->image,
            'price' => $product->price,
            'quantity' => 1, // Default to 1; you can modify this if needed
            'option' => json_encode($options), // Encode customization options as JSON
            'generated_by' => 'admin',
        ]);
    }

    return response()->json(['success' => true]);
}



public function store($customerId, Request $request)
{
    Log::info("TOTAL AMOUNT: " . $request->input('total'));
    Log::info("STATUS: " . $request->input('status'));
    Log::info("TOTAL QUANTITY: " . $request->input('total_quantity'));
    Log::info("DELIVERY DATE: " . $request->input('delivery_date'));

    $customers = Customer::with('address.country')->findOrFail($customerId);

    $request->validate([
        'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled', // Define your status options for Orders
    ]);
    $fullAddress = $customers->address->address1 . ', ' . 
    ($customers->address->address2 ? $customers->address->address2 . ', ' : '') . 
    $customers->address->city . ', ' . 
    $customers->address->state . ' ' . 
    $customers->address->zip . ', ' . 
    $customers->address->country->name;

    $order = Orders::create([
        'customer_id' => $customerId,
        'customer_name' => $customers->name,
        'customer_address' => $fullAddress,
        'quantity' => $request->input('total_quantity'),
        'total_amount' => $request->input('total'),
        'status' => $request->input('status'),
        'gift_cards_used' => 'not_used',
        'shipping_option'=>$request->input('shipping_option'),
        'delivery_date' => $request->input('delivery_date'),
        'generated_by' => 'admin',
    ]);

    $cartItems = Cart::where('customer_id', $customerId)->get()->map(function ($item) {
        $item->generated_by = 'admin'; // Or the logic to determine who generated it
        return $item;
    });

    foreach ($cartItems as $cartItem) {
        $product = Product::find($cartItem->product_id);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'qty' => $cartItem->quantity,
            'price' => $product->price,
        ]);
    }

    Cart::where('customer_id', $customerId)->delete();

    return redirect()->back();
}


}
