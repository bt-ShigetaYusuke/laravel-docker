<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OkozukaiExpense;
use App\Models\OkozukaiCategory;
use Carbon\Carbon;

class OkozukaiController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        // $today = Carbon::parse('2025-12-01');
        $startOfMonth = $today->copy()->startOfMonth()->toDateString();
        $endOfMonth   = $today->copy()->endOfMonth()->toDateString();

        // 今月の支出合計
        $monthlyTotal = OkozukaiExpense::whereBetween('spent_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $categories = OkozukaiCategory::all();

        return view('okozukai.index', compact('monthlyTotal', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'spent_at' => ['required', 'date'],
            'amount' => ['required', 'integer', 'min:1'],
            'okozukai_category_id' => ['nullable', 'exists:okozukai_categories,id'],
        ]);

        OkozukaiExpense::create([
            'spent_at' => $request->spent_at,
            'amount' => $request->amount,
            'okozukai_category_id' => $request->okozukai_category_id,
        ]);

        return redirect()->route('okozukai.index')->with('success', '登録したよ！');
    }
}
