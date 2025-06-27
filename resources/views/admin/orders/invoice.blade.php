@extends('layouts.main')

@section('title', 'Invoice #' . $order->order_number)

@section('content')
    <div class="container py-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Invoice #{{ $order->order_number }}</h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-sm btn-light">Print Invoice</button>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light">Back to Order</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="mb-3">From:</h6>
                        <div>
                            <strong>Your E-commerce Store</strong>
                        </div>
                        <div>123 Main Street</div>
                        <div>Jakarta, 12345</div>
                        <div>Email: info@store.com</div>
                        <div>Phone: +62 123-456-7890</div>
                    </div>

                    <div class="col-sm-6">
                        <h6 class="mb-3">To:</h6>
                        <div>
                            <strong>{{ $order->user->name }}</strong>
                        </div>
                        <div>{{ $order->shipping_address }}</div>
                        <div>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}</div>
                        <div>Email: {{ $order->user->email }}</div>
                        <div>Phone: {{ $order->shipping_phone }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="mb-3">Order Details:</h6>
                        <div><strong>Invoice Number:</strong> INV-{{ $order->order_number }}</div>
                        <div><strong>Order Number:</strong> {{ $order->order_number }}</div>
                        <div><strong>Order Date:</strong> {{ $order->created_at->format('d M Y') }}</div>
                        <div><strong>Payment Status:</strong>
                            @if ($order->payment_status == 'unpaid')
                                <span class="badge bg-secondary">Unpaid</span>
                            @elseif($order->payment_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($order->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($order->payment_status == 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <h6 class="mb-3">Payment Method:</h6>
                        <div>Bank Transfer</div>
                        <div>Bank BCA / Mandiri</div>
                    </div>
                </div>

                <div class="table-responsive-sm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Item</th>
                                <th class="right">Price</th>
                                <th class="center">Qty</th>
                                <th class="right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $index => $item)
                                <tr>
                                    <td class="center">{{ $index + 1 }}</td>
                                    <td class="left">{{ $item->product->name }}</td>
                                    <td class="right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="center">{{ $item->quantity }}</td>
                                    <td class="right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-sm-5 ms-auto">
                        <table class="table table-clear">
                            <tbody>
                                <tr>
                                    <td class="left">
                                        <strong>Subtotal</strong>
                                    </td>
                                    <td class="right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="left">
                                        <strong>Shipping</strong>
                                    </td>
                                    <td class="right">Rp 0</td>
                                </tr>
                                <tr>
                                    <td class="left">
                                        <strong>Total</strong>
                                    </td>
                                    <td class="right">
                                        <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h6>Terms &amp; Conditions</h6>
                        <p class="text-muted">Dengan menggunakan toko ini, Anda setuju untuk mematuhi semua syarat dan ketentuan yang berlaku. Semua transaksi pada kebijakan kami terkait harga, stok, pengiriman, dan pengembalian. Kami berhak mengubah atau menghentikan layanan tanpa pemberitahuan.</p>
                    </div>
                    <div class="col-lg-12 text-center mt-4">
                        <p class="text-muted">Terima Kasih Telah Berbelanja!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .card-header {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .card {
                border: none;
            }

            .card-body {
                padding: 0;
            }
        }
    </style>
@endsection
