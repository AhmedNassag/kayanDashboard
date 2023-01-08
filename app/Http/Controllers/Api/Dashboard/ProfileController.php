<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Income;
use App\Models\Job;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use Message;

    public function __construct()
    {
        app()->setLocale(request()->header('lang'));
    }

    public function getUser(Request $request)
    {

        $user =$request->user();
        $media = $user->media->file_name ??'';
        return $this->sendResponse(['user' => $user, 'media' => $media], 'Data exited successfully');

    }


    public function updateUser(Request $request)
    {

            $user = $request->user();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email' . ($user->id ? ",$user->id" : ''),
                'phone' => 'required|string|unique:users,phone' . ($user->id ? ",$user->id" : ''),
                'password' => 'nullable|string|min:8',
                'confirmtion' => 'nullable|same:password',
                'file' => 'nullable' . ($request->hasFile('file') ? '|mimes:jpeg,jpg,png,webp|max:5048' : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                'phone' => $request->phone
            ]);

            if($request->password)
            $user->update([
                "password" => $request->password,
            ]);

            if ($request->hasFile('file')) {
                if ($user->media && File::exists('upload/user/' . $user->id . '/' . $user->media->file_name)) {
                    unlink('upload/user/' . $user->id . '/' . $user->media->file_name);
                    $user->media->delete();
                }

                $file_size = $request->file->getSize();
                $file_type = $request->file->getMimeType();
                $image = time() . '.' . $request->file->getClientOriginalName();

                // picture move
                $request->file->storeAs('user', $image, 'general');

                $user->media()->create([
                    'file_name' => $image,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                    'file_sort' => 1
                ]);
            }

            return $this->sendResponse(['user' => $user], 'Data exited successfully');

    }

}
