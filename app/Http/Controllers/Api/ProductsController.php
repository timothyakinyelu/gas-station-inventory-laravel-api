<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Gate;
use App\Product;

class ProductsController extends Controller
{
    public function index(Request $request) 
    {
        $response = Gate::inspect('viewAny', [ Product::class ]);

        if ($response->allowed()) {
            $products = Product::orderBy('id', 'DESC')->get();
            $data = ProductResource::collection($products);

            $items = $data->toArray($request);

            $currentPage = Paginator::resolveCurrentPage();
            $perPage = 20;
            $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
            $total = count($items);

            $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

            $paginator->withPath(config('app.url').'/api/v2/products');

            if($items !== []) {
                return response()->json($paginator);
            }
            return response()->json([
                'message' => 'No Records Found!'
            ], 404);
        } else {
            return $response->message();
        }
    }

}
