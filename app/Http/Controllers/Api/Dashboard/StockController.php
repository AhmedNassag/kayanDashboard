<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
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
        $stocks = Stock::with(['employee.user','shift','city','area'])
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
        try {

            $shifts    = Shift::where('status',1)->select('id','name')->get();
            $cities    = City::where('available',1)->select('id', 'name')->get();
            $areas     = Area::select('id', 'name')->get();
            $employees = Employee::with('user')->get();

            return $this->sendResponse([
                'cities' => $cities,
                'areas'  => $areas,
                'shifts' => $shifts,
                'employees' => $employees,
            ], 'Data exited successfully');

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
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
                'city_id' => 'required',
                'area_id' => 'required',
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
            $data = $request->only(['name','city_id','area_id','title',/*'latitude','longitude',*/'phone','email','employee_id','shift_id']);

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

            $stock     = Stock::find($id);
            $shifts    = Shift::where('status',1)->select('id', 'name')->get();
            $cities    = City::where('available',1)->select('id', 'name')->get();
            $areas     = Area::select('id', 'name')->get();
            $employees = Employee::with('user')->get();

            return $this->sendResponse([
                'stock' => $stock,
                'cities' => $cities,
                'areas'  => $areas,
                'shifts' => $shifts,
                'employees' => $employees,
            ], 'Data exited successfully');

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
                'city_id' => 'required',
                'area_id' => 'required',
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

            $data = $request->only(['name','city_id','area_id','title',/*'latitude','longitude',*/'phone','email','employee_id','shift_id']);

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
    public function getEmployees()
    {
        $employees = Employee::all();
        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
    }

    public function getShifts()
    {
        $shifts = Shift::where('status',1)->get();
        return $this->sendResponse(['shifts' => $shifts], 'Data exited successfully');
    }

    public function getCities()
    {
        $cities = City::where('available',1)->get();
        return $this->sendResponse(['cities' => $cities], 'Data exited successfully');
    }

    public function getAreas()
    {
        $areas = Area::all();
        return $this->sendResponse(['areas' => $areas], 'Data exited successfully');
    }
}
