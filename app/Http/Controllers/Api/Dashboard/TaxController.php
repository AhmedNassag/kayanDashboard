<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use App\Traits\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    use Message;
    public function __construct()
    {
        app()->setLocale(request()->header('lang'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $taxs = Tax::when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest()->paginate(10);

        return $this->sendResponse(['taxs' => $taxs], 'Data exited successfully');
    }

    //change status

    public function activationTax($id)
    {
        $tax = Tax::find($id);

        if ($tax->status == 1) {
            $tax->update([
                "status" => 0
            ]);
        } else {
            $tax->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    // change status tax in app

    public function activationTaxApp($id)
    {
        $tax = Tax::find($id);

        if ($tax->app == 1) {
            $tax->update([
                "app" => 0
            ]);
        } else {
            $tax->update([
                "app" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            // Validator request
            $this->validate($request,[
                'name' => ['required', 'unique:taxes,name','string'],
                'percentage' => ['required', 'numeric'],
            ]);

            $data = $request->only(['name', 'percentage']);

            $tax = Tax::create($data);


            return $this->sendResponse([], 'Data exited successfully');

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

            $tax = Tax::find($id);

            return $this->sendResponse(['tax' => $tax], 'Data exited successfully');
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

        $tax = Tax::find($id);
        // Validator request
        $this->validate($request,[
            'name' => ['required', 'unique:taxes,name,'.$id,'string'],
            'percentage' => ['required', 'numeric']
        ]);

        $data = $request->only(['name', 'percentage']);
        $tax->update($data);

        return $this->sendResponse([], 'Data exited successfully');

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
            $tax = Tax::find($id);
            if ($tax) {

                $tax->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
