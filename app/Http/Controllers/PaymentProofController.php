<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    // Customer: Upload payment form
    public function create(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id() && Auth::user()->role === 'customer') {
            abort(403);
        }

        return view('payment.upload', compact('order'));
    }

    // Customer: Process payment upload
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:255',
        ]);

        // Check if order belongs to user
        if ($order->user_id !== Auth::id() && Auth::user()->role === 'customer') {
            abort(403);
        }

        // Upload file
        $path = $request->file('proof_image')->store('payment_proofs', 'public');

        // Create payment proof
        PaymentProof::create([
            'order_id' => $order->id,
            'image_path' => $path,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        // Update order payment status
        $order->payment_status = 'pending';
        $order->save();

        if (Auth::user()->role === 'customer') {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Payment proof uploaded successfully');
        } else {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Payment proof uploaded successfully');
        }
    }

    // Admin/Seller: View all pending payment proofs
    public function index()
    {
        $paymentProofs = PaymentProof::where('status', 'pending')
            ->with('order.user')
            ->latest()
            ->paginate(10);

        return view('payment.index', compact('paymentProofs'));
    }

    // Admin/Seller: View payment proof details
    public function show(PaymentProof $paymentProof)
    {
        return view('payment.show', compact('paymentProof'));
    }

    // Admin/Seller: Verify payment proof
    public function verify(Request $request, PaymentProof $paymentProof)
    {
        $status = $request->status; // 'verified' or 'rejected'

        $paymentProof->status = $status;
        $paymentProof->verified_at = now();
        $paymentProof->verified_by = Auth::id();
        $paymentProof->save();

        // Update order status
        $order = $paymentProof->order;

        if ($status === 'verified') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
        } else {
            $order->payment_status = 'failed';
        }

        $order->save();

        return redirect()->route('payment.index')
            ->with('success', 'Payment verification completed');
    }
}
