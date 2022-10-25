<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertiseScheduleResource;
use App\Models\AdvertiseSchedule;
use App\Models\PackageSale;
use App\Models\PackageSaleUser;
use App\Traits\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AdvertiserScheduleController extends Controller
{

    use Message;

    /**
     * activation Job
     */
    public function activation($id)
    {
        $schedule = AdvertiseSchedule::find($id);

        if ($schedule->status == 1)
        {
            $schedule->update
            ([
                "status" => 0
            ]);
        }
        else
        {
            $schedule->update
            ([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }



    public function index(Request $request)
    {
        $schedule = AdvertiseSchedule::with(['packages','users:name,id'])
        ->when($request->search,function ($q) use($request){
            return $q->WhereRelation('packages', 'name', 'like', '%' . $request->search . '%')
            ->orWhereRelation('users', 'name', 'like', '%' . $request->search . '%');
        })->paginate(10);
        return $this->sendResponse(['schedule' => $schedule], 'Data exited successfully');
    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            // Validator request
            $v = Validator::make($request->all(),
            [
                'start' => 'required|date|after_or_equal:'. now()->toDateString(),
                'end' => 'required|date|after_or_equal:start',
                'package' => 'required|integer|exists:advertising_packages,id',
                'user' => 'required|integer|exists:users,id',
                // 'package_sale' => 'required|integer|exists:package_sales,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data',['error' => 'date'], 401);
            }

            $AdvertiseSchedules = AdvertiseSchedule::where('advertising_package_id', $request->package)
            ->whereBetween('end', [now(), now()->addMonths(5)])
            ->get();

            $end_date = Carbon::parse($request->end)->format('Y-m-d H:i');
            $start_date = Carbon::parse($request->start)->format('Y-m-d H:i');

            foreach ($AdvertiseSchedules as $AdvertiseSchedule)
            {
                if((($AdvertiseSchedule->start <= $start_date) && ($AdvertiseSchedule->end >= $start_date)) || (($AdvertiseSchedule->start <= $end_date) && ($AdvertiseSchedule->end >= $end_date)))
                {
                    return $this->sendError('The appointment has already been booked',401);
                }
            }

            AdvertiseSchedule::create
            ([
                'start' => $request->start,
                'end' => $request->end,
                'advertising_package_id' => $request->package,
                'user_id' => $request->user,
                // 'package_sale_id' => $request->package_sale,
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    public function destroy($id)
    {
        try {

            $calender = AdvertiseSchedule::find($id);
            if ($calender){

                // delete images
                // $packageSale = PackageSale::find($calender->package_sale_id);

                // File::deleteDirectory(public_path('web/img/advertise/'. $packageSale->id));

                // $userSalePackage = PackageSaleUser::where('package_sale_id',$packageSale->id)->first()->delete();

                // $packageSale->delete();
                $calender->delete();
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
