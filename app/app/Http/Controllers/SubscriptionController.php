<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function subscribe()
    {
        if (!auth()->user()->subscriptions()->exists()) {
            auth()->user()->subscriptions()->create();
        }
        return redirect()->back()->with('success', 'Subscribed to notifications!');
    }

    public function unsubscribe()
    {
        auth()->user()->subscriptions()->delete();
        return redirect()->back()->with('success', 'Unsubscribed from notifications!');
    }
}
