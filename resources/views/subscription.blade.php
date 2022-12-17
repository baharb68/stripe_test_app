@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        @if($type == 'single')
                            <strong>Single Payment</strong>
                        @else
                            <strong>{{number_format($product->price)}}$ for {{$product->name}}</strong>
                        @endif
                    </div>
                    <div class="card-body">

                        <form id="subscribe-form" action="{{route("subscription.create")}}" method="post">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="type" id="type" value="{{$type}}">
                            <input type="hidden" name="product" id="product" value="{{$type == 'product' ? $product->id : ''}}">
                            @if($type == 'single')
                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>amount</label>
                                            <input type="text" name="amount" id="amount" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>name</label>
                                        <input type="text" name="name" id="card-holder-name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="card-element">Credit or debit card</label>
                                <div id="card-element" class="form-control">
                                </div>
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
                            <div class="form-group text-center">
                                <button  id="card-button" data-secret="{{ $intent->client_secret }}" class="btn btn-success btn-block">{{$type == 'single' ? 'single payment submit' : number_format($product->price).'$ payment'}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://js.stripe.com/v3/"></script>

    <script>

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
       var card = elements.create('card', {hidePostalCode: true,
           style: style});
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
       const cardButton = document.getElementById('card-button');
       const clientSecret = cardButton.dataset.secret;
       cardButton.addEventListener('click', async (e) => {
           e.preventDefault();
           console.log("attempting");
           const { setupIntent, error } = await stripe.confirmCardSetup(
               clientSecret, {
                   payment_method: {
                       card: card,
                       billing_details: { name: cardHolderName.value }
                   }
               }
           );
           if (error) {
               var errorElement = document.getElementById('card-errors');
               errorElement.textContent = error.message;
           } else {
               paymentMethodHandler(setupIntent.payment_method);
           }
       });
       function paymentMethodHandler(payment_method) {
           var form = document.getElementById('subscribe-form');
           var hiddenInput = document.createElement('input');
           hiddenInput.setAttribute('type', 'hidden');
           hiddenInput.setAttribute('name', 'payment_method');
           hiddenInput.setAttribute('value', payment_method);
           form.appendChild(hiddenInput);
           form.submit();
       }
    </script>
@endsection
