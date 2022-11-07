<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UsersCategory;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UsersCategoryController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usersCategories = UsersCategory::with('media:file_name,mediable_id')
            ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('description', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['usersCategories' => $usersCategories], 'Data exited successfully');
    }


    public function activationUsersCategory($id)
    {
        $usersCategory = UsersCategory::find($id);

        if ($usersCategory->status == 1)
        {
            $usersCategory->update([
                "status" => 0
            ]);
        }else{
            $usersCategory->update([
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
                'name' => 'required|unique:users_categories,name',
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','description']);

            $usersCategory = UsersCategory::create($data);

            if($request->hasFile('file')){

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('usersCategory', $image,'general');

                $usersCategory->media()->create([
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

            $usersCategory = UsersCategory::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['usersCategory' => $usersCategory], 'Data exited successfully');

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

            $usersCategory = UsersCategory::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'file' => 'nullable'.($request->hasFile('file')?'|file|mimes:jpeg,jpg,png':''),
                'description' => 'required|string',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','description','status']);

            $usersCategory->update($data);

            if($request->hasFile('file')){

                if(File::exists('upload/usersCategory/'.$usersCategory->media->file_name)){
                    unlink('upload/usersCategory/'. $usersCategory->media->file_name);
                }
                $usersCategory->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time().'.'. $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('usersCategory', $image,'general');

                $usersCategory->media()->create([
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
            $usersCategory = UsersCategory::find($id);
            if ($usersCategory){

                if(File::exists('upload/usersCategory/'.$usersCategory->media->file_name)){
                    unlink('upload/usersCategory/'. $usersCategory->media->file_name);
                }
                $usersCategory->media->delete();

                $usersCategory->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }
}
