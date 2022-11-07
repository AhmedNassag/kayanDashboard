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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $taxes = Tax::when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%')
            ->orWhere('rate', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['taxes' => $taxes], 'Data exited successfully');
    }


    public function activationTax($id)
    {
        $tax = Tax::find($id);

        if ($tax->status == 1)
        {
            $tax->update([
                "status" => 0
            ]);
        }else{
            $tax->update([
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
                'name' => 'required|unique:taxes,name',
                'rate' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','rate']);

            $tax = Tax::create($data);

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
        DB::beginTransaction();
        try {

            $tax = Tax::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'rate' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','rate','status']);

            $tax->update($data);

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
            $tax = Tax::find($id);
            if ($tax)
            {
                $tax->delete();
                return $this->sendResponse([],'Deleted successfully');
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
