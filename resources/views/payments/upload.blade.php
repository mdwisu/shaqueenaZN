@extends('layouts.main')

@section('title', 'Upload Payment Proof')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Upload Payment Proof for Order #{{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
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
                                    <th>Total Amount:</th>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Instructions</h5>
                            <div class="alert alert-info">
                                <p>Please transfer the total amount to one of the following bank accounts:</p>
                                <ul class="mb-0">
                                    <li>Bank BCA: 1234567890 (John Doe)</li>
                                    <li>Bank Mandiri: 0987654321 (John Doe)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="proof_image" class="form-label">Payment Proof Image</label>
                            <input type="file" class="form-control @error('proof_image') is-invalid @enderror"
                                id="proof_image" name="proof_image" required>
                            <div class="form-text">Upload a clear image of your payment receipt/transfer confirmation.
                                Accepted formats: JPG, PNG. Max size: 2MB</div>
                            @error('proof_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                placeholder="Add any additional information about your payment here...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Upload Payment Proof</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
