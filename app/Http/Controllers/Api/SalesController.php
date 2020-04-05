<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Http\Resources\SaleResource;
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
            $total_sales = $sale->amount;

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

        $paginator->withPath(config('app.url').'/api/v2/salesbystation/'.$id);
        return response()->json($paginator);
    }

    public function getSalesByDate($id, $product_code_id, $date, Request $request) 
    {
        $sales = Sale::where('station_id', $id)
        ->where('product_code_id', $product_code_id)
        ->where('date_of_entry', $date)
        ->get();
        
        $data = SaleResource::collection($sales)->flatten();

        if($data->count() > 0) {
            $items = $data->toArray($request);
    
            $currentPage = Paginator::resolveCurrentPage();
            $perPage = 20;
            $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
            $total = count($items);
    
            $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

            $paginator->withPath(config('app.url').'/api/v2/station-day-sales/'.$id.'/'.$product_code_id.'/'.$date);
            return response()->json($paginator);
        } else {
            return response()->json([
                'message' => 'No Record Available!'
            ]);
        }
    }

    public function delete($ids, Sale $sale) 
    {
        // $response = Gate::inspect('delete', $sale);
        // dd($request->all());

        // if ($response->allowed()) {
            $id = explode(",", $ids);
            $sales_to_delete = Sale::find($id);

            $sale = Sale::whereIn('id', $id)->delete();

            if($sale) {
                return response()->json(
                    ['status' => 'Sale has been deleted']
                );
            }
        // } else {
        //     return $response->message();
        // }
    }
}
