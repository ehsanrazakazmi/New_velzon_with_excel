@extends('layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Starter  @endslot
@endcomponent
@include('partials.session')
<div class="row">
        
    <!-- Col starts -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0"><strong>&#x2022;  You will be charged ${{ number_format($plan->price, 2) }} for {{ $plan->name }} Plan
                </strong></h4>
                <div class="col-sm-auto">
                    <div>
                        <a href="{{ route('main-plans') }}" class="btn btn-outline-dark waves-effect waves-light px-4 py-2 bg-green-700 hover:bg-green-500 text-slate-100 rounded-md">Go to Plans</a>
                    </div>               
                </div>
            </div>

            <div class="card-body">
                <form id="payment-form" action="{{ route('subscription.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" id="plan" value="{{ $plan->id }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="card-holder-name" class="form-label">Name</label>
                            <input type="text" name="name" id="card-holder-name" class="form-control" value="" placeholder="Name on the card">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="card-element" class="form-label">Card details</label>
                            <div id="card-element" class="form-control"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <hr>
                            <button type="submit" class="btn btn-dark" id="card-button" data-secret="{{ $intent->client_secret }}">Purchase</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>

@endsection
@section('script')
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}')
    const elements = stripe.elements()
    const cardElement = elements.create('card')
    cardElement.mount('#card-element')
    const form = document.getElementById('payment-form')
    const cardBtn = document.getElementById('card-button')
    const cardHolderName = document.getElementById('card-holder-name')
    form.addEventListener('submit', async (e) => {
        e.preventDefault()
        cardBtn.disabled = true
        const { setupIntent, error } = await stripe.confirmCardSetup(
            cardBtn.dataset.secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: cardHolderName.value
                    }   
                }
            }
        )
        if(error) {
            cardBtn.disable = false
        } else {
            let token = document.createElement('input')
            token.setAttribute('type', 'hidden')
            token.setAttribute('name', 'token')
            token.setAttribute('value', setupIntent.payment_method)
            form.appendChild(token)
            form.submit();
        }
    })
</script>
@endsection
