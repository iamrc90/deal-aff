<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request) {
        // save if new
        $params = Input::all();
        if(isset($params['email']) && !empty($params['email'])) {
            $subscriber = Subscriber::firstOrCreate(['email' => $params['email']]);
            $subscriber->is_subscribed = 1;
            $subscriber->save();
            return [
                'status'=> 12000,
                'message'=>'You have subscribed to Deal Khojo'
            ];
        }
        return [
            'status'=> 12500,
            'message'=>'Please try again later.'
        ];
    }
}
