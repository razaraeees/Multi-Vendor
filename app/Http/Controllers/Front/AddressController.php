<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\DeliveryAddress;
use App\Models\Country;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ShippingCharge;

class AddressController extends Controller
{

    // MISSING METHOD - Add this method to your controller
    public function getDeliveryAddress(Request $request)
    {
        try {
            $addresses = [];
            
            // User logged in hai?
            if (Auth::check()) {
                $addresses = Auth::user()->addresses()->get();
            } else {
                // Session based addresses
                $sessionId = session()->getId();
                $addresses = DeliveryAddress::where('session_id', $sessionId)->get();
            }
            
            return response()->json([
                'type' => 'success',
                'addresses' => $addresses->map(function($address) {
                    return [
                        'id' => $address->id,
                        'name' => $address->name,
                        'address' => $address->address,
                        'city' => $address->city,
                        'state' => $address->state,
                        'country' => $address->country,
                        'pincode' => $address->pincode,
                        'mobile' => $address->mobile,
                        'shipping_charges' => $address->shipping_charges ?? 0,
                        'codpincodeCount' => $address->cod_available ?? 1,
                        'prepaidpincodeCount' => $address->prepaid_available ?? 1,
                        'is_free_shipping' => $address->is_free_shipping ?? false
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to load addresses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveDeliveryAddress(Request $request)
    {
        if ($request->ajax()) {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'delivery_name'    => 'required|string|max:100',
                'delivery_address' => 'required|string|max:100',
                'delivery_city'    => 'required|string|max:100',
                'delivery_state'   => 'required|string|max:100',
                'delivery_country' => 'required|string|max:100',
                'delivery_pincode' => 'required',
                'delivery_mobile'  => 'required|numeric'
            ]);

            if ($validator->passes()) {
                
                $data = $request->all();

                $address = [];
                $address['user_id'] = Auth::check() ? Auth::user()->id : null;
                $address['session_id'] = !Auth::check() ? Session::getId() : null;
                $address['name']    = $data['delivery_name'];
                $address['address'] = $data['delivery_address'];
                $address['city']    = $data['delivery_city'];
                $address['state']   = $data['delivery_state'];
                $address['country'] = $data['delivery_country'];
                $address['pincode'] = $data['delivery_pincode'];
                $address['mobile']  = $data['delivery_mobile'];

                try {
                    if (!empty($data['delivery_id'])) {
                        // Update existing address
                        DeliveryAddress::where('id', $data['delivery_id'])->update($address);
                        $message = 'Address updated successfully!';
                    } else {
                        // Create new address
                        DeliveryAddress::create($address);
                        $message = 'Address added successfully!';
                    }

                    // Get cart total for shipping calculation
                    $cartTotal = $this->getCartTotal();

                    // Get updated addresses list with conditional shipping charges
                    $deliveryAddresses = Auth::check()
                        ? DeliveryAddress::where('user_id', Auth::user()->id)->get()
                        : DeliveryAddress::where('session_id', Session::getId())->get();

                    // Get dynamic shipping configuration
                    $shippingConfig = $this->getShippingConfiguration();

                    // Add conditional shipping charges to each address
                    foreach ($deliveryAddresses as $address) {
                        $address->shipping_charges = $this->calculateConditionalShipping($cartTotal, $address->pincode);
                        $address->codpincodeCount = $this->checkCODAvailability($address->pincode);
                        $address->prepaidpincodeCount = $this->checkPrepaidAvailability($address->pincode);
                        $address->is_free_shipping = $cartTotal >= $shippingConfig['free_shipping_min_amount'];
                        $address->cart_total = $cartTotal;
                        $address->free_shipping_threshold = $shippingConfig['free_shipping_min_amount'];
                    }

                    return response()->json([
                        'type' => 'success',
                        'message' => $message,
                        'addresses' => $deliveryAddresses->toArray(), // Add this line
                        'cart_total' => $cartTotal,
                        'free_shipping_threshold' => $shippingConfig['free_shipping_min_amount'],
                        'view' => (string) \Illuminate\Support\Facades\View::make('front.products.checkout_delivery')
                            ->with(compact('deliveryAddresses'))
                    ]);

                } catch (\Exception $e) {
                    Log::error('Error saving delivery address: ' . $e->getMessage());
                    return response()->json([
                        'type' => 'error',
                        'message' => 'Error saving address. Please try again.'
                    ]);
                }

            } else {
                return response()->json([
                    'type' => 'error',
                    'errors' => $validator->messages()
                ]);
            }
        }
    }

    // Get current cart total
    private function getCartTotal()
    {
        $total = 0;
        
        if (Auth::check()) {
            // For logged in users
            $userCartItems = Cart::userCartItems();
        } else {
            // For guest users (session-based cart)
            $userCartItems = Cart::where('session_id', Session::getId())->get();
        }

        foreach ($userCartItems as $item) {
            $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
            $total += ($getDiscountAttributePrice['final_price'] * $item['quantity']);
        }

        // Subtract any applied coupon
        $couponAmount = Session::get('couponAmount', 0);
        $total = $total - $couponAmount;

        return $total;
    }

    // Get dynamic shipping configuration from database
    private function getShippingConfiguration()
    {
        $shippingConfig = ShippingCharge::first();
        
        if ($shippingConfig) {
            return [
                'shipping_charge' => $shippingConfig->shipping_charge,
                'free_shipping_min_amount' => $shippingConfig->free_shipping_min_amount
            ];
        }
        
        // Default fallback values if no configuration found
        return [
            'shipping_charge' => 50,
            'free_shipping_min_amount' => 500
        ];
    }

    // Calculate conditional shipping charges
    private function calculateConditionalShipping($cartTotal, $pincode = null)
    {
        $shippingConfig = $this->getShippingConfiguration();
        
        // Free shipping if cart total is above threshold
        if ($cartTotal >= $shippingConfig['free_shipping_min_amount']) {
            return 0;
        }

        // You can add pincode-based logic here if needed
        return $shippingConfig['shipping_charge'];
    }

    // Check COD availability
    private function checkCODAvailability($pincode)
    {
        return 1; // Available
    }

    // Check prepaid availability
    private function checkPrepaidAvailability($pincode)
    {
        return 1; // Available
    }

    // Get delivery address with shipping calculation (for checkout page)
    public function getDeliveryAddressWithShipping(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            
            $deliveryAddress = DeliveryAddress::where('id', $data['addressid'])->first();
            
            if ($deliveryAddress) {
                $cartTotal = $this->getCartTotal();
                $shippingCharges = $this->calculateConditionalShipping($cartTotal);
                $shippingConfig = $this->getShippingConfiguration();
                
                $deliveryAddress->shipping_charges = $shippingCharges;
                $deliveryAddress->cart_total = $cartTotal;
                $deliveryAddress->is_free_shipping = $cartTotal >= $shippingConfig['free_shipping_min_amount'];
                
                return response()->json([
                    'address' => $deliveryAddress->toArray(),
                    'shipping_info' => [
                        'cart_total' => $cartTotal,
                        'shipping_charges' => $shippingCharges,
                        'free_shipping_threshold' => $shippingConfig['free_shipping_min_amount'],
                        'is_free_shipping' => $cartTotal >= $shippingConfig['free_shipping_min_amount'],
                        'amount_needed_for_free_shipping' => max(0, $shippingConfig['free_shipping_min_amount'] - $cartTotal)
                    ]
                ]);
            }

            return response()->json(['error' => 'Address not found'], 404);
        }
    }

    // Remove Delivery Address (updated with shipping recalculation)
    public function removeDeliveryAddress(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            try {
                // DELETE the delivery address
                DeliveryAddress::where('id', $data['addressid'])->delete();

                // Get cart total for shipping calculation
                $cartTotal = $this->getCartTotal();

                // Get updated addresses list
                $deliveryAddresses = Auth::check()
                    ? DeliveryAddress::where('user_id', Auth::user()->id)->get()
                    : DeliveryAddress::where('session_id', Session::getId())->get();

                // Get dynamic shipping configuration
                $shippingConfig = $this->getShippingConfiguration();

                // Add conditional shipping charges to each address
                foreach ($deliveryAddresses as $address) {
                    $address->shipping_charges = $this->calculateConditionalShipping($cartTotal, $address->pincode);
                    $address->codpincodeCount = $this->checkCODAvailability($address->pincode);
                    $address->prepaidpincodeCount = $this->checkPrepaidAvailability($address->pincode);
                    $address->is_free_shipping = $cartTotal >= $shippingConfig['free_shipping_min_amount'];
                    $address->cart_total = $cartTotal;
                    $address->free_shipping_threshold = $shippingConfig['free_shipping_min_amount'];
                }

                return response()->json([
                    'type' => 'success',
                    'message' => 'Address removed successfully!',
                    'addresses' => $deliveryAddresses->toArray(), // Add this line
                    'cart_total' => $cartTotal,
                    'view' => (string) \Illuminate\Support\Facades\View::make('front.products.checkout_delivery')
                        ->with(compact('deliveryAddresses'))
                ]);

            } catch (\Exception $e) {
                Log::error('Error removing delivery address: ' . $e->getMessage());
                return response()->json([
                    'type' => 'error',
                    'message' => 'Error removing address. Please try again.'
                ]);
            }
        }
    }

    // Get shipping info for AJAX calls
    public function getShippingInfo(Request $request)
    {
        try {
            $cartTotal = $this->getCartTotal();
            $shippingCharges = $this->calculateConditionalShipping($cartTotal);
            $shippingConfig = $this->getShippingConfiguration();
            
            return response()->json([
                'cart_total' => $cartTotal,
                'shipping_charges' => $shippingCharges,
                'free_shipping_threshold' => $shippingConfig['free_shipping_min_amount'],
                'is_free_shipping' => $cartTotal >= $shippingConfig['free_shipping_min_amount'],
                'amount_needed_for_free_shipping' => max(0, $shippingConfig['free_shipping_min_amount'] - $cartTotal),
                'savings_message' => $cartTotal >= $shippingConfig['free_shipping_min_amount'] 
                    ? 'You are getting FREE shipping!' 
                    : 'Add $' . ($shippingConfig['free_shipping_min_amount'] - $cartTotal) . ' more for FREE shipping!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting shipping info: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error getting shipping info',
                'cart_total' => 0,
                'shipping_charges' => 0
            ]);
        }
    }
}