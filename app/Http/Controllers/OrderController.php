<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Jobs\SendOrderCreatedMail;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_email' => 'required|email',
            'product_name' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create($validated);
            dispatch(new SendOrderCreatedMail($order));
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order placed and email sent!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Order failed: ' . $e->getMessage());
        }
    }
}
