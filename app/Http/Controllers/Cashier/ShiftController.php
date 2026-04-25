<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index()
    {
        $activeShift = CashierShift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->latest()
            ->first();

        $recentShifts = CashierShift::where('user_id', auth()->id())
            ->where('status', 'closed')
            ->latest()
            ->take(10)
            ->get();

        return view('cashier.shift.index', compact('activeShift', 'recentShifts'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0',
        ]);

        // Check if already has open shift
        $existingOpen = CashierShift::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($existingOpen) {
            return back()->with('error', 'Anda sudah memiliki shift yang aktif!');
        }

        CashierShift::create([
            'user_id' => auth()->id(),
            'started_at' => now(),
            'starting_cash' => $request->starting_cash,
            'status' => 'open',
        ]);

        return back()->with('success', 'Shift berhasil dibuka!');
    }

    public function close(Request $request, CashierShift $shift)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'cash_deposit' => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string|max:500',
        ]);

        $shift->update([
            'ended_at' => now(),
            'actual_cash' => $request->actual_cash,
            'cash_deposit' => $request->cash_deposit,
            'closing_notes' => $request->closing_notes,
            'status' => 'closed',
        ]);

        $shift->calculateExpectedCash();
        $shift->save();

        return back()->with('success', 'Shift berhasil ditutup!');
    }

    public function report(CashierShift $shift)
    {
        $shift->load(['user']);

        $transactions = $shift->transactions()->with(['customer', 'items.product'])->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_sales' => $transactions->sum('grand_total'),
            'cash_sales' => $transactions->where('payment_method', 'cash')->sum('grand_total'),
            'qris_sales' => $transactions->where('payment_method', 'qris')->sum('grand_total'),
            'debit_sales' => $transactions->where('payment_method', 'debit')->sum('grand_total'),
            'ewallet_sales' => $transactions->where('payment_method', 'ewallet')->sum('grand_total'),
            'card_sales' => $transactions->where('payment_method', 'card')->sum('grand_total'),
            'bank_sales' => $transactions->where('payment_method', 'bank')->sum('grand_total'),
        ];

        return view('cashier.shift.report', compact('shift', 'transactions', 'summary'));
    }
}