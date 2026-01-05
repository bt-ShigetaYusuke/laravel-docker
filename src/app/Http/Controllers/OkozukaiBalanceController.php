<?php

namespace App\Http\Controllers;

use App\Models\OkozukaiExpense;
use App\Models\OkozukaiMonthlySummary;
use Carbon\Carbon;

class OkozukaiBalanceController extends Controller
{
    public function index()
    {
        $budget = 30000; // とりあえず固定

        $today = Carbon::today();
        // $today = Carbon::parse('2025-12-01');
        $startOfMonth = $today->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $today->copy()->endOfMonth()->toDateString();

        $totalSpentThisMonth = OkozukaiExpense::whereBetween('spent_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // 貯金額 = これまでの total_saving の最大値
        $totalSaving = OkozukaiMonthlySummary::max('total_saving') ?? 0;

        $remaining = ($budget - $totalSpentThisMonth) + $totalSaving;

        // 月初ボタンで「締める対象の月」（＝前月）
        $targetForClose = $today->copy()->subMonth();
        $targetYearMonth = $targetForClose->format('Y-m');
        $closeTargetLabel = $targetForClose->format('Y年n月分');

        // その月のサマリがすでに存在するか（2重締め防止）
        $alreadyClosed = OkozukaiMonthlySummary::where('year_month', $targetYearMonth)->exists();

        return view('okozukai.balance', compact(
            'budget',
            'totalSpentThisMonth',
            'remaining',
            'totalSaving',
            'closeTargetLabel',
            'alreadyClosed'
        ));
    }
}
