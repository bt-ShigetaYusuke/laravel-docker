<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OkozukaiExpense;
use App\Models\OkozukaiCategory;
use Carbon\Carbon;

class OkozukaiHistoryController extends Controller
{
    public function index(Request $request)
    {
        // ?year_month=2025-11 みたいなパラメータ。なければ今月。
        $yearMonth = $request->input('year_month', now()->format('Y-m'));

        [$year, $month] = explode('-', $yearMonth);

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $categoryId = $request->input('category_id');

        $query = OkozukaiExpense::with('category')
            ->whereBetween('spent_at', [$start->toDateString(), $end->toDateString()])
            ->orderBy('spent_at', 'asc')
            ->orderBy('id', 'asc');

        if (!empty($categoryId)) {
            $query->where('okozukai_category_id', $categoryId);
        }

        // $expenses = $query->paginate(20)->withQueryString();
        // 全件取得させとく。ページネーション一旦不要。
        $expenses = $query->get();

        $categories = OkozukaiCategory::all();

        // 過去12ヶ月分くらいをプルダウンで選べるようにする
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $d = now()->copy()->subMonths($i);
            $months[] = [
                'value' => $d->format('Y-m'),
                'label' => $d->format('Y年n月'),
            ];
        }

        return view('okozukai.history', compact(
            'expenses',
            'categories',
            'yearMonth',
            'categoryId',
            'months'
        ));
    }

    public function destroy(OkozukaiExpense $expense)
    {
        $expense->delete(); // 取消＝物理削除でOKな想定

        // 合計や残高はDBから再計算してるので、消せば勝手に反映される
        return back()->with('success', '支出を取消しました');
    }
}
