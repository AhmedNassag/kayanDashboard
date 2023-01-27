<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories_query = Category::with('media:file_name,mediable_id')
        ->withSum(['cart_items as sold_quantity' => function ($query) {
            $query->whereHas('order' , function ($query) {
                $query->whereIn('order_status' , ['Pending','Shipping','Processing','Completed']);
            });
        }], 'quantity')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('code', 'like', '%' . $request->search . '%');
        });
        if($request->product_filter){
            $categories_query->orderBy('sold_quantity',$request->product_filter == 'least_seller' ? 'asc' :'desc');
        }else{
            $categories_query->latest();
        }
        $categories = $categories_query->paginate(10);

        $activeCategories = Category::where('status', 1)->get();
        $notActiveCategories = Category::where('status', 0)->get();
        $products = Product::count();
        return $this->sendResponse(['categories' => $categories,'activeCategories' => $activeCategories,'notActiveCategories' => $notActiveCategories, 'products' => $products], 'Data exited successfully');
    }


    public function activationCategory($id)
    {
        $department = Category::find($id);

        if ($department->status == 1)
        {
            $department->update
            ([
                "status" => 0
            ]);
        }
        else
        {
            $department->update
            ([
                "status" => 1
            ]);
        }

        $activeCategories = Category::where('status', 1)->get();
        $notActiveCategories = Category::where('status', 0)->get();
        return $this->sendResponse(['activeCategories' => $activeCategories,'notActiveCategories' => $notActiveCategories], 'Data exited successfully');
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
                'name' => 'required|unique:categories,name',
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name']);
            $data['code'] = rand();
            $category = Category::create($data);

            if($request->hasFile('file')){

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('category', $image,'general');

                $category->media()->create([
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


    public function show($id)
    {
        $subCategories = SubCategory::where('category_id',$id)->get();
        return $this->sendResponse(['subCategories' => $subCategories], 'Data exited successfully');
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

            $category = Category::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['category' => $category], 'Data exited successfully');

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


            $category = Category::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'file' => 'nullable'.($request->hasFile('file')?'|file':''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','status']);

            $category->update($data);

            if($request->hasFile('file')){

                if($category->media && File::exists('upload/category/'.$category->media->file_name)){
                    unlink('upload/category/'. $category->media->file_name);
                    $category->media->delete();

                }

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('category', $image,'general');

                $category->media()->create([
                    'file_name' => $image ,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);

            }

            return $this->sendResponse([],'Data exited successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category && $category->products->count() == 0){
            if($category->media && File::exists('upload/category/'.$category->media->file_name)){
                unlink('upload/category/'. $category->media->file_name);
                $category->media->delete();
            }
            $category->delete();
            return $this->sendResponse([],'Deleted successfully');
        }else{
            return $this->sendError('ID is not exist');
        }
    }
}
