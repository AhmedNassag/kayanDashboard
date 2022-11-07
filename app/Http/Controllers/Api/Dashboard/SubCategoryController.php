<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subCategories = SubCategory::with(['category','media:file_name,mediable_id'])
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%')
            ->orWhereRelation('category', 'name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['subCategories' => $subCategories], 'Data exited successfully');
    }


    public function activationSubCategory($id)
    {
        $subCategory = SubCategory::find($id);

        if ($subCategory->status == 1)
        {
            $subCategory->update([
                "status" => 0
            ]);
        }else{
            $subCategory->update([
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
                'category_id' => 'required',
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','category_id']);

            $subCategory = SubCategory::create($data);

            if ($request->hasFile('file')) {

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('subCategory', $image, 'general');
                $subCategory->media()->create([
                    'file_name' => $image,
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

            $subCategory = SubCategory::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['subCategory' => $subCategory], 'Data exited successfully');

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

            $subCategory = SubCategory::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'category_id' => 'required',
                'file' => 'nullable' . ($request->hasFile('file') ? '|file' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','category_id','status']);

            $subCategory->update($data);

            if ($request->hasFile('file')) {

                if (File::exists('upload/subCategory/' . $subCategory->media->file_name)) {
                    unlink('upload/subCategory/' . $subCategory->media->file_name);
                }
                $subCategory->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('subCategory', $image, 'general');

                $subCategory->media()->create([
                    'file_name' => $image,
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
        try
        {
            $subCategory = SubCategory::find($id);
            if ($subCategory)
            {
                if (File::exists('upload/subCategory/' . $subCategory->media->file_name))
                {
                    unlink('upload/subCategory/' . $subCategory->media->file_name);
                }
                $subCategory->media->delete();
                $subCategory->delete();
                return $this->sendResponse([], 'Deleted successfully');
            }
            else
            {
                return $this->sendError('ID is not exist');
            }
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }
}
