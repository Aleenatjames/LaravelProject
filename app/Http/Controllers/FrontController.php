<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\GiftCard;
use App\Models\Option;
use App\Models\Orders;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\ProductRating;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FrontController extends Controller
{
    public function index(){    
        $product = DB::table('product')->orderBy('created_at','asc')->take(5)->get();
        $new = DB::table('product')->orderBy('created_at','desc')->take(5)->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $customer = Auth::guard('customer')->user();
        
        return view('front.home', [
            'product' => $product,
            'new' => $new,
            'categories'=>$categories,
            'customer' => $customer,
        ]);
    }
    public function detail($id){
        $product = Product::findOrFail($id);
        $customer = Auth::guard('customer')->user();
   return view('front.details',[
   'products' =>$product,
   'customer'=>$customer
   ]);
    }
    public function update(){
        $product = DB::table('product')->orderBy('created_at','asc')->take(5)->get();
        $new = DB::table('product')->orderBy('created_at','desc')->take(5)->get();
        $customer = Auth::guard('customer')->user();
        
        return view('front.home', [
            'product' => $product,
            'new' => $new,
            'customer' => $customer,
        ]);
    }
    public function addToCart(Request $request, $id)
    {
        // Ensure user is authenticated
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login')->with('error', 'You must be logged in to add items to the cart');
        }

        $customerId = Auth::guard('customer')->id();

        // Find the product by ID
        $product = Product::findOrFail($id);

        // Check if the product already exists in the cart for the current customer
        $cartItem = Cart::where('customer_id', $customerId)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            // If the item already exists, increment the quantity
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // If the item does not exist, create a new cart item
            Cart::create([
                'customer_id' => $customerId,
                'product_id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'quantity' => 1, // Start with quantity 1
            ]);
        }

        // Fetch all cart items for the current customer
        $cartItems = Cart::where('customer_id', $customerId)->get();

        // Redirect to the cart page with success message
        return redirect()->route('front.cart')->with('success', 'Product added to cart successfully');
    }
   public function updatedetail($id)
{
   $products = Product:: withCount('product_ratings')
                        ->withSum('product_ratings','rating')
                        ->with('product_ratings')->find($id);
     $category = $products->category;
     $options = Option::where('product_id', $id)->get();
     
     $additionalImages = [];
     $directory = public_path('uploads/products/' . $category);
 
     if (is_dir($directory)) {
         $files = glob($directory . '/*.*');
         foreach ($files as $file) {
             $additionalImages[] = 'uploads/products/' . $category . '/' . basename($file);
         }
     }
      $avgRating ='0.00';
      $avgRatingPer=0;
      if($products->product_ratings_count>0){
     $avgRating=number_format(($products->product_ratings_sum_rating/$products->product_ratings_count),2);     
     $avgRatingPer=($avgRating*100)/5; 
    }
      
   $customer = Auth::guard('customer')->user();
   
   return view('front.details', [
      'products' => $products,
      'customer'=>$customer,
      'avgRating'=>$avgRating,
      'avgRatingPer'=>$avgRatingPer,
      'additionalImages'=>$additionalImages,
      'options' => $options,
  ]);
       
}
    
   // FrontController.php

   public function addTo(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $customer = Auth::guard('customer')->user();

    if (!$customer) {
        return redirect()->route('customer.login')->with('error', 'You must be logged in to add items to the cart');
    }

    $customerId = $customer->id;

    $selectedOptions = [
        'size' => $request->input('selectedSize'),
        'color' => $request->input('selectedColor'),
        'flavor' => $request->input('selectedFlavor'),
    ];

    // Fetch the price from the product_option_values table based on selected options
    $totalPrice = $product->price;

    // Fetch all options for the product
    $options = Option::where('product_id', $product->id)->get();

    foreach ($options as $option) {
        foreach ($selectedOptions as $option->type => $optionValue) {
            if (!empty($optionValue) && $option->type === $option->type) {
                $optionValueModel = ProductOptionValue::where('option_id', $option->id)
                                                      ->where('value', $optionValue)
                                                      ->first();

                if ($optionValueModel) {
                    // Add the price of the selected option to the total price
                    $totalPrice += $optionValueModel->price;
                }
            }
        }
    }
    // Serialize the options array into a JSON string
    $serializedOptions = json_encode($selectedOptions);

    Log::info('Selected Options: ' . $serializedOptions);

    // Check if the cart item with the same product and options exists
    $cartItem = Cart::where('customer_id', $customerId)
                    ->where('product_id', $product->id)
                    ->where('option', $serializedOptions)
                    ->first();

    $productType = $product->name === 'Gift Card' ? 'gift_card' : 'regular';

    if ($cartItem) {
        $cartItem->quantity += 1;
        $cartItem->save();
    } else {
        Cart::create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'price' => $totalPrice, // Use the fetched price
            'quantity' => 1,
            'product_type' => $productType,
            'option' => $serializedOptions,
        ]);
    }

    $cartItems = Cart::where('customer_id', $customerId)->get();
    $giftCard = $cartItems->firstWhere('gift_card_id', '!=', null);
    // Pass cart items to the cart view and show success message
    return view('front.cart', compact('cartItems', 'customer','giftCard'))->with('success', 'Product added to cart successfully');
}

   
public function cart()
{
    $customerId = Auth::guard('customer')->id(); // Get the authenticated customer's ID
    $cartItems = Cart::where('customer_id', $customerId)->get();
    $customer = Auth::guard('customer')->user();
    $giftCard = $cartItems->firstWhere('gift_card_id', '!=', null);
   
    return view('front.cart', compact('cartItems','customer','giftCard'));
}
         
public function removeFromCart($id)
{
    // Fetch the cart item based on its ID
    $cartItem = Cart::findOrFail($id);

    // Ensure that the cart item belongs to the authenticated customer
    $customerId = Auth::guard('customer')->id();
    if ($cartItem->customer_id !== $customerId) {
        // If the cart item doesn't belong to the authenticated customer, handle unauthorized access
        return redirect()->route('front.cart')->with('error', 'Unauthorized access');
    }

    // Delete the cart item
    $cartItem->delete();

    // Redirect back to the cart view after removing the item
    return redirect()->route('front.cart')->with('success', 'Item removed from cart successfully');
}
public function success(){
    $customerId = Auth::guard('customer')->id();
 
    Cart::where('customer_id', $customerId)->delete();
   
    return view('front.success');
}
public function account()
{
    $customerId = Auth::guard('customer')->id();
    $customer = Customer::with('address')->find($customerId);
    $orders = Orders::where('customer_id', $customerId)->get();
    $customerAddress = '';

    if ($customer->address) {
        $customerAddress = $customer->address->address1 . ', ' . $customer->address->address2 . ', ' . $customer->address->city . ', ' . $customer->address->state . ', ' . $customer->address->country->name;
    }

    return view('front.account', compact('customerAddress', 'customer', 'orders'));
}

public function list(){
    $product = DB::table('product')->take(5)->get();
    return view('front.list',[
        'product' => $product,
    ]);
}

public function saveRating($id, Request $request)
{
    try {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
            'rating' => 'required|integer|min:1|max:5'
        ]);
$count=ProductRating::where('email',$request->email)->count();
if($count > 0){
   
    session()->flash('error','You already rated this product');
    return response()->json([
        'status' => true,
       
]);
}
        // Create a new ProductRating instance and save the data
        $productRating = new ProductRating();
        $productRating->product_id = $id;
        $productRating->name = $request->name;
        $productRating->email = $request->email;
        $productRating->comment = $request->comment;
        $productRating->rating = $request->rating;
        $productRating->save();

        // Flash success message to session
        session()->flash('success', 'Thank you for rating');

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Thank you for rating'
        ]);
      //  return redirect()->route('front.addtocart')->with('success', 'Thank you for your rating');
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error('Error saving rating: ' . $e->getMessage());

        // Return error response
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while saving your rating.'
        ], 500);

    }

}

}