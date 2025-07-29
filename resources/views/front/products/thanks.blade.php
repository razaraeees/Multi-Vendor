@extends('front.layout.layout')

@section('content')
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .thank-container {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .thank-card {
            background: #fff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 700px;
            width: 100%;
            border-top: 6px solid #ffc107; /* theme yellow */
            animation: fadeInUp 1s ease-in-out;
        }
        .thank-icon {
            font-size: 70px;
            color: #212327; /* green */
            margin-bottom: 20px;
        }
        h2 {
            font-weight: 700;
            color: #212327;
        }
        p.lead {
            font-size: 17px;
            color: #555;
        }
        .btn-custom {
            background-color: #ffc107; /* theme yellow */
            color: #000;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px 30px;
            border: none;
            transition: all 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background-color: #e0a800; /* darker yellow */
            color: #fff;
            transform: scale(1.05);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="thank-container">
    <div class="thank-card">
        <div class="thank-icon">
            <i class="bi bi-bag-check-fill"></i>
        </div>
        <h2>Thank You for Shopping!</h2>
        <p class="lead">Your order has been placed successfully.  
           You will receive a confirmation email shortly with all the details.</p>
        
        @if(session('order_id'))
            <p class="mt-3 fs-5">
                <strong>Order ID:</strong> <span class="text-success">#{{ session('order_id') }}</span>
            </p>
        @endif

        <a href="{{ url('/') }}" class="btn btn-custom mt-4">
            <i class="bi bi-house-door-fill"></i> Back to Home
        </a>
    </div>
</div>

@endsection