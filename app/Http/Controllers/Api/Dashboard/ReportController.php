<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\IncomeAndExpense;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\TargetAchieved;
use App\Models\TransferringTreasury;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use Message;

    // start Platform Account Report

    // income platform report
    public function incomePlatformReport(Request $request)
    {
        $incomeAndExpense_query = IncomeAndExpense::with('income', 'user')->whereNotNull('income_id')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('income', 'name', 'like', '%' . $request->search . '%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->income_id, function ($q) use ($request) {
                    $q->where('income_id', $request->income_id);
                });
            });
            $incomeAndExpense = $incomeAndExpense_query->latest()->paginate(15);
            $incomeAndExpense_sum = $incomeAndExpense_query->sum('amount');

        return $this->sendResponse(['incomes' => $incomeAndExpense ,'incomeAndExpense_sum' => $incomeAndExpense_sum], 'Data exited successfully');
    }

    // expense platform report
    public function expensePlatformReport(Request $request)
    {
        $incomeAndExpense_query = IncomeAndExpense::with('expense', 'user')->whereNotNull('expense_id')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('expense', 'name', 'like', '%' . $request->search . '%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->expense_id, function ($q) use ($request) {
                    $q->where('expense_id', $request->expense_id);
                });
            });
            $incomeAndExpense = $incomeAndExpense_query->latest()->paginate(15);
            $incomeAndExpense_sum = $incomeAndExpense_query->sum('amount');
        return $this->sendResponse(['incomes' => $incomeAndExpense,'incomeAndExpense_sum' => $incomeAndExpense_sum], 'Data exited successfully');
    }

    // transferring Treasury Platform Report

    public function transferringTreasuryPlatformReport(Request $request)
    {
        $incomeAndExpense = TransferringTreasury::with('fromTreasury', 'user','toTreasury')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('fromTreasury', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('toTreasury', 'name', 'like', '%' . $request->search . '%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['incomes' => $incomeAndExpense], 'Data exited successfully');
    }

    // income treasury platform report
    public function incomeTreasuryPlatformReport(Request $request)
    {
        $incomeAndExpense = IncomeAndExpense::with('income', 'user')->whereNotNull('income_id')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('treasury', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('income', 'name', 'like', '%' . $request->search . '%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->treasury_id, function ($q) use ($request) {
                    $q->where('treasury_id', $request->treasury_id);
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['incomes' => $incomeAndExpense], 'Data exited successfully');
    }

    // expense treasury platform report
    public function expenseTreasuryPlatformReport(Request $request)
    {
        $incomeAndExpense = IncomeAndExpense::with('expense', 'user')->whereNotNull('expense_id')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('treasury', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('expense', 'name', 'like', '%' . $request->search . '%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->treasury_id, function ($q) use ($request) {
                    $q->where('treasury_id', $request->treasury_id);
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['incomes' => $incomeAndExpense], 'Data exited successfully');
    }

    // Daily Balance platform report
    public function dailyBalancePlatformReport(Request $request)
    {
        $incomeAndExpense = IncomeAndExpense::with('expense','income','user')
           ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
           })->where(function ($q) use ($request) {
                $q->when($request->treasury_id, function ($q) use ($request) {
                    $q->where('treasury_id', $request->treasury_id);
                });
        })->get();

        return $this->sendResponse(['incomes' => $incomeAndExpense], 'Data exited successfully');
    }

    // supplier dues platform report
    public function supplierDuesPlatformReport(Request $request)
    {
        $refund_allowed_days =Setting::first()->refund_allowed_days;
        $suppliers = Supplier::
            with(['orders' =>function($q) use($refund_allowed_days,$request){
                $q->where('orders.order_status','Completed')
                ->where('order_suppliers.dues',$request->paid)
                ->where('orders.updated_at','<',now()->subDays($refund_allowed_days));
            }])
            ->where(function($q) use($request){
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('suppliers.responsible_name', 'like', '%' . $request->search . '%')
                    ->orWhere('suppliers.name', 'like', '%' . $request->search . '%')
                    ->orWhere('suppliers.phone', 'like', '%' . $request->search . '%')
                    ->orWhere('suppliers.commerical_register', 'like', '%' . $request->search . '%')
                    ->orWhere('suppliers.tax_card', 'like', '%' . $request->search . '%')
                    ->orWhere('suppliers.address', 'like', '%' . $request->search . '%')
                    ->orWhere('orders.id', 'like', '%' . $request->search . '%')
                    ->orWhere('order_suppliers.sub_total', 'like', '%' . $request->search . '%');
                });
            })
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('orders.updated_at', ">=", $request->from_date)
                        ->whereDate('orders.updated_at', "<=", $request->to_date);
                });
            })
            ->whereHas('orders' , function($q) use($refund_allowed_days,$request){
                $q->where('orders.order_status','Completed')
                    ->where('order_suppliers.dues',$request->paid)
                    ->where('orders.updated_at','<',now()->subDays($refund_allowed_days));
            })->withCount('orders')
            ->paginate(10);

        return $this->sendResponse(['suppliers' => $suppliers], 'Data exited successfully');
    }

}
