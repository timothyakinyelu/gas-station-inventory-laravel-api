<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NewProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Gate;

use App\Product;
use App\ProductCode;
use App\ProductType;

class ProductsController extends Controller
{
    public function index($id, Request $request) 
    {
        $response = Gate::inspect('viewAny', [ Product::class ]);

        if ($response->allowed()) {
            $dec = \base64_decode($id);

            $products = Product::where('company_id', $dec)
                ->orderBy('id', 'DESC')
                ->get();

            $data = ProductResource::collection($products);

            if($data->count() > 0) {

                $items = $data->toArray($request);

                $currentPage = Paginator::resolveCurrentPage();
                $perPage = 20;
                $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
                $total = count($items);

                $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

                $paginator->withPath(config('app.url').'/api/v2/products/'.$dec);

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

    public function productType() 
    {
        $ProductType = ProductType::orderBy('id', 'DESC')->get();
        return response()->json($ProductType);
    }

    public function productCode() 
    {
        $ProductCode = ProductCode::orderBy('id', 'DESC')->get();
        return response()->json($ProductCode);
    }

    public function store(NewProductRequest $request) 
    {
        $response = Gate::inspect('create', [ Product::class]);

        if ($response->allowed()) {
            // The action is authorized...
            $companyID = \base64_decode($request->input('company_id'));
            $product = Product::firstOrCreate(
                ['name' => $request->input('name')],
                [
                    'company_id' => $companyID, 
                    'product_type_id' => $request->input('product_type_id'), 
                    'product_code_id' => $request->input('product_code_id'),
                    'price' => $request->input('price')
                ]
            );
    
            if($product->wasRecentlyCreated) {
                return response()->json([
                    'product' => $product,
                    'success' => 'New Product Created Successfully!'
                ], 200);
            }
            
            return response()->json([
                'error' => 'Product already exists!'
            ], 412);

        } else {
            return $response->message();
        }
    }

    public function getProductToEdit($id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'product' => $product
        ]);
    }

    public function getProductByCodeId($companyID, $id) 
    {
        $dec = \base64_decode($companyID);

        $products = Product::where('company_id', $dec)
                    ->where('product_code_id', $id)
                    ->orderBy('id', 'desc')
                    ->get();

        return response()->json([
            'products' => ProductResource::collection($products)
        ]);
    }

    public function update($id, Request $request) 
    {
        $product= Product::find($id);

        $response = Gate::inspect('update', $product);

        if ($response->allowed()) {
            $product->product_code_id = $request->get('product_code_id');
            $product->name = $request->get('name');
            $product->price = $request->get('price');
    
            $product->save();  
       
            return response()->json([
                'success' => 'Product updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function delete($ids, Product $product) 
    {
        $response = Gate::inspect('delete', $product);
        // dd($request->all());

        if ($response->allowed()) {
            $id = explode(",", $ids);
            $products_to_delete = Product::find($id);

            $product = Product::whereIn('id', $id)->delete();

            if($sale) {
                return response()->json(
                    ['status' => 'Product has been deleted']
                );
            }
        } else {
            return $response->message();
        }
    }

}
