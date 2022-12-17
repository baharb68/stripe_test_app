<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;

class ProductController extends Controller
{
    public function singlePayment(){
        $user = Auth::user();
        $intent = auth()->user()->createSetupIntent();
        $type = "single";
        $product = [];
        return view("subscription", compact(["intent",'user', 'type', 'product']));
    }

    public function show(Product $product, Request $request){
        $user = Auth::user();
        $intent = auth()->user()->createSetupIntent();
        $type = 'product';
        return view("subscription", compact(["product", "intent",'user', 'type']));
    }

    public function subscription(Request $request)
    {
        $type = $request->type;
        try {
            if($type == 'product'){
                $product = Product::find($request->product);
                $result = auth()->user()->newSubscription($request->product, $product->stripe_id)->create($request->payment_method);
            } else {
                $user = auth()->user();
                $user->createOrGetStripeCustomer();
                $user->addPaymentMethod($request->payment_method);
                $result = $request->user()->charge(
                    $request->amount * 100, $request->payment_method
                );
            }
            return view('subscription_success', compact(['result','type']));
        } catch(\Exception $result){
            $type = 'fail';
            return view('subscription_success', compact(['result', 'type']));
        }
    }
}
