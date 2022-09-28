<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use Illuminate\Http\Request;
use App\Traits\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AlternativeController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $alternatives = Alternative::with('media:file_name,mediable_id')
            ->when($request->search, function ($q) use ($request) {
                return $q->where('nameAr', 'like', '%' . $request->search . '%');
            })->latest()->paginate(10);

        return $this->sendResponse(['alternatives' => $alternatives], 'Data exited successfully');
    }


    public function activationAlternative($id)
    {
        $alternative = Alternative::find($id);

        if ($alternative->status == 1) {
            $alternative->update([
                "status" => 0
            ]);
        } else {
            $alternative->update([
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
                'nameAr' => 'required|unique:alternatives,nameAr',
                'nameEn' => 'required|unique:alternatives,nameEn',
                'file' => 'required|file|mimes:png,jpg,jpeg',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['nameAr','nameEn']);

            $alternative = Alternative::create($data);

            if ($request->hasFile('file')) {

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('alternative', $image, 'general');

                $alternative->media()->create([
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

            $alternative = Alternative::with('media:file_name,mediable_id')->find($id);

            return $this->sendResponse(['alternative' => $alternative], 'Data exited successfully');
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

            $alternative = Alternative::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'nameAr' => 'required|unique:alternatives,nameAr',
                'nameEn' => 'required|unique:alternatives,nameEn',
                'file' => 'nullable' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['nameAr', 'nameEn', 'status']);

            $alternative->update($data);

            if ($request->hasFile('file')) {

                if (File::exists('upload/alternative/' . $alternative->media->file_name)) {
                    unlink('upload/alternative/' . $alternative->media->file_name);
                }
                $alternative->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('alternative', $image, 'general');

                $alternative->media()->create([
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $alternative = Alternative::find($id);
            if ($alternative) {

                if (File::exists('upload/alternative/' . $alternative->media->file_name)) {
                    unlink('upload/alternative/' . $alternative->media->file_name);
                }
                $alternative->media->delete();

                $alternative->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
