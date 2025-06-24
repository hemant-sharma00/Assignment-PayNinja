<x-mail::message>
# Order Confirmation

Thank you for your order!

**Product:** {{ $order->product_name }}  
**Amount:** ₹{{ number_format($order->amount, 2) }}

We appreciate your business.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
