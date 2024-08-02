<x-mail::message>
    # Order Placed successfully 🎉

    <p>
        Thank you for placing your order with us. We are excited to let you know that your order has been successfully
        placed. Your order number is <strong>{{ $order->id }}</strong>.
    </p>

    <p>You can track the status of your order by logging into your account on our website.</p>

    <x-mail::button :url="$url">
        View your order
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
