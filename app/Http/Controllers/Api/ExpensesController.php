<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Http\Resources\ExpenseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

use App\Expense;
class ExpensesController extends Controller
{
    public function getExpensesByStation($id) 
    {
        $data = [];
        
        $expenses = Expense::where('station_id', $id)
            ->orderBy('date_of_entry', 'DESC')
            ->get();

        $results = [];

        foreach($expenses as $key => $expense) {
            $date = $expense->date_of_entry;
            $total_expense = $expense->expense_amount;

            $st = $expense->station_id;
            

            if(isset($results[$date])) {
                if(isset($results[$date][$st])) {
                    $results[$date][$st]['total_expense'] += $total_expense;
                } else {
                    $results[$date][$st] = [
                        'total_expense' => $total_expense,
                        'date' => Carbon::parse($date)->isoFormat('dddd, Do MMMM YYYY'),
                    ];
                }
            } else {
                $results[$date] = [
                    $st => [
                        'total_expense' => (float)$total_expense,
                        'date' => Carbon::parse($date)->isoFormat('dddd, Do MMMM YYYY')
                    ]
                ];
            }
        }

        $temp = [];
        foreach($results as $xKey => $xData) {
            foreach($xData as $yKey => $yData) {
                $temp[] = $yData;
            }
        }

        $data = $temp;
        $items = $data;

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
        $total = count($items);

        $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

        $paginator->withPath(config('app.url').'/api/v2/expensesbystation/'.$id);
        return response()->json($paginator);
    }

    public function getExpenseDetails($id, $date, Request $request) 
    {
        
        $response = Gate::inspect('viewAny', [ Expense::class ]);

        $day = Carbon::parse($date)->toDateString();
        if ($response->allowed()) {
        
            $expenses = Expense::where('station_id', $id)
            ->where('date_of_entry', $day)
            ->get();
            
            $data = ExpenseResource::collection($expenses);
            if($data->count() > 0) {

                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/station-day-expense/'.$id.'/'.$date);
                return response()->json($paginator);
            } else {
                return response()->json([
                    'message' => 'No Record in the database!'
                ]);
            }
        } else {
            return $response->message();
        }
    }

    public function getExpenseToEdit($id)
    {
        $expense = Expense::findOrFail($id);

        return response()->json([
            'expense' => $expense
        ]);
    }

    public function getDayExpenses($stationId, $date, Request $request, Expense $expense) 
    {
        $response = Gate::inspect('view', [ $expense ]);

        if ($response->allowed()) {
            $expenses = Expense::where('station_id', $stationId)
                    ->where('date_of_entry', $date)
                    ->get();
            
        
            $data = ExpenseResource::collection($expenses);

            if($data->count() > 0) {
                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/expenses/expense/'.$stationId.'/'.$date);
                return response()->json($paginator);
            } else {
                return response()->json([
                    'message' => 'No Record in the database!'
                ]);
            }
        } else {
            return $response->message();
        }
    }

    public function storeExpense(Request $request) 
    {
        $response = Gate::inspect('create', [ Expense::class]);

        $items = json_decode($request->items, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($items as $key => $value) {
                $List[] = $value;
                $expense = Expense::create([
                    'station_id' => $request->get('station_id'),
                    'date_of_entry' => $request->get('expense_date'), 
                    'expense_amount' => $List[$key]['amount'],
                    'description' => $List[$key]['reference']
                ]);
            }
            return response()->json([
                'success' => 'Expense Entered Successfully!'
            ]);
        } else {
            return $response->message();
        }
    }

    public function update($id, Request $request) 
    {
        $expense = Expense::find($id);

        $response = Gate::inspect('update', $expense);

        if ($response->allowed()) {
            $expense->amount = $request->get('amount');
            $expense->description = $request->get('description');
            $expense->date_of_entry = $request->get('expense_date');
    
            $expense->save();  
       
            return response()->json([
                'success' => 'Expense updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function delete($ids, Expense $expense) 
    {
        $response = Gate::inspect('delete', $expense);
        if ($response->allowed()) {
            $id = explode(",", $ids);

            $expense = Expense::whereIn('id', $id)->delete();

            if($expense) {
                return response()->json(
                    ['status' => 'Expense has been deleted']
                );
            }
        } else {
            return $response->message();
        }
    }
}
