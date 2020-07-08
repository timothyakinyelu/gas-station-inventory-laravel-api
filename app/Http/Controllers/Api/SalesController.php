<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Http\Resources\SaleResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use App\Sale;
use App\Product;
use App\ProductCode;

class SalesController extends Controller
{
    public function getSalesByStation($id, Request $request)
    {
        $response = Gate::inspect('viewAny', [ Sale::class ]);
        $data = [];
        
        if ($response->allowed()) {
            $term = $request->input('search');

            if ($term) {
                $k = Sale::where('station_id', $id)
                    ->orderBy('date_of_entry', 'DESC')
                    ->get();
                        
                $sales = $k->filter(function($item) use ($term) {
                    $value = stripos($item->product_code['code']. ' ' .$item['date_of_entry'], strval($term)) !== false;
                    $value1 = stripos($item['date_of_entry']. ' ' .$item->product_code['code'], strval($term)) !== false;
                    
                    return $value || $value1;
                });
            } else {
                $sales = Sale::where('station_id', $id)
                    ->orderBy('date_of_entry', 'DESC')->get();
            }

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

            if(count($data) > 0) {
                $items = $data;

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/salesbystation/'.$id);
                return response()->json($paginator);
            } else {
                return response()->json([
                    'message' => 'No Record Available!'
                ]);
            }   
        } else {
            return $response->message();
        }
    }

    public function getSalesByDate($id, $product_code_id, $date, Request $request) 
    {
        $response = Gate::inspect('viewAny', [ Sale::class ]);

        if ($response->allowed()) {
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
        } else {
            return $response->message();
        }
    }

    public function getSaleToEdit($id)
    {
        $sale = Sale::findOrFail($id);

        return response()->json([
            'sale' => $sale
        ]);
    }

    public function getDaySalesByProductId($stationId, $productId, $date, Request $request, Sale $sale) 
    {
        $response = Gate::inspect('view', [ $sale ]);

        if ($response->allowed()) {
            $sales = Sale::where('station_id', $stationId)
                    ->where('product_id', $productId)
                    ->where('date_of_entry', $date)
                    ->get();
            
        
            $data = SaleResource::collection($sales)->flatten();
            if($data->count() > 0) {

                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/sales/sale/'.$stationId.'/'.$productId.'/'.$date);
                return response()->json($paginator);
            } else {
                return response()->json([
                    'message' => 'No Record Available!'
                ]);
            }
        } else {
            return $response->message();
        }
    }

    public function getDaySalesByProductCodeId($stationId, $productCodeId, $date, Request $request, Sale $sale) 
    {
        $response = Gate::inspect('view', [ $sale ]);

        if ($response->allowed()) {
            $sales = Sale::where('station_id', $stationId)
                    ->where('product_code_id', $productCodeId)
                    ->where('date_of_entry', $date)
                    ->get();
            
        
            $data = SaleResource::collection($sales)->flatten();

            if($data->count() > 0) {
                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/sales/sale/'.$stationId.'/'.$productCodeId.'/'.$date);
                return response()->json($paginator);
            } else {
                return response()->json([
                    'message' => 'No Record Available!'
                ]);
            }
        } else {
            return $response->message();
        }
    }

    public function storeWet(Request $request) 
    {
        $response = Gate::inspect('create', [Sale::class]);
        
        $foreCourts = json_decode($request->foreCourts, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($foreCourts as $key => $value) {

                $List[] = $value;
                $sale = Sale::create([
                    'station_id' => $request->get('station_id'),
                    'product_id' => $request->get('product_id'), 
                    'product_code_id' => $request->get('product_code_id'), 
                    'unit_price' => $request->get('unit_price'),
                    'date_of_entry' => $request->get('date_of_entry'), 
                    'pump_code' => $List[$key]['pumpCode'],
                    'start_metre' => $List[$key]['startMetre'],
                    'end_metre' => $List[$key]['endMetre'], 
                    'quantity_sold' => $List[$key]['quantitySold'],
                    'amount' => $List[$key]['amount']
                ]);
            }
            return response()->json([
                'success' => 'Sale Completed Successfully!'
            ]);
        } else {
            return $response->message();
        }
    }

    public function storeDry(Request $request) 
    {
        $response = Gate::inspect('create', [ Sale::class]);

        $foreCourts = json_decode($request->foreCourts, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($foreCourts as $key => $value) {

                $List[] = $value;
                $sale = Sale::create([
                    'station_id' => $request->get('station_id'),
                    'product_id' => $List[$key]['productID'],
                    'product_code_id' => $request->get('product_code_id'), 
                    'unit_price' => $List[$key]['unitPrice'],
                    'date_of_entry' => $request->get('date_of_entry'),
                    'quantity_sold' => $List[$key]['quantitySold'],
                    'amount' => $List[$key]['amount']
                ]);
            }
            return response()->json([
                'success' => 'Sale Completed Successfully!'
            ]);
        } else {
            return $response->message();
        }
    }

    public function update($id, Request $request, Sale $sale) 
    {

        $sale = Sale::findOrFail($id);
        
        $response = Gate::inspect('update', $sale);
        // dd($request->all());

        if ($response->allowed()) {
            $sale->product_id = $request->get('product_id');
            $sale->unit_price = $request->get('unit_price');
            $sale->pump_code = $request->get('pump_code');
            $sale->start_metre = $request->get('start_metre');
            $sale->end_metre = $request->get('end_metre');
            $sale->quantity_sold = $request->get('quantity_sold');
            $sale->amount = $request->get('amount');
            $sale->date_of_entry = $request->get('date_of_entry');
    
            $sale->save();  
       
            return response()->json([
                'success' => 'Sale updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function delete($ids, Sale $sale) 
    {
        $response = Gate::inspect('delete', $sale);
        // dd($request->all());

        if ($response->allowed()) {
            $id = explode(",", $ids);
            $sales_to_delete = Sale::find($id);

            $sale = Sale::whereIn('id', $id)->delete();

            if($sale) {
                return response()->json(
                    ['status' => 'Sale has been deleted']
                );
            }
        } else {
            return $response->message();
        }
    }
}
