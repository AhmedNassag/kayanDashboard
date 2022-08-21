<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Tax;
use App\Models\Unit;
use App\Traits\Message;
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
        $products = Product::with('media:file_name,mediable_id')
            ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        foreach($products as $product)
        {
            $product->setAttribute('added_at',$product->created_at->format('Y-m-d'));
        }

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'name' => 'required',
                'description' => 'required',
                'charge' => 'required',
                'maxMount' => 'required',
                'barCode' => 'required',
                'file' => 'required|file|mimes:png,jpg,jpeg',
                'cat_id' => 'required',
                'sub_category_id' => 'required',
                'company_id' => 'required',
                'tax_id' => 'required',
                'unit_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','description','charge','maxMount','barCode','cat_id','sub_category_id','company_id','tax_id','unit_id']);

            $product = Product::create($data);

            if($request->hasFile('file')){

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('product', $image,'general');

                $product->media()->create([
                    'file_name' => $image ,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);

            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

            $product = Product::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['product' => $product], 'Data exited successfully');

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
                'name' => 'required|string',
                'description' => 'required',
                'charge' => 'required',
                'maxMount' => 'required',
                'barCode' => 'required',
                'file' => 'nullable'.($request->hasFile('file')?'|file|mimes:jpeg,jpg,png':''),
                'cat_id' => 'required',
                'sub_category_id' => 'required',
                'company_id' => 'required',
                'tax_id' => 'required',
                'unit_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','description','charge','maxMount','barCode','cat_id','sub_category_id','company_id','tax_id','unit_id','status']);

            $product->update($data);

            if($request->hasFile('file')){

                if(File::exists('upload/product/'.$product->media->file_name)){
                    unlink('upload/product/'. $product->media->file_name);
                }
                $product->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('product', $image,'general');

                $product->media()->create([
                    'file_name' => $image ,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);

            }

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
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
            $product = Product::find($id);
            if ($product){

                if(File::exists('upload/product/'. $product->media->file_name)){
                    unlink('upload/product/'. $product->media->file_name);
                }
                $product->media->delete();

                $product->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }


    //start relations functions
    public function getCategories()
    {
        $categories = Category::all();
        return $this->sendResponse(['categories' => $categories], 'Data exited successfully');
    }

    public function getSubCategories()
    {
        $subCategories = SubCategory::all();
        return $this->sendResponse(['subCategories' => $subCategories], 'Data exited successfully');
    }

    public function getCompanies()
    {
        $companies = Company::all();
        return $this->sendResponse(['companies' => $companies], 'Data exited successfully');
    }

    public function getTaxes()
    {
        $taxes = Tax::all();
        return $this->sendResponse(['taxes' => $taxes], 'Data exited successfully');
    }

    public function getUnits()
    {
        $units = Unit::all();
        return $this->sendResponse(['units' => $units], 'Data exited successfully');
    }
}
