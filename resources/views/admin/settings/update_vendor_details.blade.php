@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Update Vendor Details</h1>
    </div>

    <!-- Error Message -->
    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($slug == 'personal')
        <!-- Personal Information Form -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">Update Personal Information</h5>
                <form action="{{ url('admin/update-vendor-details/personal') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Vendor Username/Email</label>
                        <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="vendor_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="vendor_name" name="vendor_name"
                               placeholder="Enter Name"
                               value="{{ Auth::guard('admin')->user()->name }}">
                    </div>

                    <div class="mb-3">
                        <label for="vendor_address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="vendor_address" name="vendor_address"
                               placeholder="Enter Address"
                               value="{{ $vendorDetails['address'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="vendor_city" class="form-label">City</label>
                        <input type="text" class="form-control" id="vendor_city" name="vendor_city"
                               placeholder="Enter City"
                               value="{{ $vendorDetails['city'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="vendor_state" class="form-label">State</label>
                        <input type="text" class="form-control" id="vendor_state" name="vendor_state"
                               placeholder="Enter State"
                               value="{{ $vendorDetails['state'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="vendor_country" class="form-label">Country</label>
                        <select class="form-control" id="vendor_country" name="vendor_country">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['country_name'] }}" 
                                        @if ($country['country_name'] == $vendorDetails['country']) selected @endif>
                                    {{ $country['country_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vendor_pincode" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="vendor_pincode" name="vendor_pincode"
                               placeholder="Enter Pincode"
                               value="{{ $vendorDetails['pincode'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="vendor_mobile" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="vendor_mobile" name="vendor_mobile"
                               placeholder="Enter 10 Digit Mobile Number"
                               value="{{ Auth::guard('admin')->user()->mobile }}"
                               maxlength="10" minlength="10">
                    </div>

                    <div class="mb-3">
                        <label for="vendor_image" class="form-label">Vendor Photo</label>
                        <input type="file" class="form-control" id="vendor_image" name="vendor_image">
                        @if (!empty(Auth::guard('admin')->user()->image))
                            <div class="mt-2">
                                <a target="_blank" 
                                   href="{{ url('admin/images/photos/' . Auth::guard('admin')->user()->image) }}">
                                    View Current Image
                                </a>
                                <input type="hidden" name="current_vendor_image"
                                       value="{{ Auth::guard('admin')->user()->image }}">
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-light">
                            <i class="fas fa-undo"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @elseif ($slug == 'business')
        <!-- Business Information Form -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">Update Vendor Business Information</h5>
                <form action="{{ url('admin/update-vendor-details/business') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Vendor Username/Email</label>
                        <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="shop_name" class="form-label">Shop Name</label>
                        <input type="text" class="form-control" id="shop_name" name="shop_name"
                               placeholder="Enter Shop Name"
                               @if (isset($vendorDetails['shop_name'])) value="{{ $vendorDetails['shop_name'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="shop_address" class="form-label">Shop Address</label>
                        <input type="text" class="form-control" id="shop_address" name="shop_address"
                               placeholder="Enter Shop Address"
                               @if (isset($vendorDetails['shop_address'])) value="{{ $vendorDetails['shop_address'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="shop_city" class="form-label">Shop City</label>
                        <input type="text" class="form-control" id="shop_city" name="shop_city"
                               placeholder="Enter Shop City"
                               @if (isset($vendorDetails['shop_city'])) value="{{ $vendorDetails['shop_city'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="shop_state" class="form-label">Shop State</label>
                        <input type="text" class="form-control" id="shop_state" name="shop_state"
                               placeholder="Enter Shop State"
                               @if (isset($vendorDetails['shop_state'])) value="{{ $vendorDetails['shop_state'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="shop_country" class="form-label">Shop Country</label>
                        <select class="form-control" id="shop_country" name="shop_country">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country['country_name'] }}"
                                        @if (isset($vendorDetails['shop_country']) && $country['country_name'] == $vendorDetails['shop_country']) selected @endif>
                                    {{ $country['country_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="shop_pincode" class="form-label">Shop Pincode</label>
                        <input type="text" class="form-control" id="shop_pincode" name="shop_pincode"
                               placeholder="Enter Shop Pincode"
                               @if (isset($vendorDetails['shop_pincode'])) value="{{ $vendorDetails['shop_pincode'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="shop_mobile" class="form-label">Shop Mobile</label>
                        <input type="text" class="form-control" id="shop_mobile" name="shop_mobile"
                               placeholder="Enter 10 Digit Shop Mobile Number"
                               @if (isset($vendorDetails['shop_mobile'])) value="{{ $vendorDetails['shop_mobile'] }}" @endif
                               maxlength="10" minlength="10">
                    </div>

                    <div class="mb-3">
                        <label for="shop_website" class="form-label">Shop Website</label>
                        <input type="text" class="form-control" id="shop_website" name="shop_website"
                               placeholder="Enter Shop Website"
                               @if (isset($vendorDetails['shop_website'])) value="{{ $vendorDetails['shop_website'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="business_license_number" class="form-label">Business License Number</label>
                        <input type="text" class="form-control" id="business_license_number" name="business_license_number"
                               placeholder="Enter Business License Number"
                               @if (isset($vendorDetails['business_license_number'])) value="{{ $vendorDetails['business_license_number'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="gst_number" class="form-label">GST Number</label>
                        <input type="text" class="form-control" id="gst_number" name="gst_number"
                               placeholder="Enter GST Number"
                               @if (isset($vendorDetails['gst_number'])) value="{{ $vendorDetails['gst_number'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="pan_number" class="form-label">PAN Number</label>
                        <input type="text" class="form-control" id="pan_number" name="pan_number"
                               placeholder="Enter PAN Number"
                               @if (isset($vendorDetails['pan_number'])) value="{{ $vendorDetails['pan_number'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="address_proof" class="form-label">Shop Address Proof</label>
                        <select class="form-control" id="address_proof" name="address_proof">
                            <option value="Passport" @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Passport') selected @endif>Passport</option>
                            <option value="Voting Card" @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Voting Card') selected @endif>Voting Card</option>
                            <option value="PAN" @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'PAN') selected @endif>PAN</option>
                            <option value="Driving License" @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Driving License') selected @endif>Driving License</option>
                            <option value="Aadhar card" @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof'] == 'Aadhar card') selected @endif>Aadhar Card</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="address_proof_image" class="form-label">Address Proof Image</label>
                        <input type="file" class="form-control" id="address_proof_image" name="address_proof_image">
                        @if (!empty($vendorDetails['address_proof_image']))
                            <div class="mt-2">
                                <a target="_blank" 
                                   href="{{ url('admin/images/proofs/' . $vendorDetails['address_proof_image']) }}">
                                    View Current Image
                                </a>
                                <input type="hidden" name="current_address_proof"
                                       value="{{ $vendorDetails['address_proof_image'] }}">
                            </div>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-light">
                            <i class="fas fa-undo"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @elseif ($slug == 'bank')
        <!-- Bank Information Form -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">Update Vendor Bank Information</h5>
                <form action="{{ url('admin/update-vendor-details/bank') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Vendor Username/Email</label>
                        <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="account_holder_name" class="form-label">Account Holder Name</label>
                        <input type="text" class="form-control" id="account_holder_name" name="account_holder_name"
                               placeholder="Enter Account Holder Name"
                               @if (isset($vendorDetails['account_holder_name'])) value="{{ $vendorDetails['account_holder_name'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name"
                               placeholder="Enter Bank Name"
                               @if (isset($vendorDetails['bank_name'])) value="{{ $vendorDetails['bank_name'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control" id="account_number" name="account_number"
                               placeholder="Enter Account Number"
                               @if (isset($vendorDetails['account_number'])) value="{{ $vendorDetails['account_number'] }}" @endif>
                    </div>

                    <div class="mb-3">
                        <label for="bank_ifsc_code" class="form-label">Bank IFSC Code</label>
                        <input type="text" class="form-control" id="bank_ifsc_code" name="bank_ifsc_code"
                               placeholder="Enter Bank IFSC Code"
                               @if (isset($vendorDetails['bank_ifsc_code'])) value="{{ $vendorDetails['bank_ifsc_code'] }}" @endif>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-light">
                            <i class="fas fa-undo"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection