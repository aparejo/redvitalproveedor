<?php

namespace App\Http\Controllers\Api\Provider\ProductProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\ProductProvider;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Imports\ProductProviderImport;

class ProductProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $rules = [
        'name' => 'required',
        'category' => 'required',
        'sku_provider' => 'required',
        'bar_code' => 'required',
        'method_of_payment' => 'required',
        'condition'=> 'required',
        'currency' => 'required',
        'cost_per_unit' => 'required',
        'cost_per_package' => 'required',
        'sugessted_price' => 'required',
        'provider_id' => 'required',
    ];

    public $errorSkuProvider = 'Sku Provider already exists';

    public function index(Request $request, ProductProvider $supplier_id){

        $approved = $request->query('approved');
        $commercialized = $request->query('commercialized');

        if($approved){
            $pivot = ProductProvider::where('provider_id', $supplier_id->id)->where('approved', $approved ==='true' ? 1 : 0 )->get();
        } else if($commercialized){
            $pivot = ProductProvider::where('provider_id', $supplier_id->id)->where('commercialized', $commercialized ==='true' ? 1 : 0 )->get();
        } else {
            $pivot = ProductProvider::where('provider_id', $supplier_id->id)->get();
        }

        if(!empty($pivot)){
            $productsProvider = $pivot->map(function ($element) {
                return  $element->product_id;
            });
            $products = ProductProviderResource::collection(Product::whereIn('id', $productsProvider)->get());
            return $this->paginate($products);
        }else {
            return $this->errorResponse('No products found for this supplier', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Provider $supplier_id)
    {
        $validate = Validator::make($request->all(), $this->rules);

        if ($this->existSku($request->sku_provider)) {
            return $this->errorResponse($this->errorSkuProvider, Response::HTTP_BAD_REQUEST);
        }

        $provider = Provider::where('id', $supplier_id->id)->get();

        if ($validate->fails()) {
            return $this->errorResponse($validate, Response::HTTP_BAD_REQUEST);
        }

        if(!empty($provider)){
            $product = Product::create(
                $request->all()
            );

            $productsProvider = ProductProvider::create([
                'product_id' => $product->id,
                'provider_id' => $request->supplier_id->id,
            ]);
            return $this->showOne($product, 201);
        }else {
            return $this->errorResponse($validate->errors(), Response::HTTP_BAD_REQUEST);
        }
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request, Provider $supplier_id)
    {
        $validate = Validator::make($request->all(), ['import' => 'required']);
        if ($validate->fails()) {
            return $this->errorResponse($validate, Response::HTTP_BAD_REQUEST);
        }
        $fileProducts = $request->file('import');
        // $supplier_id->products()->delete();
        try{
            Excel::import(new ProductProviderImport($supplier_id), $fileProducts);
        }
        catch(\Exception $e){
            error_log("holaa");
            error_log($e);
            return $this->errorResponse("formato no valido", Response::HTTP_BAD_REQUEST);
        }
      
    }

  
   

    public function existSku($sku)
    {
        return Product::where('sku_provider', $sku)->first();
    }
}
