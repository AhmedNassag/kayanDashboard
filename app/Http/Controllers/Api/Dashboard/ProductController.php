<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\MeasurementUnit;
use App\Models\Media;
use App\Models\Product;
use App\Models\SellingMethod;
use App\Models\Tax;
use App\Models\Unit;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
            $products = Product::
            with('company:id,name','category:id,name','tax:id,name')
            ->when($request->search,function ($q) use ($request){

                return $q->where('name','like',"%". $request->search ."%");
            })->latest()->paginate(15);

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
            ])->with('mainMeasurementUnit','subMeasurementUnit','company')->get();

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

        public function activationProduct($id)
        {
            $product = Product::find($id);

            if ($product->status == 1)
            {
                $product->update([
                    "status" => 0
                ]);
            }else{
                $product->update([
                    "status" => 1
                ]);
            }
            return $this->sendResponse([], 'Data exited successfully');
        }

        public function create()
        {
            try {

                $companies = Company::select('id','name')->get();
                $categories = Category::where('parent_id',0)->select('id','name')->get();
                $measures = Unit::select('id','name')->get();
                $tax = Tax::select('id','name')->get();
                // $sellingMethods = SellingMethod::select('id','name')->get();

                return $this->sendResponse([
                    'companies' => $companies,
                    'categories' => $categories,
                    'measures' => $measures,
                    'taxes' => $tax,
                    // 'sellingMethods' => $sellingMethods
                ], 'Data exited successfully');

            } catch (\Exception $e) {

                return $this->sendError('An error occurred in the system');

            }
        }


        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            try {
                DB::beginTransaction();

                // Validator request
                $v = Validator::make($request->all(), [
                    'name'                      => 'required|unique:products',
                    'max_order'                 => 'required|integer',
                    'barcode'                   => 'required|integer|unique:products,barcode',
                    'description'               => 'nullable',
                    'image'                     => 'required|file|mimes:png,jpg,jpeg',
                    'files'                     => 'required|array',
                    'files.*'                   => 'required|file|mimes:png,jpg,jpeg',
                    'category_id'               => 'required|integer|exists:categories,id',
                    'sub_category_id'           => 'required|integer|exists:categories,id',
                    'company_id'                => 'required|integer|exists:companies,id',
                    'main_measurement_unit_id'  => 'required|integer|exists:units,id',
                    'sub_measurement_unit_id'   => 'required|integer|exists:units,id',
                    'count_unit'                 => 'required|integer',
                    'tax_id'                    => 'required|integer|exists:taxes,id',
                    'selling_method'            => 'required',
                    'selling_method.*'          => 'required|exists:selling_methods,id',
                ]);

                if ($v->fails()) {
                    return $this->sendError('There is an error in the data', $v->errors());
                }

                $image = time().'.'. $request->image->getClientOriginalName();

                // picture move
                $request->image->storeAs('product', $image,'general');

                $product = Product::create([
                    'name' => $request->name,
                    'max_order' => $request->max_order,
                    'barcode' => $request->barcode,
                    'description' => $request->description,
                    'image' => $image,
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'company_id' => $request->company_id,
                    'main_measurement_unit_id' => $request->main_measurement_unit_id,
                    'sub_measurement_unit_id' => $request->sub_measurement_unit_id,
                    'count_unit' => $request->count_unit,
                    'tax_id' => $request->tax_id
                ]);

                $imageProduct = explode(',',$request->selling_method[0]);

                $product->selling_method()->attach($imageProduct);

                $i = 0;
                if($request->hasFile('files')){
                    foreach($request->file('files') as $index => $file){

                        $file_size = $file->getSize();
                        $file_type = $file->getMimeType();
                        $image = time().$i.'.'. $file->getClientOriginalName();

                        // picture move
                        $file->storeAs('product', $image,'general');

                        $product->media()->create([
                            'file_name' => $image ,
                            'file_size' => $file_size,
                            'file_type' => $file_type,
                            'file_sort' => $i
                        ]);

                        $i++;
                    }
                }

                DB::commit();

                return $this->sendResponse([], 'Data exited successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError('An error occurred in the system');
            }
        }


        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            try {

                $product = Product::with('media:mediable_id,file_name,id')->find($id);
                $companies = Company::select('id','name')->get();
                $categories = Category::where('parent_id',0)->select('id','name')->get();
                $measures = Unit::select('id','name')->get();
                $tax = Company::select('id','name')->get();
                $sellingMethods = SellingMethod::select('id','name')->get();
                $sellingMethodChange = $product->selling_method;

                return $this->sendResponse([
                    'product' => $product,
                    'companies' => $companies,
                    'categories' => $categories,
                    'measures' => $measures,
                    'taxes' => $tax,
                    'sellingMethods' => $sellingMethods,
                    'sellingMethodChange' => $sellingMethodChange
                ], 'Data exited successfully');

            } catch (\Exception $e) {

                return $this->sendError('An error occurred in the system');

            }
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $product = Product::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name'                      => 'required|unique:products,name,'.$product->id,
                'max_order'                 => 'required|integer',
                'barcode'                   => 'required|integer|unique:products,barcode,'.$product->id,
                'description'               => 'nullable',
                'image'                     => 'nullable'.($request->hasFile('image')?'|file|mimes:jpeg,jpg,png':''),
                'files'                     => 'nullable',
                'files.*'                   => 'nullable'.($request->hasFile('files')?'|file|mimes:jpeg,jpg,png':''),
                'category_id'               => 'required|integer|exists:categories,id',
                'sub_category_id'           => 'required|integer|exists:categories,id',
                'company_id'                => 'required|integer|exists:companies,id',
                'main_measurement_unit_id'  => 'required|integer|exists:units,id',
                'sub_measurement_unit_id'   => 'required|integer|exists:units,id',
                'count_unit'                => 'required|integer',
                'tax_id'                    => 'required|integer|exists:taxes,id',
                'selling_method'            => 'required',
                'selling_method.*'          => 'required|exists:selling_methods,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data['name'] = $request->name;
            $data['max_order'] = $request->max_order;
            $data['barcode'] = $request->barcode;
            $data['description'] = $request->description;
            if($request->hasFile('image')){
                if(File::exists('upload/product/'. $product->image)){
                    unlink('upload/product/'. $product->image);
                }
                $image = time().'.'. $request->image->getClientOriginalName();
                $request->image->storeAs('product', $image,'general');
                $data['image'] = $image;
            }
            $data['category_id'] = $request->category_id;
            $data['sub_category_id'] = $request->sub_category_id;
            $data['company_id'] = $request->company_id;
            $data['tax_id'] = $request->tax_id;
            $data['main_measurement_unit_id'] = $request->main_measurement_unit_id;
            $data['sub_measurement_unit_id'] = $request->sub_measurement_unit_id;
            $data['count_unit'] = $request->count_unit;


            $product->update($data);

            $imageProduct = explode(',',$request->selling_method[0]);

            $product->selling_method()->attach($imageProduct);

            $i = 0;
            if($request->hasFile('files')){
                foreach($request->file('files') as $index => $file){

                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time().$i.'.'. $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image,'general');

                    $product->media()->create([
                        'file_name' => $image ,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);

                    $i++;
                }
            }

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


        public function deleteOne(Request $request,$id)
        {
            try {
                $product = Product::find($id);
                if ($product){

                    $media = Media::find($request->idMedia);

                    if(File::exists('upload/product/'. $media->file_name)){
                        unlink('upload/product/'. $media->file_name);
                    }

                    $media->delete();
                    return $this->sendResponse([],'Deleted successfully');
                }else{
                    return $this->sendError('ID is not exist');
                }

            }catch (\Exception $e){
                return $this->sendError('An error occurred in the system');
            }
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            try {
                $Product = Product::find($id);
                if ($Product){

                    if(File::exists('upload/product/'. $Product->image)){
                        unlink('upload/product/'. $Product->image);
                    }

                    foreach ($Product->media as $item){
                        if(File::exists('upload/product/'.$item->file_name)){
                            unlink('upload/product/'. $item->file_name);
                        }
                        $item->delete();
                    }

                    $Product->delete();
                    return $this->sendResponse([],'Deleted successfully');
                }else{
                    return $this->sendError('ID is not exist');
                }

            }catch (\Exception $e){
                return $this->sendError('An error occurred in the system');
            }
        }

        //
        public function getCategories()
        {
            $categories = Category::all();
            return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
        }

}

// namespace App\Http\Controllers\Api\Dashboard;

// use App\Http\Controllers\Controller;
// use App\Models\Category;
// use App\Models\Company;
// use Illuminate\Http\Request;
// use App\Models\Product;
// use App\Models\ProductName;
// use App\Models\SubCategory;
// use App\Models\Tax;
// use App\Models\Unit;
// use App\Traits\Message;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Validator;

// class ProductController extends Controller
// {
//     use Message;

//     public function index(Request $request)
    // {
    //     $products = Product::with('media:file_name,mediable_id')->with('productName')->with('category')->with('subCategory')->with('company')->with('supplier')->with('tax')->with('unit')->with('mainMeasurementUnit')
    //         ->when($request->search, function ($q) use ($request) {
    //         return $q->where('name', 'like', '%' . $request->search . '%');
    //     })->latest()->paginate(10);
    //     foreach($products as $product)
    //     {
    //         $product->setAttribute('added_at',$product->created_at->format('Y-m-d'));
    //     }

    //     return $this->sendResponse(['products' => $products], 'Data exited successfully');
    // }


    // public function activationProduct($id)
    // {
    //     $product = Product::find($id);

    //     if ($product->status == 1)
    //     {
    //         $product->update([
    //             "status" => 0
    //         ]);
    //     }else{
    //         $product->update([
    //             "status" => 1
    //         ]);
    //     }
    //     return $this->sendResponse([], 'Data exited successfully');
    // }


    // public function create()
    // {
    //     //
    // }


    // public function store(Request $request)
    // {
    //     // try {
    //         DB::beginTransaction();

    //         // Validator request
    //         $v = Validator::make($request->all(), [
    //             'description' => 'required',
    //             'charge' => 'required',
    //             'maxMount' => 'required',
    //             'barCode' => 'required',
    //             'file' => 'required|file|mimes:png,jpg,jpeg',
    //             'productName_id' => 'required',
    //             'category_id' => 'required',
    //             'sub_category_id' => 'required',
    //             // 'company_id' => 'required',
    //             // 'supplier_id' => 'required',
    //             'tax_id' => 'required',
    //             'main_measurement_unit_id' => 'required',
    //             'saleMethods' => 'required',
    //         ]);

    //         if ($v->fails()) {
    //             return $this->sendError('There is an error in the data', $v->errors());
    //         }
    //         $data = $request->only(['description','charge','maxMount','barCode','productName_id','category_id','sub_category_id','company_id','supplier_id','tax_id','main_measurement_unit_id','saleMethods']);
    //         if($data['supplier_id'] != "null")
    //         {
    //             //unset($data['company_id']);
    //             $data['company_id'] = null;
    //         }
    //         else
    //         {
    //             //unset($data['supplier_id']);
    //             $data['supplier_id'] = null;
    //         }


    //         $product = Product::create($data);

    //         if($request->hasFile('file')){
    //             $file_size = $request->file->getSize();
    //             $file_type = $request->file->getMimeType();
    //             $image = time().'.'. $request->file->getClientOriginalName();

    //             // picture move
    //             $request->file->storeAs('product', $image,'general');

    //             $product->media()->create([
    //                 'file_name' => $image ,
    //                 'file_size' => $file_size,
    //                 'file_type' => $file_type,
    //                 'file_sort' => 1
    //             ]);

    //         }

    //         DB::commit();

    //         return $this->sendResponse([], 'Data exited successfully');

    //     // } catch (\Exception $e) {
    //     //     DB::rollBack();
    //     //     return $this->sendError('An error occurred in the system');
    //     // }
    // }


    // public function show($id)
    // {
    //     //
    // }


    // public function edit($id)
    // {
    //     try {

    //         $product = Product::with('media:file_name,mediable_id')->find($id);

    //         return $this->sendResponse(['product' => $product], 'Data exited successfully');

    //     } catch (\Exception $e) {

    //         return $this->sendError('An error occurred in the system');

    //     }
    // }


    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();
    //     // try {

    //         $product = Product::find($id);

    //         // Validator request
    //         $v = Validator::make($request->all(), [
    //             'description' => 'required',
    //             'charge' => 'required',
    //             'maxMount' => 'required',
    //             'barCode' => 'required',
    //             'file' => 'nullable'.($request->hasFile('file')?'|file|mimes:jpeg,jpg,png':''),
    //             'productName_id' => 'required',
    //             'category_id' => 'required',
    //             'sub_category_id' => 'required',
    //             // 'company_id' => 'required',
    //             // 'supplier_id' => 'required',
    //             'tax_id' => 'required',
    //             'main_measurement_unit_id' => 'required',
    //             'saleMethods' => 'required',

    //         ]);

    //         if ($v->fails()) {
    //             return $this->sendError('There is an error in the data', $v->errors());
    //         }

    //         $data = $request->only(['description','charge','maxMount','barCode','productName_id','category_id','sub_category_id','company_id','supplier_id','tax_id','main_measurement_unit_id','saleMethods']);
    //         if($data['supplier_id'] != "null")
    //         {
    //             //unset($data['company_id']);
    //             $data['company_id'] = null;
    //         }
    //         else
    //         {
    //             //unset($data['supplier_id']);
    //             $data['supplier_id'] = null;
    //         }

    //         $product->update($data);

    //         if($request->hasFile('file')){

    //             if(File::exists('upload/product/'.$product->media->file_name)){
    //                 unlink('upload/product/'. $product->media->file_name);
    //             }
    //             $product->media->delete();

    //             $file_size = $request->file->getSize();
    //             $file_type = $request->file->getMimeType();
    //             $image = time().'.'. $request->file->getClientOriginalName();

    //             // picture move
    //             $request->file->storeAs('product', $image,'general');

    //             $product->media()->create([
    //                 'file_name' => $image ,
    //                 'file_size' => $file_size,
    //                 'file_type' => $file_type,
    //                 'file_sort' => 1
    //             ]);

    //         }

    //         DB::commit();
    //         return $this->sendResponse([],'Data exited successfully');
    //     // }catch (\Exception $e){

    //     //     DB::rollBack();
    //     //     return $this->sendError('An error occurred in the system');
    //     // }
    // }


    // public function destroy($id)
    // {
    //     try {
    //         $product = Product::find($id);
    //         if ($product){

    //             if(File::exists('upload/product/'. $product->media->file_name)){
    //                 unlink('upload/product/'. $product->media->file_name);
    //             }
    //             $product->media->delete();

    //             $product->delete();
    //             return $this->sendResponse([],'Deleted successfully');
    //         }else{
    //             return $this->sendError('ID is not exist');
    //         }

    //     }catch (\Exception $e){
    //         return $this->sendError('An error occurred in the system');
    //     }
    // }


    // //start relations functions
    // public function getProductNames()
    // {
    //     $productNames = ProductName::all();
    //     return $this->sendResponse(['productNames' => $productNames], 'Data exited successfully');
    // }

    // public function getCategories()
    // {
    //     $categories = Category::all();
    //     return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
    // }

    // public function getSubCategories()
    // {
    //     $subCategories = SubCategory::all();
    //     return $this->sendResponse(['subCategories' => $subCategories], 'Data exited successfully');
    // }

    // public function getCompanies()
    // {
    //     $companies = Company::all();
    //     return $this->sendResponse(['companies' => $companies], 'Data exited successfully');
    // }

    // public function getTaxes()
    // {
    //     $taxes = Tax::all();
    //     return $this->sendResponse(['taxes' => $taxes], 'Data exited successfully');
    // }

    // public function getUnits()
    // {
    //     $units = Unit::all();
    //     return $this->sendResponse(['units' => $units], 'Data exited successfully');
    // }


    // public function purchaseInvoiceProduct(Request $request)
    // {
    //     $products = Product::where([
    //         ['status', 1],
    //         ['category_id', $request->category_id],
    //         ['sub_category_id', $request->sub_category_id]
    //     ])->with('mainMeasurementUnit','subMeasurementUnit','company')->get();

    //     return $this->sendResponse(['products' => $products], 'Data exited successfully');
    // }
// }
