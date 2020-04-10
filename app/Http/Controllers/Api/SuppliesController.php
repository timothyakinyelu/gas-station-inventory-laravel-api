<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplyResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Supply;

class SuppliesController extends Controller
{
    public function getSuppliesByStation($id, Request $request) 
    {
        $response = Gate::inspect('viewAny', [ Supply::class ]);

        if ($response->allowed()) {
            $supplies = Supply::where('station_id', $id)
                ->orderBy('date_of_supply', 'DESC')
                ->get();

            $data = SupplyResource::collection($supplies);

            if($data->count() > 0) {

                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/suppliesbystation'.$id);
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

    public function getSupplyToEdit($id)
    {
        $supply = Supply::findOrFail($id);

        return response()->json([
            'supply' => $supply
        ]);
    }

    public function getDaySuppliesByProductId($stationId, $productId, $date, Request $request) 
    {
        $supplies = Supply::where('station_id', $stationId)
                ->where('product_id', $productId)
                ->where('date_of_supply', $date)
                ->get();
        
       
        $data = SupplyCollection::collection($supplies);

        $items = $data->toArray($request);

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
        $total = count($items);

        $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

        $paginator->withPath(config('app.url').'/api/v2/supplies/supply/'.$stationId.'/'.$productId.'/'.$date);
        return response()->json($paginator);
    }

    public function getDaySuppliesByProductCodeId($stationId, $productCodeId, $date, Request $request) 
    {
        // dd($request->all());
        $supplies = Supply::where('station_id', $stationId)
                ->where('product_code_id', $productCodeId)
                ->where('date_of_supply', $date)
                ->get();
        
       
        $data = SupplyCollection::collection($supplies);

        $items = $data->toArray($request);

        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
        $total = count($items);

        $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

        $paginator->withPath(config('app.url').'/api/v2/supplies/supply/'.$stationId.'/'.$productCodeId.'/'.$date);
        return response()->json($paginator);
    }

    public function storeWetSupply(Request $request) 
    {
        // dd($request->all());
        $response = Gate::inspect('create', [ Supply::class]);
        // dd($request->all());
        $items = json_decode($request->items, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($items as $key => $value) {
                // dd($request->get('product_code_id'));
                // dd($value[$key]);
                $List[] = $value;
                $supply = Supply::create([
                    'station_id' => $request->get('station_id'),
                    'product_id' => $request->get('product_id'), 
                    'product_code_id' => $request->get('product_code_id'), 
                    'date_of_supply' => $request->get('date_of_supply'), 
                    'supply_price' => $request->get('supply_price'), 
                    'tank_code' => $List[$key]['tankCode'],
                    'inventory_received' => $List[$key]['received']
                ]);
            }
            return response()->json([
                'success' => 'Supply Entered Successfully!'
            ]);
        } else {
            return $response->message();
        }
    }

    public function storeDrySupply(Request $request) 
    {
        $response = Gate::inspect('create', [Supply::class]);

        $items = json_decode($request->items, true);
        $List = array();

        if ($response->allowed()) {
            foreach ($items as $key => $value) {

                $List[] = $value;
                $supply = Supply::create([
                    'station_id' => $request->get('station_id'),
                    'product_code_id' => $request->get('product_code_id'), 
                    'date_of_supply' => $request->get('date_of_supply'),
                    'product_id' => $List[$key]['productID'],
                    'inventory_received' => $List[$key]['received'],
                    'supply_price' => $List[$key]['supplyPrice'], 
                ]);
            }
            return response()->json([
                'success' => 'Supply Entered Successfully!'
            ]);
        } else {
            return $response->message();
        }
    }

    public function update($id, Request $request) 
    {
        $supply = Supply::find($id);

        $response = Gate::inspect('update', $supply);
        
        if ($response->allowed()) {
            $supply->product_id = $request->get('product_id');
            $supply->inventory_received = $request->get('inventory_received');
            $supply->supply_price = $request->get('supply_price');
            $supply->date_of_supply = $request->get('date_of_supply');
    
            $supply->save();  
       
            return response()->json([
                'success' => 'Supply has been updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function delete($ids, Supply $supply) 
    {
        $response = Gate::inspect('delete', $supply);
        if ($response->allowed()) {
            $id = explode(",", $ids);


            $supply = Supply::whereIn('id', $id)->delete();

            if($supply) {
                return response()->json(
                    ['status' => 'Data has been deleted']
                );
            }
        } else {
            return $response->message();
        }
    }
}
