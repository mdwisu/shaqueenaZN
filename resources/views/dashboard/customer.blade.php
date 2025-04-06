@extends('layouts.main')

@section('title', 'My Account')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action active">My
                        Account</a>
                    <a href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action">My Orders</a>
                    <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action">My Cart</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Account</h4>
                </div>
                <div class="card-body">
                    <h5>Profile Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                            <p><strong>Member Since:</strong> {{ auth()->user()->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                        </div>
                    </div>

                    <h5>Order Summary</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>Total Orders</h5>
                                    <h2>{{ $totalOrders ?? 0 }}</h2>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('customer.orders') }}" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>Pending Orders</h5>
                                    <h2>{{ $pendingOrders ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>Completed Orders</h5>
                                    <h2>{{ $completedOrders ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4">Recent Orders</h5>
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
                            @forelse($recentOrders ?? [] as $order)
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No recent orders</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
