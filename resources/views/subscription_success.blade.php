@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong>{{$type == 'fail' ? 'error' : 'success'}}</strong></div>
                    <div class="card-body">
                        <div class="alert alert-{{$type == 'fail' ? 'danger' : 'success'}}">
                            @if($type == 'fail')
                                {{$exception}}
                            @elseif($type == 'single')
                                <div><strong>payment_type:</strong> single payment</div>
                                <div><strong>amount:</strong> {{$result->amount_received}}</div>
                                <div><strong>client_secret:</strong> {{$result->client_secret}}</div>
                                <div><strong>customer:</strong> {{$result->customer}}</div>
                                <div><strong>payment_method:</strong> {{$result->payment_method}}</div>
                            @else
                                <div><strong>product:</strong> {{\App\Models\Product::get_product_name($result->name)}}</div>
                                <div><strong>price:</strong> {{\App\Models\Product::get_product_price($result->name)}}</div>
                                <div><strong>name:</strong> {{$result->owner->name}}</div>
                                <div><strong>email:</strong> {{$result->owner->email}}</div>
                                <div><strong>date:</strong> {{$result->created_at}}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
