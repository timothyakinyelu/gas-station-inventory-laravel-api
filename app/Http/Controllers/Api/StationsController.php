<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use Illuminate\Http\Request;

use App\Station;
use App\Sale;
use App\Expense;
use App\Product;
use App\ProductType;
use Carbon\Carbon;

class StationsController extends Controller
{
    /**
     * Fetch all stations associated with a company
     */
    public function index($id) 
    {
        $dec = base64_decode($id);
        $stations = Station::where('company_id', $dec)->get();

        return response()->json(StationResource::collection($stations));
    }

    /**
     * Fetch monthly data to use in charts
     */
    public function getChartDataByMonth($id) 
    {
        // dd($slug);
        $stationData = [];
        $dec = base64_decode($id);
        $stations = Station::where('company_id', $dec)->get();

        foreach ($stations as $station) {
            $tmp = [];

            $sales = Sale::where('station_id', $station->id)->get();
            $expenses = Expense::where('station_id', $station->id)->get();

            $collection = $sales->merge($expenses);
            $items = $collection->all();

            $list = array();
            foreach($items as $key => $item) {
                $list[$key] = $item;
                
                $date = Carbon::parse($list[$key]['date_of_entry'])->format('M');
                
                $total_sales = $list[$key]->amount;
                $total_expenses = $list[$key]->expense_amount;

                $st = $item->station_id;
                if(isset($tmp[$date])) {
                    if(isset($tmp[$date][$st])) {
                        $tmp[$date][$st]['totalSale'] += $total_sales;
                        $tmp[$date][$st]['totalExpense'] += $total_expenses;
                    } else {
                        $tmp[$date][$st] = [
                            'totalSale' => $total_sales,
                            'totalExpense' => $total_expenses,
                            'month' => $date
                        ];
                    }
                } else {
                    $tmp[$date] = [
                        $st => [
                            'totalSale' => $total_sales,
                            'totalExpense' => $total_expenses,
                            'month' => $date
                        ]
                    ];
                }
            }

            $temp = [];

            foreach($tmp as $xKey => $xData) {
                foreach($xData as $yKey => $yData) {
                    $temp[] = $yData;
                }
            }
            $station['data'] = $temp;
            $stationData[] = $station;
        }
        return response()->json($stationData);
    }

    /**
     * Fetch the current months station expenses
     */
    public function getExpensesByCurrentMonth($id) 
    {
        $stationExpenses = [];
        $dec = base64_decode($id);
        $stations = Station::where('company_id', $dec)->get();;

        foreach ($stations as $station) {
            $tmp = [];
            $expenses = Expense::where('station_id', $station->id)
                        ->whereMonth('date_of_entry', Carbon::now()->format('n'))
                        ->get();
            foreach($expenses as $key => $expense) {
                
                $date = Carbon::now()->format('F');
                $total_expenses = $expense->expense_amount;
                $st = $expense->station_id;
                if(isset($tmp[$date])) {
                    if(isset($tmp[$date][$st])) {
                        $tmp[$date][$st]['totalExpense'] += $total_expenses;
                    } else {
                        $tmp[$date][$st] = [
                            'totalExpense' => $total_expenses,
                            'month' => $date
                        ];
                    }
                } else {
                    $tmp[$date] = [
                        $st => [
                            'totalExpense' => $total_expenses,
                            'month' => $date
                        ]
                    ];
                }
            }

            $temp = [];
            foreach($tmp as $xKey => $xData) {
                foreach($xData as $yKey => $yData) {
                    $temp[] = $yData;
                }
            }
            $station['data'] = $temp;
            $stationExpenses[] = $station;
        }
        return response()->json($stationExpenses);
    }

    public function getStationExpensesByDate($id, $from, $to) 
    {
        $stationExpenses = [];
        $dec = \base64_decode($id);
        $stations = Station::where('company_id', $dec)->get();;

        foreach ($stations as $station) {
            $tmp = [];
            $expenses = Expense::where('station_id', $station->id)
                        ->whereBetween('date_of_entry', [$from, $to])
                        ->get();
            foreach($expenses as $key => $expense) {
                
                $date = Carbon::parse($from)->format('F');
                $total_expenses = $expense->expense_amount;
                $st = $expense->station_id;
                if(isset($tmp)) {
                    if(isset($tmp[$date][$st])) {
                        $tmp[$date][$st]['totalExpense'] += $total_expenses;
                    } else {
                        $tmp[$date][$st] = [
                            'totalExpense' => $total_expenses,
                        ];
                    }
                } else {
                    $tmp[$date] = [
                        $st => [
                            'totalExpense' => $total_expenses,
                        ]
                    ];
                }
            }

            $temp = [];
            foreach($tmp as $xKey => $xData) {
                foreach($xData as $yKey => $yData) {
                    // $yData['formatted_amount'] = \number_format($yData['total_expense'],
                    $temp[] = $yData;
                }
            }
            $station['data'] = $temp;
            $stationExpenses[] = $station;
        }
        return response()->json($stationExpenses);
    }

    /**
     *  Fetch the current months station sales
     */
    public function getSalesByCurrentMonth($id) 
    {
        // dd($id);
        $stationSales = [];
        $dec = base64_decode($id);
        $stations = Station::where('company_id', $dec)->get();

        foreach ($stations as $station) {
            $tmp = [];
            $sales = Sale::where('station_id', $station->id)
                    ->whereMonth('date_of_entry', Carbon::now()->format('n'))
                    ->get();
            foreach($sales as $key => $sale) {
                
                $date = Carbon::now()->format('F');
                $product = Product::find($sale->product_id);
                $producttype = $product->product_type_id;
                $producttypeName = ProductType::find($producttype)->name;
                $total_sales = $sale->amount;

                if(isset($tmp[$date])) {
                    if(isset($tmp[$date][$producttype])) {
                        $tmp[$date][$producttype]['totalSale'] += $total_sales;
                    } else {
                        $tmp[$date][$producttype] = [
                            'totalSale' => $total_sales,
                            'month' => $date,
                            'productType' => $producttypeName,
                            'productTypeId' => $producttype
                        ];
                    }
                } else {
                    $tmp[$date] = [
                        $producttype => [
                            'totalSale' => $total_sales,
                            'month' => $date,
                            'productType' => $producttypeName,
                            'productTypeId' => $producttype
                        ]
                    ];
                }
            }

            $temp = [];
            foreach($tmp as $xKey => $xData) {
                foreach($xData as $yKey => $yData) {
                    // $yData['formatted_amount'] = \number_format($yData['total_expense'],
                    $temp[] = $yData;
                }
            }
            $station['data'] = $temp;
            $stationSales[] = $station;
        }
        return response()->json($stationSales);
    }

    public function getStationSalesByDate($id, $from, $to) 
    {
        $stationSales = [];
        $dec = \base64_decode($id);
        $stations = Station::where('company_id', $dec)->get();

        foreach ($stations as $station) {
            $tmp = [];
            $sales = Sale::where('station_id', $station->id)
                    ->whereBetween('date_of_entry', [$from, $to])
                    ->get();
            foreach($sales as $key => $sale) {
                
                $date = Carbon::parse($from)->format('F');
                $product = Product::find($sale->product_id);
                $producttype = $product->product_type_id;
                $producttypeName = ProductType::find($producttype)->name;
                $total_sales = $sale->amount;

                if(isset($tmp[$date])) {
                    if(isset($tmp[$date][$producttype])) {
                        $tmp[$date][$producttype]['totalSale'] += $total_sales;
                    } else {
                        $tmp[$date][$producttype] = [
                            'totalSale' => $total_sales,
                            // 'month' => $date,
                            'productType' => $producttypeName,
                            'productTypeId' => $producttype
                        ];
                    }
                } else {
                    $tmp[$date] = [
                        $producttype => [
                            'totalSale' => $total_sales,
                            // 'month' => $date,
                            'productType' => $producttypeName,
                            'productTypeId' => $producttype
                        ]
                    ];
                }
            }

            $temp = [];
            foreach($tmp as $xKey => $xData) {
                foreach($xData as $yKey => $yData) {
                    $temp[] = $yData;
                }
            }
            $station['data'] = $temp;
            $stationSales[] = $station;
        }
        return response()->json($stationSales);
    }
}
