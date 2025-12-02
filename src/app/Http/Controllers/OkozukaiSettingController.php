<?php

namespace App\Http\Controllers;

use App\Models\OkozukaiExpense;
use App\Models\OkozukaiMonthlySummary;
use Carbon\Carbon;

class OkozukaiSettingController extends Controller
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

    $remaining = $budget - $totalSpentThisMonth;

    // 貯金額 = これまでの total_saving の最大値
    $totalSaving = OkozukaiMonthlySummary::max('total_saving') ?? 0;

    // 月初ボタンで「締める対象の月」（＝前月）
    $targetForClose = $today->copy()->subMonth();
    $targetYearMonth = $targetForClose->format('Y-m');
    $closeTargetLabel = $targetForClose->format('Y年n月分');

    // その月のサマリがすでに存在するか（2重締め防止）
    $alreadyClosed = OkozukaiMonthlySummary::where('year_month', $targetYearMonth)->exists();

    return view('okozukai.setting', compact(
      'budget',
      'totalSpentThisMonth',
      'remaining',
      'totalSaving',
      'closeTargetLabel',
      'alreadyClosed'
    ));
  }

  public function monthlyClose()
  {
    $budget = 30000; // 月の予算（あとで設定画面対応してもOK）

    $today = Carbon::today();
    // $today = Carbon::parse('2025-12-01');
    $target = $today->copy()->subMonth(); // 前月を締める
    $yearMonth = $target->format('Y-m');

    // すでにその月のサマリがあるなら何もしない
    if (OkozukaiMonthlySummary::where('year_month', $yearMonth)->exists()) {
      return back()->with('error', $yearMonth . ' はすでに月次サマリ作成済みです。');
    }

    $start = $target->copy()->startOfMonth()->toDateString();
    $end   = $target->copy()->endOfMonth()->toDateString();

    // 前月の支出合計
    $totalSpent = OkozukaiExpense::whereBetween('spent_at', [$start, $end])
      ->sum('amount');

    // 残り金額
    $remaining = $budget - $totalSpent;

    // 今月貯金に回す額（マイナスは貯金しない）
    $savingAdded = max(0, $remaining);

    // これまでの貯金累計
    $prevTotalSaving = OkozukaiMonthlySummary::max('total_saving') ?? 0;

    $totalSaving = $prevTotalSaving + $savingAdded;

    OkozukaiMonthlySummary::create([
      'year_month'   => $yearMonth,
      'budget'       => $budget,
      'total_spent'  => $totalSpent,
      'remaining'    => $remaining,
      'saving_added' => $savingAdded,
      'total_saving' => $totalSaving,
    ]);

    return back()->with('success', $yearMonth . ' の月次サマリを作成しました。');
  }
}
