<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertiseScheduleResource;
use App\Models\AdvertiseSchedule;
use App\Models\AdvertisingPackage;
use App\Models\AdvertisingSize;
use App\Models\AdvertisingSizeMobile;
use App\Models\PackageSale;
use App\Models\PackageSaleUser;
use App\Models\SchedulePage;
use App\Models\SchedulePageMobile;
use App\Models\User;
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
            $schedule->update(["status" => 0]);
        }
        else
        {
            $schedule->update(["status" => 1]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }



    public function index(Request $request)
    {
        $schedule = AdvertiseSchedule::with(['packages','users:name,id'])
        ->when($request->search,function ($q) use($request)
        {
            return $q->WhereRelation('packages', 'name', 'like', '%' . $request->search . '%')
            ->orWhereRelation('users', 'name', 'like', '%' . $request->search . '%');
        })->paginate(10);
        return $this->sendResponse(['schedule' => $schedule], 'Data exited successfully');
    }



    public function create()
    {
        try
        {
            $users               = User::where('status', 1)->whereJsonContains('role_name', 'Advertise')->get();
            $advertisingPackages = AdvertisingPackage::where('status', 1)->get();
            //
            $Advertise = AdvertisingPackage::whereStatus(true)
            ->with
            ([
                'page_view' => function ($q)
                {
                    $q->with(['Page', 'view', 'size']);
                },
                'page_view_mobile' => function ($q)
                {
                    $q->with(['pageMobile', 'view', 'size']);
                },
            ])
            ->get();
            //
            return $this->sendResponse
            ([
                'users'               => $users,
                'advertisingPackages' => $advertisingPackages,
                //
                'packages'            => $Advertise
                //
            ], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }



    public function store(Request $request)
    {
        DB::beginTransaction();
        try
        {
            // Validator request
            $v = Validator::make($request->all(),
            [
                'start' => 'required|date|after_or_equal:' . now()->toDateString(),
                'end' => 'required|date|after_or_equal:start',
                'advertising_package_id' => 'required|integer|exists:advertising_packages,id',
                'user_id' => 'required|integer|exists:users,id',
                // 'package_sale' => 'required|integer|exists:package_sales,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', ['error' => 'date'], 401);
            }

            $AdvertiseSchedules = AdvertiseSchedule::where('advertising_package_id', $request->advertising_package_id)
            ->whereBetween('end', [now(), now()->addMonths(5)])
            ->get();

            $end_date   = Carbon::parse($request->end)->format('Y-m-d H:i');
            $start_date = Carbon::parse($request->start)->format('Y-m-d H:i');

            foreach ($AdvertiseSchedules as $AdvertiseSchedule)
            {
                if ((($AdvertiseSchedule->start <= $start_date) && ($AdvertiseSchedule->end >= $start_date)) || (($AdvertiseSchedule->start <= $end_date) && ($AdvertiseSchedule->end >= $end_date)))
                {
                    return $this->sendError('للأسف هذه الفترة محجوزة', 401);
                }
            }
            AdvertiseSchedule::create
            ([
                'title'                  => $request->title,
                'start'                  => $request->start,
                'end'                    => $request->end,
                'link'                   => $request->link,
                'advertising_package_id' => $request->advertising_package_id,
                'user_id'                => $request->user_id,
                // 'package_sale_id' => $request->package_sale,
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function edit($id)
    {
        try
        {
            $schedule            = AdvertiseSchedule::with(['packages', 'users:name,id'])->find($id);
            $users               = User::where('status', 1)->whereJsonContains('role_name', 'Advertise')->get();
            $advertisingPackages = AdvertisingPackage::where('status', 1)->get();
            return $this->sendResponse
            ([
                'schedule'            => $schedule,
                'users'               => $users,
                'advertisingPackages' => $advertisingPackages,
            ], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }



    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try
        {
            $schedule = AdvertiseSchedule::find($id);

            // Validator request
            $v = Validator::make($request->all(),
            [
                'start' => 'required|date|after_or_equal:' . now()->toDateString(),
                'end' => 'required|date|after_or_equal:start',
                'advertising_package_id' => 'required|integer|exists:advertising_packages,id',
                'user_id' => 'required|integer|exists:users,id',
                // 'package_sale' => 'required|integer|exists:package_sales,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $AdvertiseSchedules = AdvertiseSchedule::where('id','!=',$schedule)->where('advertising_package_id', 1 /*$request->advertising_package_id*/)
            ->whereBetween('end', [now(), now()->addMonths(5)])
            ->get();

            $end_date   = Carbon::parse($request->end)->format('Y-m-d H:i');
            $start_date = Carbon::parse($request->start)->format('Y-m-d H:i');

            foreach ($AdvertiseSchedules as $AdvertiseSchedule)
            {
                if ((($AdvertiseSchedule->start <= $start_date) && ($AdvertiseSchedule->end >= $start_date)) || (($AdvertiseSchedule->start <= $end_date) && ($AdvertiseSchedule->end >= $end_date)))
                {
                    return $this->sendError('للأسف هذه الفترة محجوزة', 401);
                }
            }

            // $data = $request->only(['title','start','end','link','advertising_package_id','user_id']);
            // $schedule->update($data);
            $schedule->update
            ([
                'title'                  => $request->title,
                'start'                  => $request->start,
                'end'                    => $request->end,
                'link'                   => $request->link,
                'advertising_package_id' => $request->advertising_package_id,
                'user_id'                => $request->user_id,
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
        try
        {
            $calender = AdvertiseSchedule::find($id);
            if ($calender)
            {
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




    /**/
    public function advertise()
    {
        // $user = auth()->guard('api')->user();
        // $userResource = new UserResource($user);

        // $advertiser = new AdvertiserResource(Advertiser::whereUserId($user->id)->first());

        // return $this->sendResponse(['user' => $userResource, 'advertiser' => $advertiser], 'Data exited successfully');
    }//**********end advertise************/



    public function buy_package(Request $request)
    {
        //  return [getimagesize($request->file[0]['file_name'])];
        $v = Validator::make($request->all(), [
            'package_id' => 'required|integer|exists:advertising_packages,id',
            'link' => 'required|string|url',
            'date' => 'required|date|after_or_equal:today',
            'day' => 'required|integer',
            'file.*.file_name' => 'required|file|mimes:jpeg,jpg,png',
            'file.*.size_id' => 'required|exists:advertising_sizes,id',
            'fileMobile.*.file_name' => 'required|file|mimes:jpeg,jpg,png',
            'fileMobile.*.size_id' => 'required|exists:advertising_size_mobiles,id'
        ]);

        if ($v->fails())
        {
            return $this->sendError('There is an error in the data', $v->errors());
        }

        if ($this->validDim($request->file) == 'error'  || $this->validDimMobile($request->fileMobile) == 'error') {
            return $this->sendError('There is an error in the data', ['file' => 'image']);
        }

        $AdvertiseSchedules = AdvertiseSchedule::where('advertising_package_id', $request->package_id)
        ->whereBetween('end', [now(), now()->addMonths(5)])
        ->get();

        $end_date = Carbon::parse($request->date)->addDays($request->day)->format('Y-m-d H:i');
        $start_date = Carbon::parse($request->date)->format('Y-m-d H:i');

        foreach ($AdvertiseSchedules as $AdvertiseSchedule)
        {
            if (
                (($AdvertiseSchedule->start <= $start_date) && ($AdvertiseSchedule->end >= $start_date)) ||
                (($AdvertiseSchedule->start <= $end_date) && ($AdvertiseSchedule->end >= $end_date))
            )
            {
                return $this->sendError('The appointment has already been booked', 401);
            }
        }
        // $user = auth()->guard('api')->user();
        $user = auth()->user();
        $invoice_value = AdvertisingPackage::find($request->package_id)->price;
        $schedule = AdvertiseSchedule::create([
            'start' => $request->date,
            'end' => Carbon::parse($request->date)->addDays($request->day),
            'link' => $request->link,
            'user_id' => $user->id,
            "title" => $user->complement->nameCompany,
            "price" => $invoice_value,
            'advertising_package_id' => $request->package_id
        ]);

        $i = 0;

        foreach ($request->file as $index => $file)
        {
            $schedule_page = SchedulePage::create([
                'page_id' => $file['page_id'],
                'schedule_id' => $schedule->id
            ]);

            $file_size = $file['file_name']->getSize();
            $file_type = $file['file_name']->getMimeType();
            $image = time() . $i . '.' . $file['file_name']->getClientOriginalName();

            // picture move
            $file['file_name']->storeAs('advertise', $image, 'general');

            $schedule_page->media()->create([
                'file_name' => $image,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => $i
            ]);
            $i++;
        }

        foreach ($request->fileMobile as $index => $fileMobile)
        {
            $schedule_page_mo = SchedulePageMobile::create([
                'page_mobile_id' => $fileMobile['page_id'],
                'schedule_id' => $schedule->id
            ]);

            $file_size = $fileMobile['file_name']->getSize();
            $file_type = $fileMobile['file_name']->getMimeType();
            $image = time() . $i . '.' . $fileMobile['file_name']->getClientOriginalName();

            // picture move
            $fileMobile['file_name']->storeAs('advertise', $image, 'general');
            $schedule_page_mo->media()->create([
                'file_name' => $image,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => $i
            ]);
            $i++;
        }

        // $myfatorah = new MyFatoorahController();
        // $name = $user->name;
        // $phone = $user->phone;
        // $email = $user->email;
        // $code = $user->code;
        // $callback_success = route('advertiser_callback_success');
        // $callback_error = route('advertiser_callback_error');
        // $response = $myfatorah->index($invoice_value, $name, $phone, $email, $schedule->id, $code, $callback_success, $callback_error);
        // if (isset($response->IsSuccess) && $response->IsSuccess == 'true') {
        //     return response()->json(['url' => $response->Data->InvoiceURL], 200);
        // }
    }//**********end buy_package************/



    public function numPackage(Request $request)
    {
        $user = auth()->guard('api')->user();
        // $numVisitor   = $user->schedule()->sum('visitor');
        // $packagePrice = $user->schedule()->sum('price');
        $numberUnfinishedAds = AdvertiseSchedule::where('end', '>=', now())->count();
        $numberFinishedAds   = AdvertiseSchedule::where('end', '<=', now())->count();
        return $this->sendResponse([
                // 'numVisitor' => $numVisitor,
                // 'packagePrice' => $packagePrice,
                'numberFinishedAds' => $numberFinishedAds,
                "numberUnfinishedAds" => $numberUnfinishedAds
            ],'Data exited successfully'
        );
    }//**********end numPackage************/



    public function package(Request $request)
    {
        // $user = auth()->guard('api')->user()->id;
        $user = auth()->user()->id;
        $unFinishedAds = AdvertiseSchedule::select('id', 'advertising_package_id', 'start', 'end', 'visitor')->where('end', '<=', now())->with('packages:id,period,price')->paginate(10);
        return $this->sendResponse([
                'advertises' => $unFinishedAds
            ],'Data exited successfully'
        );
    }//**********end Package************/



    public function package1(Request $request)
    {
        $user = auth()->guard('api')->user()->id;
        $unFinishedAds = AdvertiseSchedule::select('id', 'advertising_package_id', 'start', 'end', 'visitor')->whereUserId($user)->whereActive(true)->where('end', '>=', now())->with('packages:id,period,price')->paginate(10);
        return $this->sendResponse([
                'advertises' => $unFinishedAds
            ],'Data exited successfully'
        );
    }//**********end Package1************/



    public function package2(Request $request)
    {
        // $user = auth()->guard('api')->user()->id;
        $user = auth()->user()->id;
        $visitor = AdvertiseSchedule::select('id', 'advertising_package_id', 'start', 'end', 'visitor')->whereUserId($user)->whereActive(true)->with('packages:id,period,price')->paginate(10);
        return $this->sendResponse([
                'advertises' => $visitor
            ],'Data exited successfully'
        );
    }//**********end Package2************/



    public function getALL($id)
    {
        try
        {
            $schedule = AdvertiseSchedule::where([
                    ['advertising_package_id', $id],
                ])
            ->whereBetween('created_at',
                [now()->subMonth(), now()->addMonths(5)]
            )
            ->get();
            return $this->sendResponse(['schedule' => AdvertiseScheduleResource::collection($schedule)], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }//**********end getALL************/



    protected function validDim($files)
    {
        foreach ($files as $key => $file)
        {
            $size = AdvertisingSize::find($file['size_id']);
            $data = getimagesize($file['file_name']);
            $width = $data[0];
            $height = $data[1];
            if ($width != $size->width || $height != $size->height)
            {
                return 'error';
            }
        }
    }//**********end validDim************/



    protected function validDimMobile($files)
    {
        foreach ($files as $key => $file)
        {
            $size = AdvertisingSizeMobile::find($file['size_id']);
            $data = getimagesize($file['file_name']);
            $width = $data[0];
            $height = $data[1];
            if ($width != $size->width || $height != $size->height)
            {
                return 'error';
            }
        }
    }//**********end validDimMobile************/



    public function advertiser_callback_success(Request $request)
    {
        // if (isset($request['paymentId'])) {
        //     $myfatorah = new MyFatoorahController();
        //     $postFields = [
        //         'Key' => $request['paymentId'],
        //         'KeyType' => 'PaymentId',
        //     ];
        //     $data = $myfatorah->callAPI("/v2/GetPaymentStatus", $postFields);
        //     $transaction = collect($data->Data->InvoiceTransactions)->last();
        //     if (isset($data->Data->CustomerReference) && $transaction->TransactionStatus == "Succss") {
        //         $project = AdvertiseSchedule::findOrFail($data->Data->CustomerReference);
        //         $package = AdvertisingPackage::find($project->advertising_package_id);
        //         $project->update(['status' => 'paid', 'transaction_id' => $transaction->TransactionId]);
        //         $this->notifiy($project->id);
        //         $this->putCommissionInIncomes($package->price, $project->id, $project->title);
        //         return redirect("/" . app()->getLocale() . "/p/advertise/package")->with(['success' => app()->getLocale() == 'ar' ? ' تم الدفع بنجاح' : 'Payment completed successfully']);
        //     }
        // }
    }//**********end advertiser_callback_success************/



    public function advertiser_callback_error(Request $request)
    {
        // if (isset($request['paymentId']))
        // {
        //     $myfatorah = new MyFatoorahController();
        //     $postFields = [
        //         'Key' => $request['paymentId'],
        //         'KeyType' => 'PaymentId',
        //     ];
        //     $data = $myfatorah->callAPI("/v2/GetPaymentStatus", $postFields);
        //     if (isset($data->Data->CustomerReference) && collect($data->Data->InvoiceTransactions)->last()->TransactionStatus == "Failed")
        //     {
        //         $project = AdvertiseSchedule::findOrFail($data->Data->CustomerReference);
        //         $project->delete();
        //         return redirect("/" . app()->getLocale() . "/p/advertise/package")->with(['error' => app()->getLocale() == 'ar' ? 'فشل في عملية الدفع' : 'Payment Failed']);
        //     }
        // }
    }//**********end advertiser_callback_error************/



    public function notifiy($project)
    {
        // User::whereAuthId(1)
        // ->whereRelation('roles.notify', 'name', 'create advertise')
        // ->each(function ($admin) use ($project) {
        //     $admin->notify(new AddProposalNotification($project, '', 'showSchedule', "لقد قام عميل بطلب خدمة الاعلانات", "An advertising service customer has requested ."));
        // });
    }//**********end notifiy************/



    protected function putCommissionInIncomes($amount, $project_id, $name)
    {
        // IncomeAndExpense::create([
        //     'amount' => $amount,
        //     'notes' => "مقابل عمل اعلان علي الموقع ($project_id) ",
        //     'payment_date' => now(),
        //     'payer' => $name,
        //     'treasury_id' => 5,
        //     'income_id' => 5,
        // ]);
    }//**********end putCommissionInIncomes************/
    /**/

}
