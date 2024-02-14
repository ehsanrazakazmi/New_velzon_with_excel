@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection

@section('styles')
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }

  /* Hide default HTML checkbox */
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  /* The slider */
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
  </style>

@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Plans
        @endslot
        @slot('title')
            Checkout
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><b>Your Subscriptions</b></h4>
                    <div class="ml-auto d-flex">
                        <a href="{{ route('plans.all') }}"
                            class="btn btn-outline-success waves-effect waves-light px-4 py-2 bg-green-700 hover:bg-green-500 text-slate-100 rounded-md">Go
                            to Plan
                        </a>
                      
                    </div>
                </div>
                <div class="card-body">
                    @if (count($subscriptions) > 0)

                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Plan Name</th>
                            <th scope="col">Subs Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Trial Start At</th>
                            <th scope="col">Pause subscription</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->plan->name }}</td>
                                    <td>{{ $subscription->name }}</td>
                                    <td>{{ $subscription->plan->price }}</td>
                                    <td>{{ $subscription->quantity }}</td>
                                    <td>{{ $subscription->created_at }}</td>
                                    <td>
                                        <label class="switch">
                                            @if ($subscription->ends_at == null)
                                                <input type="checkbox" id="switcher" checked value="{{ $subscription->name }}">
                                            @else
                                                <input type="checkbox" id="switcher" value="{{ $subscription->name }}">
                                            @endif

                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h4>You are not subscribed to any plan</h4>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#switcher').click(function() {
                var subscriptionName = $('#switcher').val();
                if($(this).is(':checked')){
                    $.ajax({
                        url:'{{ route("subscriptions.resume") }}',
                        data: { subscriptionName },
                        type:"GET",
                        success:function( response )
                        {

                        },
                        error: function(response)
                        {
                        }
                    });
                }
                else {
                    $.ajax({
                        url:'{{ route("subscriptions.cancel") }}',
                        data: { subscriptionName },
                        type:"GET",
                        success:function( response )
                        {
                            alert(response)
                        },
                        error: function(response)
                        {
                        }
                    });
                }
            });
        });
    </script>


@endsection
