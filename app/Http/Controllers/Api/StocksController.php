<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Stock;

class StocksController extends Controller
{
    public function getStocksByStation($id, Request $request) {

        $response = Gate::inspect('viewAny', [ Stock::class ]);

        if ($response->allowed()) {
            $stocks = Stock::where('station_id', $id)
                ->orderBy('date_of_inventory', 'DESC')
                ->get();

            $data = StockResource::collection($stocks);

            if($data->count() > 0) {

                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/stocksbystation'.$id);
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

    public function getStockToEdit($id)
    {
        $stock = Stock::findOrFail($id);

        return response()->json([
            'stock' => $stock
        ]);
    }

    public function getDayStocksByProductId($stationId, $productId, $date, Request $request) 
    {
        $stocks = Stock::where('station_id', $stationId)
                ->where('product_id', $productId)
                ->where('date_of_inventory', $date)
                ->get();
        
       
        $data = StockCollection::collection($stocks);

        $items = $data->toArray($request);

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
        $total = count($items);

        $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

        $paginator->withPath('http://localhost:8000/api/v1/stocks/stock/'.$stationId.'/'.$productId.'/'.$date);
        return response()->json($paginator);
    }

    public function getDayStocksByProductCodeId($stationId, $productCodeId, $date, Request $request) 
    {
        // dd($request->all());
        $stocks = Stock::where('station_id', $stationId)
                ->where('product_code_id', $productCodeId)
                ->where('date_of_inventory', $date)
                ->get();
        
       
        $data = StockCollection::collection($stocks);

        $items = $data->toArray($request);

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
        $total = count($items);

        $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

        $paginator->withPath('http://localhost:8000/api/v1/stocks/stock/'.$stationId.'/'.$productCodeId.'/'.$date);
        return response()->json($paginator);
    }

    public function storeWetStock(Request $request) 
    {
        // dd($request->all());
        $response = Gate::inspect('create', [Stock::class]);
        // dd($request->all());
        $items = json_decode($request->items, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($items as $key => $value) {
                // dd($request->get('product_code_id'));
                // dd($value[$key]);
                $List[] = $value;
                $item = Stock::create([
                    'station_id' => $request->get('station_id'),
                    'product_id' => $request->get('product_id'), 
                    'product_code_id' => $request->get('product_code_id'), 
                    'date_of_inventory' => $request->get('date_of_inventory'), 
                    'tank_code' => $List[$key]['tankCode'],
                    'open_stock' => $List[$key]['startStock'],
                    'close_stock' => $List[$key]['endStock'], 
                    'inventory_sold' => $List[$key]['quantitySold'],
                    'inventory_received' => $List[$key]['received']
                ]);
            }
            return response()->json([
                'success' => 'Stock Entered Successfully!'
            ]);
        } else {
            return $response->message();
        }
    }

    public function storeDryStock(Request $request) 
    {
        // dd($request->all());
        $response = Gate::inspect('create', [ Stock::class]);
        // dd($request->all());
        $items = json_decode($request->items, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($items as $key => $value) {
                // dd($request->get('date_of_inventory'));
                $List[] = $value;
                $stock = Stock::create([
                    'station_id' => $request->get('station_id'),
                    'product_id' => $List[$key]['productID'],
                    'product_code_id' => $request->get('product_code_id'), 
                    'date_of_inventory' => $request->get('date_of_inventory'),
                    'open_stock' => $List[$key]['startStock'],
                    'close_stock' => $List[$key]['endStock'], 
                    'inventory_sold' => $List[$key]['quantitySold'],
                    'inventory_received' => $List[$key]['received']
                ]);
            }
            return response()->json([
                'success' => 'Stock Entered Successfully!'
            ]);
        } else {
            return $response->message();
        }
        // return $List;
    }

    public function update($id, Request $request) 
    {
        // dd($request->all());
        $stock = Stock::find($id);

        $response = Gate::inspect('update', $stock);
        // dd($request->all());

        if ($response->allowed()) {
            $stock->product_id = $request->get('product_id');
            $stock->tank_code = $request->get('tank_code');
            $stock->open_stock = $request->get('open_stock');
            $stock->close_stock = $request->get('close_stock');
            $stock->inventory_sold = $request->get('inventory_sold');
            $stock->inventory_received = $request->get('inventory_received');
            $stock->date_of_inventory = $request->get('date_of_inventory');
    
            $stock->save();  
       
            return response()->json([
                'success' => 'Stock has been updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function delete($ids, Stock $stock) 
    {
        $response = Gate::inspect('delete', $stock);
        // dd($request->all());

        if ($response->allowed()) {
            $id = explode(",", $ids);
            $stocks_to_delete = Stock::find($id);

            $stock = Stock::whereIn('id', $id)->delete();

            if($stock) {
                return response()->json(
                    ['status' => 'Stock has been deleted']
                );
            }
        } else {
            return $response->message();
        }
    }
}
