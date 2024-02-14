<form action="{{ route('checkout.charge', $product->id) }}" method="POST">
    @csrf
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="{{ config('services.stripe.key') }}"
        data-amount="{{ $product->price * 100 }}"
        data-name="Your Company"
        data-description="{{ $product->name }}"
        data-currency="{{ $product->currency }}"
    >
    </script>
</form>
