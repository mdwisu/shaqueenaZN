@extends('layouts.main')

@section('title', 'Payment Verification')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">Products</a>
                    {{-- <a href="{{ route('admin.categories') }}" class="list-group-item list-group-item-action">Categories</a> --}}
                    {{-- <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action">Users</a> --}}
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a>
                    <a href="{{ route('payment.index') }}" class="list-group-item list-group-item-action active">Payment
                        Verification</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment Verification</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Uploaded</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentProofs ?? [] as $proof)
                                    <tr>
                                        <td>{{ $proof->id }}</td>
                                        <td>{{ $proof->order->order_number }}</td>
                                        <td>{{ $proof->order->user->name }}</td>
                                        <td>Rp {{ number_format($proof->order->total_amount, 0, ',', '.') }}</td>
                                        <td>{{ $proof->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            @if ($proof->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($proof->status == 'verified')
                                                <span class="badge bg-success">Verified</span>
                                            @elseif($proof->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('payment.show', $proof->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No payment proofs waiting for verification
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $paymentProofs->links() ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
