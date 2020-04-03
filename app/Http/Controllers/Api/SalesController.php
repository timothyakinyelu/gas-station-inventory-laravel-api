<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;

use App\Sale;
use App\Product;
use App\ProductCode;

class SalesController extends Controller
{
    public function getSalesByStation($id)
    {
        $data = [];
        
        $sales = Sale::where('station_id', $id)
            ->orderBy('date_of_entry', 'DESC')
            ->get();

        $results = [];

        foreach($sales as $sale) {
            $date = $sale->date_of_entry;
            $product = Product::find($sale->product_id);
            $product_code = ProductCode::find($sale->product_code_id);
            $total_sales = sprintf("%.2f", $sale->amount);

            if(isset($results[$date])) {
                if(isset($results[$date][$product_code->id])) {
                    $results[$date][$product_code->id]['total_sale'] += $total_sales;
                } else {
                    $results[$date][$product_code->id] = [
                        'product_code_id' => $product_code->id,
                        'product_code' => $product_code->code,
                        'product' => $product->name,
                        'total_sale' => $total_sales,
                        'date' => $date,
                    ];
                }
            } else {
                $results[$date] = [
                    $product_code->id => [
                        'product_code_id' => $product_code->id,
                        'product_code' => $product_code->code,
                        'product' => $product->name,
                        'total_sale' => $total_sales,
                        'date' => $date,
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

        $paginator->withPath(config('app.url').'/api/v1/salesbystation/'.$id);
        return response()->json($paginator);
    }
}
