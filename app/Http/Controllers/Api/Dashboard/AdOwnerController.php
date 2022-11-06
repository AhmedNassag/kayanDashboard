<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AdOwner;
use App\Models\AdvertiseSchedule;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AdOwnerController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adOwners = User::with('media:file_name,mediable_id')
        ->whereJsonContains('role_name', 'Advertise')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['adOwners' => $adOwners], 'Data exited successfully');

        // $adOwners = AdvertiseSchedule::with(['packages', 'users:name,id'])
        // ->when($request->search, function ($q) use ($request) {
        //     return $q->WhereRelation('packages', 'name', 'like', '%' . $request->search . '%')
        //     ->orWhereRelation('users', 'name', 'like', '%' . $request->search . '%');
        // })->paginate(10);
        // return $this->sendResponse(['adOwners' => $adOwners], 'Data exited successfully');
    }



    public function activationAdOwner($id)
    {
        $adOwner = User::find($id);

        if ($adOwner->status == 1)
        {
            $adOwner->update
            ([
                "status" => 0
            ]);
        }
        else
        {
            $adOwner->update
            ([
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
        try
        {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(),
            [
                'name'  => 'required|string',
                'email' => 'required',
                'password' => 'required',
                'phone' => 'required|integer|unique:users,phone',
                'file'  => 'nullable' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
                // 'image'  => 'nullable' . ($request->hasFile('image') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            // $data = $request->only(['name','email', 'password', 'phone']);
            $adOwner = User::create
            ([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'phone' => $request->phone,
                'role_name' => ['Advertise'],
            ]);

            if ($request->hasFile('file'))
            {
                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image     = time() . '.' . $request->file->getClientOriginalName();

                $request->file->storeAs('adOwnerCommercialRecord', $image, 'general');
                $adOwner->media()->create
                ([
                    'file_name' => $image,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);
            }

            // if ($request->hasFile('image'))
            // {
            //     $file_size = $request->image->getSize();
            //     $file_type = $request->image->getMimeType();
            //     $image     = time() . '.' . $request->file->getClientOriginalName();

            //     $request->image->storeAs('adOwnerTaxCard', $image, 'general');
            //     $adOwner->media()->create([
            //         'file_name' => $image,
            //         'file_size' => $file_size,
            //         'file_type' => $file_type,
            //         'file_sort' => 1
            //     ]);
            // }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
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
        try
        {
            $adOwner = User::with('media:file_name,mediable_id')->find($id);
            return $this->sendResponse(['adOwner' => $adOwner], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
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

            $adOwner = User::find($id);

            // Validator request
            $v = Validator::make($request->all(),
            [
                'name'  => 'required|string',
                'email' => 'required|unique:users,email',
                // 'password' => 'required',
                'phone' => 'required|integer',
                'file'  => 'nullable' . ($request->hasFile('file') ? '|file|mimes:jpeg,jpg,png' : ''),
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            // $data = $request->only(['name', 'phone', 'status']);

            $adOwner->update
            ([
                'name' => $request->name,
                'email' => $request->email,
                // 'password' => $request->password,
                'phone' => $request->phone,
            ]);

            if ($request->hasFile('file'))
            {

                if (File::exists('upload/adOwnerCommercialRecord/' . $adOwner->media->file_name))
                {
                    unlink('upload/adOwnerCommercialRecord/' . $adOwner->media->file_name);
                }
                $adOwner->media->delete();

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('adOwnerCommercialRecord', $image, 'general');
                $adOwner->media()->create
                ([
                    'file_name' => $image,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);
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
            $adOwner = User::find($id);
            if ($adOwner)
            {
                if($adOwner->media->file_name)
                {
                    if (File::exists('upload/adOwnerCommercialRecord/' . $adOwner->media->file_name))
                    {
                        unlink('upload/adOwnerCommercialRecord/' . $adOwner->media->file_name);
                        $adOwner->media->delete();
                    }
                }
                $adOwner->delete();
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
