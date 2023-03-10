<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\Category;
use App\Models\Company;
use App\Models\Media;
use App\Models\PharmacistForm;
use App\Models\Product;
use App\Models\ProductPricing;
use App\Models\PurchaseProduct;
use App\Models\SellingMethod;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\SubCategory;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with('category:id,name', 'tax:id,name','pharmacistForm:id,name')
        ->withSum(['cart_items as sold_quantity' => function ($query) {
            $query->whereHas('order' , function ($query) {
                $query->whereIn('order_status' , ['Pending','Shipping','Processing','Completed']);
            });
        }], 'quantity')
        ->where(function($q) use($request){
            $q->when($request->search,function($q) use($request) {
                $q->where('nameAr' ,'like',"%$request->search%")
                ->orWhere('nameEn' ,'like',"%$request->search%")
                ->orWhere('effectiveMaterial' ,'like',"%$request->search%")
                ->orWhere('barcode' ,'like',"%$request->search%")
                ->orWhere('product_code' ,'like',"%$request->search%");
            });
        })
        ->where(function($q) use($request){
            $q->when($request->category_id,function($q) use($request) {
                $q->whereRelation('category','id',$request->category_id);
            });
        });
        if($request->product_filter){
            $query->orderBy('sold_quantity',$request->product_filter == 'least_seller' ? 'asc' :'desc');
        }else{
            $query->latest();
        }
        $products = $query->paginate( $request->pagination ?? 25 );
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    /**
     * get Purchase Invoice Product
     */
    public function purchaseInvoiceProduct(Request $request)
    {
        $products = Product::where([
            ['status', 1],
            ['category_id', $request->category_id],
            ['sub_category_id', $request->sub_category_id]
        ])->with('mainMeasurementUnit', 'subMeasurementUnit', 'pharmacistForm')->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    /**
     * get active Product
     */
    public function activeProduct()
    {
        $products = Product::where('status', 1)->get();
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    /**
     * get mobile Product
     */
    public function mobileProduct()
    {
        $products = Product::where([
            ['status', 1],
            ['sell_app', 1],
        ])->whereHas('productPrice', function ($q) {
            $q->where('active', 1);
        })->whereHas('storeProducts', function ($q) {
            $q->where('sub_quantity_order', '>=', 1);
        })->with(['media', 'productPrice' => function ($q) {
            $q->where('active', 1);
            $q->with('sellingMethod', 'measurementUnit');
        }])->get();

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }

    public function activationProduct($id)
    {
        $product = Product::find($id);

        if ($product->status == 1) {
            $product->update([
                "status" => 0
            ]);
        } else {
            $product->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    public function create()
    {
        $pharmacistForms = PharmacistForm::select('id', 'name')->get();
        $categories = Category::where('status', 1)->select('id', 'name')->get();
        $companies = Company::get();
        $measures = Unit::select('id', 'name')->get();
        $sellingMethods = SellingMethod::select('id', 'name')->get();
        $stores = Store::where('status', 1)->get();
        $clients  = User::where('status', 1)->whereJsonContains('role_name', 'client')->get();

        return $this->sendResponse([
            'pharmacistForms' => $pharmacistForms,
            'categories' => $categories,
            'measures' => $measures,
            'sellingMethods' => $sellingMethods,
            'stores' => $stores,
            'companies' => $companies,
            'clients'         => $clients
        ], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            // Validator request
            $v = Validator::make($request->all(), [
                'nameAr' => 'required|unique:products',
                'nameEn' => 'required|unique:products',
                'effectiveMaterial' => 'nullable',
                'barcode' => 'nullable|unique:products,barcode',
                'product_code' => 'required|unique:products,product_code',
                // 'Re_order_limit' => 'required|numeric',
                // 'maximum_product' => 'required|numeric',
                'description' => 'nullable',
                'image' => 'nullable|file|mimes:png,jpg,jpeg',
                'files' => 'nullable|array',
                'files.*' => 'nullable|file|mimes:png,jpg,jpeg',
                'category_id' => 'required|integer|exists:categories,id',
                'company_id' => 'required|integer|exists:companies,id',
                'sub_category_id' => 'required|integer|exists:sub_categories,id',
                'pharmacistForm_id' => 'nullable',
                // 'main_measurement_unit_id' => 'required|integer|exists:units,id',
                // 'sub_measurement_unit_id' => 'required|integer|exists:units,id',
                // 'count_unit' => 'required|numeric',
                // 'selling_method' => 'required',
                // 'sell_app' => 'required',
                // 'selling_method.*' => 'required|exists:selling_methods,id',

                // 'quantity' => 'required|numeric',
                // 'sub_quantity' => 'required|numeric',
                // 'price' => 'required|numeric',
                // 'sub_price' => 'required|numeric',
                // 'store_id' => 'required|integer|exists:stores,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            if($request->image){
                $image = time() . '.' . $request->image->getClientOriginalName();

                // picture move
                $request->image->storeAs('product', $image, 'general');
            }


            $product = Product::create([
                'nameAr' => $request->nameAr,
                'nameEn' => $request->nameEn,
                'barcode' => $request->barcode,
                'product_code' => $request->product_code,
                'effectiveMaterial' => $request->effectiveMaterial,
                'description' => $request->description,
                // 'Re_order_limit' => $request->Re_order_limit,
                // 'maximum_product' => $request->maximum_product,
                'image' => $image,
                'category_id' => $request->category_id,
                'company_id' => $request->company_id,
                'sub_category_id' => $request->sub_category_id,
                'pharmacistForm_id' =>PharmacistForm::find($request->pharmacistForm_id)? $request->pharmacistForm_id : null,
                // 'main_measurement_unit_id' => $request->main_measurement_unit_id,
                // 'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
                // 'count_unit' => $request->count_unit,
                // 'sell_app' => $request->sell_app,
            ]);

            // $imageProduct = explode(',', $request->selling_method);

            // $product->selling_method()->attach($imageProduct);
            // foreach ($imageProduct as $item) {

            //     ProductPricing::create([
            //         'product_id' => $product->id,
            //         'selling_method_id' => $item,
            //         'measurement_unit_id' => $request->main_measurement_unit_id
            //     ]);
            //     ProductPricing::create([
            //         'product_id' => $product->id,
            //         'selling_method_id' => $item,
            //         'measurement_unit_id' => $request->sub_measurement_unit_id
            //     ]);
            // }

            $i = 0;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {

                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time() . $i . '.' . $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image, 'general');

                    $product->media()->create([
                        'file_name' => asset('upload/product/' . $image),
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);

                    $i++;
                }
            }

            // StoreProduct::create([
            //     'product_id' => $product->id,
            //     'main_measurement_unit_id' => $request->main_measurement_unit_id,
            //     'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
            //     'sub_category_id' => $request->sub_category_id,
            //     'store_id' => $request['store_id'],
            //     'quantity' => $request['quantity'],
            //     'sub_quantity' => $request['sub_quantity'],
            //     'count_unit' => $request['count_unit'],
            //     'sub_quantity_order' => 0,
            // ]);

            // PurchaseProduct::create([
            //     'product_id' => $product->id,
            //     'quantity' => $request['quantity'],
            //     'sub_quantity' => $request['sub_quantity'],
            //     'price' => $request['price'],
            //     'count_unit' => $request['count_unit'],
            // ]);

            $this->associateAlternativeProducts($request,$product);


            return $this->sendResponse([], 'Data exited successfully');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('media:mediable_id,file_name,id','related:id,image,nameAr','company')->find($id);
        $pharmacistForms = PharmacistForm::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        $companies = Company::get();
        // $measures = Unit::select('id', 'name')->get();
        // $sellingMethods = SellingMethod::select('id', 'name')->get();
        // $sellingMethodChange = $product->selling_method;
        // $stores = Store::where('status', 1)->get();
        // $storeProduct = $product->storeProducts()->first();
        // $purchaseProducts = $product->purchaseProducts()->first();

        return $this->sendResponse([
            'product' => $product,
            'pharmacistForms' => $pharmacistForms,
            'categories' => $categories,
            'companies' => $companies,
            // 'measures' => $measures,
            // 'sellingMethods' => $sellingMethods,
            // 'sellingMethodChange' => $sellingMethodChange,
            // 'stores' => $stores,
            // 'storeProduct' => $storeProduct,
            // 'purchaseProducts' => $purchaseProducts,
        ], 'Data exited successfully');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


            $product = Product::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'nameAr' => 'required|unique:products,nameAr,' . $product->id,
                'nameEn' => 'required|unique:products,nameEn,' . $product->id,
                'effectiveMaterial' => 'nullable',
                // 'Re_order_limit' => 'required|numeric',
                // 'maximum_product' => 'required|numeric',
                'barcode' => 'nullable|unique:products,barcode,' . $product->id,
                'product_code' => 'required|unique:products,product_code,' . $product->id,
                'description' => 'nullable',
                'image' => 'nullable' . ($request->hasFile('image') ? '|file|mimes:jpeg,jpg,png' : ''),
                'files' => 'nullable',
                'files.*' => 'nullable' . ($request->hasFile('files') ? '|file|mimes:jpeg,jpg,png' : ''),
                'category_id' => 'required|exists:categories,id',
                'company_id' => 'required|exists:companies,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
                'pharmacistForm_id' => 'nullable',
                // 'main_measurement_unit_id' => 'required|integer|exists:units,id',
                // 'sub_measurement_unit_id' => 'required|integer|exists:units,id',
                // 'count_unit' => 'required|numeric',
                // 'selling_method' => 'required',
                // 'sell_app' => 'required',
                // 'selling_method.*' => 'required|exists:selling_methods,id',

                // 'quantity' => 'required|numeric',
                // 'sub_quantity' => 'required|numeric',
                // 'price' => 'required|numeric',
                // 'sub_price' => 'required|numeric',
                // 'store_id' => 'required|integer|exists:stores,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data['nameAr'] = $request->nameAr;
            $data['nameEn'] = $request->nameEn;
            $data['effectiveMaterial'] = $request->effectiveMaterial;
            // $data['Re_order_limit'] = $request->Re_order_limit;
            // $data['maximum_product'] = $request->maximum_product;
            $data['barcode'] = $request->barcode != "null" ? $request->barcode : $product->barcode;
            $data['product_code'] = $request->product_code != "null" ? $request->product_code : $product->product_code;
            $data['description'] = $request->description;
            if ($request->hasFile('image') ) {
                if (File::exists('upload/product/' . $product->image) && $product->image) {
                    unlink('upload/product/' . $product->image);
                }
                $image = time() . '.' . $request->image->getClientOriginalName();
                $request->image->storeAs('product', $image, 'general');
                $data['image'] = $image;
            }
            $data['category_id'] = $request->category_id;
            $data['company_id'] = $request->company_id;
            $data['sub_category_id'] = $request->sub_category_id;
            $data['pharmacistForm_id'] = PharmacistForm::find($request->pharmacistForm_id)? $request->pharmacistForm_id : null;
            // $data['main_measurement_unit_id'] = $request->main_measurement_unit_id;
            // $data['sub_measurement_unit_id'] = $request->sub_measurement_unit_id;
            // $data['count_unit'] = $request->count_unit;
            // $data['sell_app'] = $request->sell_app;


            $product->update($data);

            // $imageProduct = explode(',', $request->selling_method);

            // $product->selling_method()->sync($imageProduct);

            // foreach ($imageProduct as $item) {

            //     $ProductPricing = ProductPricing::where([
            //         ['product_id', $id],
            //         ['selling_method_id', $item],
            //     ])->get();
            //     if (count($ProductPricing) == 0) {
            //         ProductPricing::create([
            //             'product_id' => $id,
            //             'selling_method_id' => $item,
            //             'measurement_unit_id' => $request->main_measurement_unit_id
            //         ]);
            //         ProductPricing::create([
            //             'product_id' => $id,
            //             'selling_method_id' => $item,
            //             'measurement_unit_id' => $request->sub_measurement_unit_id
            //         ]);
            //     } else {
            //         foreach ($ProductPricing as $index => $value) {
            //             if ($index == 0) {
            //                 $value->update([
            //                     'measurement_unit_id' => $request->main_measurement_unit_id
            //                 ]);
            //             } else {
            //                 $value->update([
            //                     'measurement_unit_id' => $request->sub_measurement_unit_id
            //                 ]);
            //             }
            //         }
            //     }
            // }

            $i = 0;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {

                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time() . $i . '.' . $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image, 'general');

                    $product->media()->create([
                        'file_name' => asset('upload/product/' . $image),
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);

                    $i++;
                }
            }

            // $store_product = StoreProduct::where('product_id', $id)->first();
            // $store_product->update([
            //     'main_measurement_unit_id' => $request->main_measurement_unit_id,
            //     'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
            //     'sub_category_id' => $request->sub_category_id,
            //     'store_id' => $request['store_id'],
            //     'quantity' => $request['quantity'],
            //     'sub_quantity' => $request['sub_quantity'],
            //     'count_unit' => $request['count_unit'],
            //     'sub_quantity_order' => 0,
            // ]);

            // $purchase_product = PurchaseProduct::where('product_id', $id)->first();
            // $purchase_product->update([
            //     'quantity' => $request['quantity'],
            //     'sub_quantity' => $request['sub_quantity'],
            //     'price' => $request['price'],
            //     'count_unit' => $request['count_unit'],
            // ]);
            $this->associateAlternativeProducts($request,$product);


            return $this->sendResponse([], 'Data exited successfully');

    }



    public function deleteOne(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if ($product) {

                $media = Media::find($request->idMedia);

                if (File::exists('upload/product/' . $media->file_name)) {
                    unlink('upload/product/' . $media->file_name);
                }

                $media->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $Product = Product::find($id);
            if ($Product) {

                if (File::exists('upload/product/' . $Product->image)) {
                    unlink('upload/product/' . $Product->image);
                }

                foreach ($Product->media as $item) {
                    if (File::exists('upload/product/' . $item->file_name)) {
                        unlink('upload/product/' . $item->file_name);
                    }
                    $item->delete();
                }

                $Product->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }


    //
    public function alternativeProduct(Request $request)
    {
        $alternatives = Alternative::where([
            ['status', 1],
            ['category_id', $request->category_id],
            ['sub_category_id', $request->sub_category_id]
        ])->get();
        return $this->sendResponse(['alternatives' => $alternatives], 'Data exited successfully');
    }

    public function show($id)
    {
        $products = Product::where('sub_category_id	', $id)->with('related:id,image,nameAr')->get();
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }
    public function getCategories()
    {
        $categories = Category::where('status', 1)->get();
        $companies = Company::get();
        return $this->sendResponse(['categories' => $categories,'companies' => $companies], 'Data exited successfully');
    }

    public function getSubCategories()
    {
        $subCategories = SubCategory::where('status', 1)->get();
        return $this->sendResponse(['subCategories' => $subCategories], 'Data exited successfully');
    }

    public function getTaxes()
    {
        $taxes = Tax::where('status', 1)->get();
        return $this->sendResponse(['taxes' => $taxes], 'Data exited successfully');
    }

    public function getUnits()
    {
        $units = Unit::get();
        return $this->sendResponse(['units' => $units], 'Data exited successfully');
    }

    public function getAlternativesProducts(Request $request)
    {
        $alternatives = Product::where(function ($q) use ($request) {
                $q->when($request->altr_search, function ($q) use ($request) {
                    $q->where('nameAr', 'like', "%$request->altr_search%")
                    ->orWhere('nameEn', 'like', "%$request->altr_search%")
                    ->orWhere('effectiveMaterial', 'like', "%$request->altr_search%")
                    ->orWhere('description', 'like', "%$request->altr_search%")
                    ->orWhere('barcode', 'like', "%$request->altr_search%");
                });
            })
            ->where(function ($q) use ($request) {
                $q->when($request->product_id, function ($q) use ($request) {
                    $q->where('id', '!=', $request->product_id);
                });
            })
            ->select('id', 'nameAr', 'image')->latest()->take(10)->get();

        return response()->json(['alternatives' => $alternatives]);
    }

    public function associateAlternativeProducts($request, $product)
    {
        $request->merge(['alternativeDetail' => json_decode($request->alternativeDetail)]);
        $array=collect($request->alternativeDetail)->where('alternative_id','!=',$product->id)->unique('alternative_id')->pluck('alternative_id')->filter()->toArray();
        if ($array && $request->alternativeDetail && $request-> alternativeDetail != Null) {
            $product->related()->sync($array);
        }
    }
}
