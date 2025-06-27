@extends('layouts.main')

@section('title', 'Order Details')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action">My Account</a>
                    <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action active">My
                        Orders</a>
                    <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action">My Cart</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
                    <a href="{{ route('customer.orders') }}" class="btn btn-sm btn-light">Back to Orders</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order Number:</th>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Order Status:</th>
                                    <td>
                                        @if ($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Processing</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($order->status == 'declined')
                                            <span class="badge bg-danger">Declined</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        @if ($order->payment_status == 'unpaid')
                                            <span class="badge bg-secondary">Unpaid</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Shipping Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $order->shipping_address }}</td>
                                </tr>
                                <tr>
                                    <th>City:</th>
                                    <td>{{ $order->shipping_city }}</td>
                                </tr>
                                <tr>
                                    <th>State/Province:</th>
                                    <td>{{ $order->shipping_state }}</td>
                                </tr>
                                <tr>
                                    <th>Zipcode:</th>
                                    <td>{{ $order->shipping_zipcode }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $order->shipping_phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        width="50" height="50" alt="{{ $item->product->name }}"
                                                        class="me-2">
                                                @else
                                                    <img src="{{ asset('images/no-image.jpg') }}" width="50"
                                                        height="50" alt="No Image" class="me-2">
                                                @endif
                                                <span>{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if ($order->payment_status == 'unpaid')
                        <div class="alert alert-info mt-3">
                            <h5>Payment Instructions</h5>
                            <p>Silakan transfer jumlah total ke salah satu rekening bank berikut:</p>
                            <ul>
                                <li>Bank BCA: 1740874558 (A.n Selvyra citha dewi)</li>
                                <li>Bank BRI: 227401012529530 (A.n Selvyra citha dewi)</li>
                            </ul>
                            <p>Setelah melakukan pembayaran, silakan unggah bukti pembayaran Anda di bawah ini:</p>
                            <a href="{{ route('payment.create', $order->id) }}" class="btn btn-success">Upload Payment
                                Proof</a>
                        </div>
                    @elseif($order->payment_status == 'pending')
                        <div class="alert alert-warning mt-3">
                            <h5>Payment Verification</h5>
                            <p>Your payment proof has been uploaded and is currently being verified. We will update you once
                                the verification is complete.</p>

                            @if ($order->paymentProof)
                                <div class="mt-2">
                                    <p><strong>Uploaded on:</strong>
                                        {{ $order->paymentProof->created_at->format('d M Y H:i') }}</p>
                                    <p><strong>Current status:</strong> Pending verification</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
