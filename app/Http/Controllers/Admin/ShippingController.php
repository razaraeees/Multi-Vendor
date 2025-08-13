<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\ShippingCharge;

class ShippingController extends Controller
{
    // We got two Shipping Charges modules: Simple one (every country has its own shipping rate (price/cost/charges) based on the Delivery Address in the Checkout page) and Advanced one (every country has its own shipping rate based on the Delivery Address in the Checkout page, plus, other charges are calculated based on shipment weight). We created the `shipping_charges` database table for that matter. Also, the Shipping Charge module will be available in the Admin Panel for 'admin'-s only, not for 'vendor'-s
    

    // Render the Shipping Charges page (admin/shipping/shipping_charges.blade.php) in the Admin Panel for 'admin'-s only, not for vendors    
    public function shippingCharges() {
        // Highlight the 'Shipping Charges' module in the Sidebar on the left in the Admin Panel. Correcting issues in the Skydash Admin Panel Sidebar using Session
        Session::put('page', 'shipping');

        $shippingCharges = ShippingCharge::get()->toArray();

        return view('admin.shipping.shipping_charges')->with(compact('shippingCharges'));
    }

    // Update Shipping Status (active/inactive) via AJAX in admin/shipping/shipping_charages.blade.php, check admin/js/custom.js    
    public function updateShippingStatus(Request $request) {
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)
            // dd($data);

            if ($data['status'] == 'Active') { // $data['status'] comes from the 'data' object inside the $.ajax() method    // reverse the 'status' from (ative/inactive) 0 to 1 and 1 to 0 (and vice versa)
                $status = 0;
            } else {
                $status = 1;
            }

            ShippingCharge::where('id', $data['shipping_id'])->update(['status' => $status]); 

            return response()->json([ 
                'status'      => $status,
                'shipping_id' => $data['shipping_id']
            ]);
        }
    }

    // Add/Create Shipping Charges Method
    public function addShippingCharges(Request $request) {
        Session::put('page', 'shipping');

        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);

            // Check if any record already exists
            $existingRecord = ShippingCharge::first();
            
            if ($existingRecord) {
                // If record exists, update it instead of creating new
                $existingRecord->update([
                    'shipping_charge' => $data['shipping_charge'],
                    'free_shipping_min_amount' => $data['free_shipping_min_amount'],
                ]);
                $message = 'Shipping Charges updated successfully!';
            } else {
                // Create new record only if no record exists
                ShippingCharge::create([
                    'shipping_charge' => $data['shipping_charge'],
                    'free_shipping_min_amount' => $data['free_shipping_min_amount'],
                ]);
                $message = 'Shipping Charges added successfully!';
            }

            return redirect('admin/shipping-charges')->with('success_message', $message);
        }

        // Check if record already exists
        $existingRecord = ShippingCharge::first();
        
        if ($existingRecord) {
            // If record exists, redirect to edit page
            return redirect('admin/edit-shipping-charges/' . $existingRecord->id);
        }

        // Empty details for new record
        $shippingDetails = [
            'id' => '',
            'shipping_charge' => '',
            'free_shipping_min_amount' => ''
        ];
        $title = 'Add Shipping Charges';

        return view('admin.shipping.edit_shipping_charges')->with(compact('shippingDetails', 'title'));
    }

    // Edit/Update Shipping Charges Method
    public function editShippingCharges($id, Request $request) { 
        Session::put('page', 'shipping');

        if ($request->isMethod('post')) { 
            $data = $request->all();
            // dd($data);

            // Simple update for existing record
            ShippingCharge::where('id', $id)->update([
                'shipping_charge' => $data['shipping_charge'],
                'free_shipping_min_amount' => $data['free_shipping_min_amount'],
            ]);

            $message = 'Shipping Charges updated successfully!';
            return redirect('admin/shipping-charges')->with('success_message', $message);
        }

        $shippingDetails = ShippingCharge::where('id', $id)->first();
        $title = 'Edit Shipping Charges';

        return view('admin.shipping.edit_shipping_charges')->with(compact('shippingDetails', 'title'));
    }
}