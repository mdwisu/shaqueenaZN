@extends('layouts.main')

@section('title', 'Order Details')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">Products</a>
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action active">Orders</a>
                    <a href="{{ route('payment.index') }}" class="list-group-item list-group-item-action">Payment
                        Verification</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
                    <div>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-light">Back to Orders</a>
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-sm btn-light">Download
                            Invoice</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order Number:</th>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>Customer:</th>
                                    <td>{{ $order->user->name }} ({{ $order->user->email }})</td>
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
                                @if ($order->notes)
                                    <tr>
                                        <th>Notes:</th>
                                        <td>{{ $order->notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Update Order Status</h5>
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                            Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="declined" {{ $order->status == 'declined' ? 'selected' : '' }}>
                                            Declined</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h5>Update Payment Status</h5>
                            <form action="{{ route('admin.orders.payment', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <select name="payment_status" class="form-select">
                                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                            Unpaid</option>
                                        <option value="pending"
                                            {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                            Paid</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>
                                            Failed</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Payment</button>
                                </div>
                            </form>
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
                                                        class="me-2" style="object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('images/no-image.jpg') }}" width="50"
                                                        height="50" alt="No Image" class="me-2">
                                                @endif
                                                <span>{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
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

                    @if ($order->paymentProof)
                        <div class="mt-4">
                            <h5>Payment Proof</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Uploaded Date:</th>
                                            <td>{{ $order->paymentProof->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if ($order->paymentProof->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($order->paymentProof->status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($order->paymentProof->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($order->paymentProof->verified_at)
                                            <tr>
                                                <th>Verified Date:</th>
                                                <td>
                                                    @if (is_string($order->paymentProof->verified_at))
                                                        {{ $order->paymentProof->verified_at }}
                                                    @else
                                                        {{ $order->paymentProof->verified_at->format('d M Y H:i') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($order->paymentProof->notes)
                                            <tr>
                                                <th>Notes:</th>
                                                <td>{{ $order->paymentProof->notes }}</td>
                                            </tr>
                                        @endif
                                    </table>

                                    @if ($order->paymentProof->status == 'pending')
                                        <div class="d-flex mt-3">
                                            <form action="{{ route('payment.verify', $order->paymentProof->id) }}"
                                                method="POST" class="me-2">
                                                @csrf
                                                <input type="hidden" name="status" value="verified">
                                                <button type="submit" class="btn btn-success">Verify Payment</button>
                                            </form>

                                            <form action="{{ route('payment.verify', $order->paymentProof->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger">Reject Payment</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="border p-2">
                                        <img src="{{ asset('storage/' . $order->paymentProof->image_path) }}"
                                            alt="Payment Proof" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-between">
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Order</button>
                        </form>

                        <div>
                            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">Back to Orders</a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-primary">Download
                                Invoice</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
