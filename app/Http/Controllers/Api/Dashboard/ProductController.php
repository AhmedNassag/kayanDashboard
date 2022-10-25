<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativeDetail;
use App\Models\Category;
use App\Models\Client;
use App\Models\Media;
use App\Models\PharmacistForm;
use App\Models\Product;
use App\Models\SellingMethod;
use App\Models\SubCategory;
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
        $products = Product::where('status',1)->with('category:id,name', 'subCategory:id,name', 'tax:id,name', 'pharmacistForm:id,name')
            ->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', "%" . $request->search . "%");
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
        ])->with('mainMeasurementUnit', 'subMeasurementUnit')->get();

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
        try {
            $categories      = Category::where('status',1)->select('id', 'name')->get();
            $measures        = Unit::select('id', 'name')->get();
            $tax             = Tax::where('status',1)->select('id', 'name')->get();
            $sellingMethods  = SellingMethod::where('status',1)->select('id', 'name')->get();
            $pharmacistForms = PharmacistForm::select('id', 'name')->get();
            $alternatives    = Alternative::select('id', 'nameAr')->get();
            $clients         = Client::with('user')->get();

            return $this->sendResponse([
                'categories'      => $categories,
                'measures'        => $measures,
                'taxes'           => $tax,
                'sellingMethods'  => $sellingMethods,
                'pharmacistForms' => $pharmacistForms,
                'alternatives'    => $alternatives,
                'clients'         => $clients
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
                'nameAr'                   => 'required|unique:products,nameAr',
                'nameEn'                   => 'required|unique:products,nameEn',
                'description'              => 'nullable',
                'effectiveMaterial'        => 'required',
                'barcode'                  => 'required|integer|unique:products,barcode',
                'maximum_product'          => 'required|integer',
                'Re_order_limit'           => 'required|integer',
                'image'                    => 'required|file|mimes:png,jpg,jpeg',
                'pharmacistForm_id'        => 'required|exists:pharmacist_forms,id',
                'category_id'              => 'required|integer|exists:categories,id',
                'sub_category_id'          => 'required|integer|exists:sub_categories,id',
                'tax_id'                   => 'required|integer|exists:taxes,id',
                'main_measurement_unit_id' => 'required|integer|exists:units,id',
                'files'                    => 'required|array',
                'files.*'                  => 'required|file|mimes:png,jpg,jpeg',
                'selling_methods'          => 'required',
                'selling_methods.*'        => 'required|exists:selling_methods,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $image = time() . '.' . $request->image->getClientOriginalName();

            // picture move
            $request->image->storeAs('product', $image, 'general');

            $data = $request->only(['nameAr', 'nameEn', 'description', 'effectiveMaterial', 'barcode', 'maximum_product', 'Re_order_limit', 'image', 'category_id', 'sub_category_id', 'tax_id', 'main_measurement_unit_id', 'sub_measurement_unit_id', 'pharmacistForm_id', 'count_unit']);

            // $data['sub_measurement_unit_id'] = 1;

            $data['image'] = $image;

            $product = Product::create($data);

            $imageProduct = explode(',', $request->selling_methods[0]);
            $product->selling_methods()->attach($imageProduct);

            $i = 0;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {
                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time() . $i . '.' . $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image, 'general');
                    $product->media()->create([
                        'file_name' => $image,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);
                    $i++;
                }
            }

            if ($request->alternativeDetail) {
                $request->merge(['alternativeDetail' => json_decode($request->alternativeDetail)]);
                foreach ($request->alternativeDetail as $alternativeDetail) {
                    AlternativeDetail::create([
                        'product_id'     => $product['id'],
                        'alternative_id' => $alternativeDetail->alternative_id,
                        'discount'       => $alternativeDetail->discount,
                        'pharmacyPrice'  => $alternativeDetail->pharmacyPrice,
                        'publicPrice'    => $alternativeDetail->publicPrice,
                    ]);
                }
            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    public function show($id)
    {
        $products = Product::where('sub_category_id	', $id)->get();
        return $this->sendResponse(['products' => $products], 'Data exited successfully');
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
            $product         = Product::where('status',1)->with(['media:mediable_id,file_name,id', 'alternativeDetails'])->find($id);
            $categories      = Category::where('status',1)->select('id', 'name')->get();
            $measures        = Unit::select('id', 'name')->get();
            $taxes           = Tax::where('status',1)->select('id', 'name')->get();
            $pharmacistForms = PharmacistForm::select('id', 'name')->get();
            $sellingMethods  = SellingMethod::where('status',1)->select('id', 'name')->get();
            $alternatives    = Alternative::select('id', 'nameAr')->get();
            $sellingMethodChange = $product->selling_methods;

            return $this->sendResponse([
                'product'             => $product,
                'categories'          => $categories,
                'measures'            => $measures,
                'taxes'               => $taxes,
                'pharmacistForms'     => $pharmacistForms,
                'sellingMethods'      => $sellingMethods,
                'alternatives'        => $alternatives,
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
                'nameAr'                    => 'required',
                'nameEn'                    => 'required',
                'description'               => 'nullable',
                'effectiveMaterial'         => 'required',
                'barcode'                   => 'required|integer|unique:products,barcode,' . $product->id,
                'maximum_product'           => 'required|integer',
                'Re_order_limit'            => 'required|integer',
                'pharmacistForm_id'         => 'required|exists:pharmacist_forms,id',
                'category_id'               => 'required|integer|exists:categories,id',
                'sub_category_id'           => 'required|integer|exists:sub_categories,id',
                'tax_id'                    => 'required|integer|exists:taxes,id',
                'main_measurement_unit_id'  => 'required|integer|exists:units,id',
                // 'sub_measurement_unit_id'   => 'required|integer|exists:units,id',
                // 'count_unit'                => 'required|integer',

                'image'                     => 'nullable' . ($request->hasFile('image') ? '|file|mimes:jpeg,jpg,png' : ''),
                'files'                     => 'nullable',
                'files.*'                   => 'nullable' . ($request->hasFile('files') ? '|file|mimes:jpeg,jpg,png' : ''),
                'selling_methods'           => 'required',
                'selling_method.*'          => 'required|exists:selling_methods,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['nameAr','nameEn','description', 'effectiveMaterial', 'barcode', 'maximum_product', 'Re_order_limit', 'category_id', 'sub_category_id', 'tax_id', 'main_measurement_unit_id', 'pharmacistForm_id']);

            $product->update($data);

            if ($request->hasFile('image')) {
                if (File::exists('upload/product/' . $product->image)) {
                    unlink('upload/product/' . $product->image);
                }
                $image = time() . '.' . $request->image->getClientOriginalName();
                $request->image->storeAs('product', $image, 'general');
                $data['image'] = $image;
            }

            $imageProduct = explode(',', $request->selling_methods[0]);
            $product->selling_methods()->attach($imageProduct);

            $i = 0;
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $index => $file) {

                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $image = time() . $i . '.' . $file->getClientOriginalName();

                    // picture move
                    $file->storeAs('product', $image, 'general');
                    $product->media()->create([
                        'file_name' => $image,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'file_sort' => $i
                    ]);
                    $i++;
                }
            }

            if ($request->alternativeDetail != null) {
                $request->merge(['alternativeDetail' => json_decode($request->alternativeDetail)]);
                foreach ($request->alternativeDetail as $alternativeDetail) {
                    if ($alternativeDetail->alternative_id != null && $alternativeDetail->discount != null && $alternativeDetail->pharmacyPrice != null && $alternativeDetail->publicPrice != null) {
                        foreach ($product->alternativeDetails as $data) {
                            $data->delete();
                        }
                        AlternativeDetail::create([
                            'product_id'     => $product['id'],
                            'alternative_id' => $alternativeDetail->alternative_id,
                            'discount'       => $alternativeDetail->discount,
                            'pharmacyPrice'  => $alternativeDetail->pharmacyPrice,
                            'publicPrice'    => $alternativeDetail->publicPrice,
                        ]);
                    }
                }
            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
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
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }

    public function getCategories()
    {
        $categories = Category::where('status', 1)->get();
        return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
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
}
