<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Country;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.login');
    }

    public function authenticate(Request $request)
    {
     
       
        $validateData = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ]);


        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = config('services.recaptcha.v3.secret_key');
    
        Log::info('reCAPTCHA Response: ' . $recaptchaResponse);
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip()
        ]);
    
        $result = $response->json();
    
        if (!$result['success'] || $result['score'] < 0.5) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed, please try again.']);
        }

        Log::info('reCAPTCHA verification successful.');
        if ($validateData) {
           if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
              
                return redirect()->route('front.home');
            } else {
                return redirect()->route('customer.login')->with('error', 'Either useremail or password is incorrect');
            }
        } else {
            return redirect()->route('customer.login')->withInput()->with('error', 'Either useremail or password is incorrect');
        }
    }
    
   // CustomerController.php

public function login(Request $request){
    if (Auth::check()) {
        return redirect()->route('front.home');
    }
    
    $data = $request->input(); 
    $request->session()->put('email', $data['email']);
    
    return view('customer.login');
}

public function logout()
{
    Auth::logout();
    session()->forget('email');

    return redirect()->route('customer.login');
}


    public function register()
    {
        return view('customer.register');
    }

    public function processRegister(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed',
            'g-recaptcha-response'=>'recaptcha',
        ]);

        if ($validateData) {
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->password = Hash::make($request->password); // Fix typo in $request->password
            
            $customer->save();

            return redirect()->route('customer.login')->with('success', 'You have registered successfully');
        } else {
            return redirect()->route('customer.register')->withInput()->withErrors($validateData);
        }
    }
    public function changePassword(Request $request)
{
    // Validate the input
    $validateData=$request->validate([
        'current_password' => 'required',
        'password' => 'required|confirmed',
    ]);

    // Get the authenticated customer
    $customer = auth()->guard('customer')->user();

    // Check if the current password matches
    if (!Hash::check($request->current_password, $customer->password)) {
        return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
    }
   
    // Update the customer's password
    $customer->password = Hash::make($request->password); // Fix typo in $request->password
            // $customer->save();
            

    // Redirect with success message
    return redirect()->route('front.account')->with('success', 'Password changed successfully.');
}
public function orderDetails($customerId)
{
    $orders = Orders::with('orderItems.product')
                    ->where('customer_id', $customerId)
                    ->get();
   
    return view('front.orders', compact('orders'));
}


public function showOrders($orderId)
{
    // Fetch the order by ID and ensure it belongs to the logged-in customer
    $order = Orders::findOrFail($orderId);

    return view('front.orders', compact('order'));
}
public function create()
{
    $countries = Country::all(); // Retrieve all countries for the dropdown
    return view('admin.customer.create', compact('countries'));
}
public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer,email',
            'mobile_number' => 'required|string|max:15',
            'country_id' => 'required|exists:countries,id',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
        ]);

        // Create the customer
        $customer = Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        // Create the address
        $address = Address::create([
            'customer_id' => $customer->id,
            'name' => $request->input('name'),
            'country_id' => $request->input('country_id'),
            'address1' => $request->input('address1'),
            'address2' => $request->input('address2'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'pincode' => $request->input('pincode'),
            'mobileno' => $request->input('mobile_number'),
        ]);

        // Redirect with a success message
        return back()->with('success', 'Customer created successfully');
    }
    public function select()
{
    $customers = Customer::with('address.country')->get();
    return view('admin.customer.select', compact('customers'));
}



}
