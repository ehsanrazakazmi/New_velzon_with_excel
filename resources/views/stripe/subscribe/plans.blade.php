@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('content')

    @include('partials.session')

    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="row">
                @if ($basic)
                    @include('stripe.subscribe.basic')
                @endif
                <!--end col-->

                @if ($professional)
                    @include('stripe.subscribe.professional')
                @endif
                <!--end col-->
                @if ($enterprise)
                    @include('stripe.subscribe.enterprise')
                @endif
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <!--  Extra Large modal example -->
    <div class="modal fade " id="stripe_modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <h4 id="planname"></h4>
                        </div>
                        <div>
                            <b>$</b>
                            <b id="price"></b>
                        </div>
                    </div>
                </div>
                <form action="{{ route('plan.process') }}" method="POST" id="subscribe-form">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="plan_id" value="" id="Plan_ID">
                        <label for="card-holder-name">Card Holder Name</label> <br>
                        <input id="card-holder-name" type="text" class="form-control">

                        <div class="form-row">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element" class="form-control">
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <div class="stripe-errors"></div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif
                        <br>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i
                                class="ri-close-line me-1 align-middle"></i> Close</a>

                        <div class="form-group text-center">
                            <button id="process-btn" data-secret="{{ $intent->client_secret }}"
                                class="btn btn-lg btn-success btn-block" type="submit">Process
                                Subscription</button>
                        </div>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <button id="error" type="button" data-toast data-toast-text="You are already subscribed!" data-toast-gravity="top"
        data-toast-position="right" data-toast-className="primary" data-toast-duration="3000" data-toast-close="close"
        data-toast-style="style" class="btn btn-light w-xs "></button>
@endsection

{{-- scripts --}}
@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="{{ URL::asset('assets/js/pages/pricing.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        function getPlan(plan) {
            // console.log('plan id :'+plan);// Set the plan variable to the input element
            $.ajax({
                url: '{{ route('plans.checkout') }}',
                type: "POST",
                data: {
                    planId: plan,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // console.log(data.plan.price);
                    if (data == 'false') {

                        $('#error').click();
                        return;
                    }
                    $('#planname').html(data.plan.name);
                    var price = data.plan.price / 100;
                    $('#price').html(price);
                    $('#Plan_ID').val(data.plan.plan_id);

                    // $('#process-btn').attr('data-secret', data.intent.client_secret);

                    $('#stripe_modal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {
            hidePostalCode: true,
            style: style
        });
        card.mount('#card-element');
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });


        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('process-btn');
        // console.log(cardButton);
        const clientSecret = cardButton.dataset.secret;


        cardButton.addEventListener('click', async (e) => {
            e.preventDefault();
            console.log("attempting");
            const {
                setupIntent,
                error
            } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            );
            if (error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                console.log(setupIntent.payment_method);
                paymentMethodHandler(setupIntent.payment_method);
            }
        });

        function paymentMethodHandler(payment_method) {
            console.log(payment_method);
            var form = document.getElementById('subscribe-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', payment_method);
            form.appendChild(hiddenInput);
            console.log(form);
            form.submit();
        }
    </script>
@endsection
