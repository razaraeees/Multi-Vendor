@extends('admin.layout.layout')
@section('content')
    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="page-title mb-0">{{ $title }}</h3>
        <nav aria-label="breadcrumb">
            <a href="{{ url('admin/coupons') }}" class="btn btn-primary ">
                <i class="fas fa-arrow-left me-1"></i> Back to Coupons
            </a>
        </nav>
    </div>

    {{-- Form Card --}}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            {{-- Messages --}}
            @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> {{ Session::get('error_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-circle-exclamation me-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success:</strong> {{ Session::get('success_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Coupon Form --}}
            <form class="row g-4"
                  @if (empty($coupon['id'])) action="{{ url('admin/add-edit-coupon') }}"
                  @else action="{{ url('admin/add-edit-coupon/' . $coupon['id']) }}" @endif
                  method="post" enctype="multipart/form-data">
                @csrf

                {{-- Coupon Option --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-primary">Coupon Option</label>
                    @if (empty($coupon['id']))
                        <div class="btn-group w-100 mb-2" data-bs-toggle="buttons">
                            <label class="btn btn-outline-primary rounded-3 active">
                                <input type="radio" name="coupon_option" value="Automatic" checked autocomplete="off">
                                Automatic
                            </input>
                            </label>
                            <label class="btn btn-outline-primary rounded-3">
                                <input type="radio" name="coupon_option" value="Manual" autocomplete="off">
                                Manual
                            </input>
                            </label>
                        </div>
                        <div class="my-3 d-none" id="couponField">
                            <input type="text" class="form-control shadow-sm" placeholder="Enter Coupon Code (e.g. SUMMER25)" name="coupon_code" style="letter-spacing: 1px; font-weight: 500;">
                        </div>
                    @else
                        <input type="hidden" name="coupon_option" value="{{ $coupon['coupon_option'] }}">
                        <input type="hidden" name="coupon_code" value="{{ $coupon['coupon_code'] }}">
                        <div class="p-3 bg-primary bg-opacity-10 border border-primary rounded-3 text-dark fw-bold text-center">
                            <i class="fas fa-ticket-alt me-1 text-primary"></i>
                            {{ $coupon['coupon_code'] }}
                        </div>
                    @endif
                </div>

                {{-- Coupon Type --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-primary">Coupon Type</label>
                    <div class="d-flex gap-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coupon_type" value="Multiple Times"
                                @if(isset($coupon['coupon_type']) && $coupon['coupon_type']=='Multiple Times') checked @endif>
                            <label class="form-check-label">Multiple Times</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="coupon_type" value="Single Time"
                                @if(isset($coupon['coupon_type']) && $coupon['coupon_type']=='Single Time') checked @endif>
                            <label class="form-check-label">Single Time</label>
                        </div>
                    </div>
                </div>

                {{-- Amount Type --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-primary">Amount Type</label>
                    <div class="d-flex gap-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="amount_type" value="Percentage"
                                @if(isset($coupon['amount_type']) && $coupon['amount_type']=='Percentage') checked @endif>
                            <label class="form-check-label">Percentage (%)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="amount_type" value="Fixed"
                                @if(isset($coupon['amount_type']) && $coupon['amount_type']=='Fixed') checked @endif>
                            <label class="form-check-label">Fixed (INR / USD)</label>
                        </div>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="col-md-6">
                    <label for="amount" class="form-label fw-semibold text-primary">Discount Amount</label>
                        <input type="text" class="form-control form-control-lg shadow-sm" name="amount" id="amount"
                            placeholder="e.g. 10 or 10%"
                            value="{{ $coupon['amount'] ?? old('amount') }}">

                </div>

                {{-- Categories --}}
                <div class="col-md-6">
                    <label for="categories" class="form-label fw-semibold text-primary">Select Categories</label>
                    <select name="categories[]" class="form-select select2" multiple="multiple" style="width: 100%;">
                        @foreach ($categories as $section)
                            <optgroup label="{{ $section['name'] }}">
                                @foreach ($section['categories'] as $category)
                                    <option value="{{ $category['id'] }}" 
                                        @if(in_array($category['id'], $selCats)) selected @endif>
                                        {{ $category['category_name'] }}
                                    </option>
                                    @foreach ($category['sub_categories'] as $subcategory)
                                        <option value="{{ $subcategory['id'] }}" 
                                            @if(in_array($subcategory['id'], $selCats)) selected @endif>
                                            -- {{ $subcategory['category_name'] }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                {{-- Brands --}}
                <div class="col-md-6">
                    <label for="brands" class="form-label fw-semibold text-primary">Select Brands</label>
                    <select name="brands[]" class="form-select select2" multiple="multiple" style="width: 100%;">
                        @foreach ($brands as $brand)
                            <option value="{{ $brand['id'] }}"
                                @if(in_array($brand['id'], $selBrands)) selected @endif>
                                {{ $brand['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Users --}}
                <div class="col-md-6">
                    <label for="users" class="form-label fw-semibold text-primary">Select Users</label>
                    <select name="users[]" class="form-select select2" multiple="multiple" style="width: 100%;">
                        @foreach ($users as $user)
                            <option value="{{ $user['email'] }}"
                                @if(in_array($user['email'], $selUsers)) selected @endif>
                                {{ $user['email'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Expiry Date --}}
                <div class="col-md-6">
                    <label for="expiry_date" class="form-label fw-semibold text-primary">Expiry Date</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </span>
                        <input type="date" class="form-control form-control-lg shadow-sm" name="expiry_date" id="expiry_date"
                            value="{{ $coupon['expiry_date'] ?? old('expiry_date') }}">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="col-12 text-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary px-4 rounded-3 me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                        <i class="fas fa-save me-1"></i> Save Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- Custom & Select2 Styles --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Modern Google-like Font */
        body, .form-control, .form-label, .btn {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        /* Card Styling */
        .card {
            border-radius: 12px !important;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
        }

        /* Input & Select Styling */
        .form-control, .form-select {
            border: 1.5px solid #e0e0e0;
            padding: 10px 14px;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            outline: none;
        }

        /* Labels */
        .form-label {
            font-weight: 600 !important;
            color: #0056b3 !important;
            font-size: 0.95rem;
        }

        /* Radio Buttons */
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Button Styling */
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            /* padding: 10px 20px; */
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: scale(1.03);
        }
        .btn-outline-secondary:hover {
            background-color: #f1f1f1;
        }

        /* Alert Styling */
        .alert {
            border-radius: 10px;
            font-weight: 500;
            padding: 12px 20px;
        }

        /* Select2 Custom */
        .select2-container--default .select2-selection--multiple {
            min-height: 52px !important;
            border: 1.5px solid #e0e0e0 !important;
            /* border-radius: 10px !important; */
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 0.85rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
        }

        /* Input Group Icons */
        .input-group-text {
            background-color: #f8f9fa;
            border: 1.5px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px !important;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0 !important;
        }

        /* Responsive on small screens */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
            .btn {
                width: 100%;
                margin-bottom: 8px;
            }
        }
    </style>
@endpush

{{-- Scripts --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Choose options...",
                allowClear: true,
                width: '100%',
                theme: 'default'
            });

            // Coupon Option Toggle
            $('input[name="coupon_option"]').on('change', function() {
                if ($(this).val() === 'Manual') {
                    $('#couponField').removeClass('d-none').hide().fadeIn(300);
                } else {
                    $('#couponField').fadeOut(200, function() {
                        $(this).addClass('d-none');
                    });
                }
            });
        });
    </script>
@endpush