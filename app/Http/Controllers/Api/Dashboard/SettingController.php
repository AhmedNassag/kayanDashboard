<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use Message;

    public function index()
    {
        return $this->sendResponse(['setting' => Setting::first()], 'Data exited successfully');
    }

    public function edit($id)
    {
        return $this->sendResponse(['setting' =>  Setting::first()], 'Data exited successfully');
    }

    public function update(Request $request, $id)
    {

        $data = $this->validate($request,[
            'linkedin' => 'nullable|max:255|string',
            'youtube' => 'nullable|max:255|string',
            'pinterest' => 'nullable|max:255|string',
            'email' => 'nullable|email',
            'address' => 'nullable|max:255|string',
            'work_time' => 'nullable|max:255|string',
            'instagram' => 'required|max:255|string',
            'close' => 'required|boolean',
            'show_price' => 'required|boolean',
            'facebook' => 'required|string|min:1',
            'phone' => 'required|regex:/(01)[0-9]{9}/',
            'wats_app' => 'required|regex:/(01)[0-9]{9}/',
        ]);

        Setting::first()->update(collect($data)->filter()->toArray());
    }

}
