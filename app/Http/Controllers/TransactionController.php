<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{


    public function store(Request $request)
    {
        $data = $request->validate([
            'type'        => 'required|in:expense,income',
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'notes'       => 'nullable|string|max:500',
        ]);

        $data['user_id'] = Auth::id();

        Transaction::create($data);

        return redirect()->route('dashboard')->with('success', 'Transaction added!');
    }

    public function TransactionIndex(Request $request)
    {
        $filter = $request->filter ?? '';
        $type = $request->type ?? 'all';
        $word = $request->t;
        $filterData = Transaction::where('user_id', auth::id());
        $data = Transaction::where('user_id', auth::id());
        if ($filter == 'month') {
            $month = date('m');
            $filterData = $filterData->whereMonth('date', $month);
            $data = $data->whereMonth('date', $month);
        }
        if ($filter == 'last') {
            $month = date('m') - 1;
            $filterData = $filterData->whereMonth('date', $month);
            $data = $data->whereMonth('date', $month);
        }
        if ($filter == 'year') {
            $year = date('Y');
            $filterData = $filterData->whereYear('date', $year);
            $data = $data->whereYear('date', $year);
        }
        $filterData = $filterData->get();
        $totalIncome = $filterData->where('type', 'income')->sum('amount');
        $totalExpense = $filterData->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        $totalCount = $filterData->count();

        if ($type != 'all') {
            $data = $data->where('type', $type);
        }
        if ($word) {
            $data = $data->where('notes', 'like', '%' . $word . '%');
        }



        $data = $data->paginate(5);


        // dd($totalCount);

        $categories = Category::where('user_id', Auth::id())->get();
        return view('dashboard.index', compact('data', 'totalIncome', 'totalExpense', 'balance', 'totalCount', 'type', 'word', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'type'        => 'required|in:expense,income',
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'notes'       => 'nullable|string|max:500',
        ]);

        $transaction->update($data);

        return redirect()->route('dashboard')->with('success', 'Transaction updated!');
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);
        $transaction->delete();

        return redirect()->route('dashboard')->with('success', 'Transaction deleted!');
    }
}
