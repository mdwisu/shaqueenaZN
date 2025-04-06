@extends('layouts.main')

@section('title', 'My Orders')

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
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Orders</h4>
                </div>
                <div class="card-body">
                    <!-- Filter options -->
                    <div class="mb-3">
                        <form action="{{ route('customer.orders') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>
                                        Declined</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="payment_status" class="form-select">
                                    <option value="">All Payment Statuses</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('customer.orders') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
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
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('customer.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                            @if ($order->payment_status == 'unpaid')
                                                <a href="{{ route('payment.create', $order->id) }}"
                                                    class="btn btn-sm btn-success">Pay Now</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
