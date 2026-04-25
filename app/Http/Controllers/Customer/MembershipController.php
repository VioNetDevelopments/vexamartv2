<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::where('is_active', true)->get();
        $currentSubscription = Auth::check() ? Auth::user()->subscription : null;

        return view('customer.membership.index', compact('memberships', 'currentSubscription'));
    }

    public function subscribe(Request $request, Membership $membership)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,qris,ewallet'
        ]);

        $endDate = now()->addDays($membership->duration_days);

        $subscription = Subscription::create([
            'user_id' => Auth::id(),
            'membership_id' => $membership->id,
            'start_date' => now(),
            'end_date' => $endDate,
            'status' => 'active',
            'amount_paid' => $membership->price,
        ]);

        return redirect()->route('customer.membership.index')
            ->with('success', 'Berhasil berlangganan ' . $membership->name . '!');
    }
}