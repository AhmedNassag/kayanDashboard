<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use App\Traits\Message;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stocks = Stock::with('employee')->with('shift')
            ->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        return $this->sendResponse(['stocks' => $stocks], 'Data exited successfully');
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
                'name' => 'required|string',
                'governorate' => 'required',
                'region' => 'required',
                'title' => 'required',
                // 'latitude'=> 'required',
                // 'longitude' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'employee_id' => 'required',
                'shift_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','governorate','region','title',/*'latitude','longitude',*/'phone','email','employee_id','shift_id']);

            $stock = Stock::create($data);

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

            $stock = Stock::find($id);

            return $this->sendResponse(['stock' => $stock], 'Data exited successfully');

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

            $stock = Stock::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'governorate' => 'required',
                'region' => 'required',
                'title' => 'required',
                // 'latitude'=> 'required',
                // 'longitude' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'employee_id' => 'required',
                'shift_id' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','governorate','region','title',/*'latitude','longitude',*/'phone','email','employee_id','shift_id']);

            $stock->update($data);

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
            $stock = Stock::find($id);
            if ($stock){
                $stock->delete();
                return $this->sendResponse([],'Deleted successfully');
            }else{
                return $this->sendError('ID is not exist');
            }

        }catch (\Exception $e){
            return $this->sendError('An error occurred in the system');
        }
    }


    //start relations functions
    public function getEmpolyees()
    {
        $empolyees = Employee::with('user')->get();
        return $this->sendResponse(['empolyees' => $empolyees], 'Data exited successfully');
    }

    public function getShifts()
    {
        $shifts = Shift::all();
        return $this->sendResponse(['shifts' => $shifts], 'Data exited successfully');
    }
}
