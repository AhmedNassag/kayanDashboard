<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisePackageRequest;
use App\Models\AdvertisingPackage;
use App\Models\AdvertisingPage;
use App\Models\AdvertisingPageMobile;
use App\Models\PackageSale;
use App\Models\PageViewMobilePackage;
use App\Models\PageViewPackage;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    use Message;

    public function index(Request $request)
    {
        $package = AdvertisingPackage::when($request->search,function ($q) use($request){
            return $q->where('name','like','%'.$request->search.'%');
        })->paginate(5);

        return $this->sendResponse(['package' => $package],'Data exited successfully');
    }



    public function create()
    {
        try
        {
            $pageView       = AdvertisingPage::with('views')->get();
            $pageViewMobile = AdvertisingPageMobile::with('views')->get();
            $notWebId       = PageViewPackage::get()->pluck('page_view_id')->toArray();
            $notMobileId    = PageViewMobilePackage::get()->pluck('page_view_mobile_id')->toArray();

            return $this->sendResponse(['pageView' => $pageView, 'pageViewMobile' => $pageViewMobile,'notWebId' => $notWebId,"notMobileId" => $notMobileId ],'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }



    public function store(AdvertisePackageRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $data = $request->only([/*'ar','en',*/ 'name','period','visiter_num','price']);
            $package = AdvertisingPackage::create($data);
            $package->page_view()->attach($request->pageView_id);
            $package->page_view_mobile()->attach($request->pageViewMobile_id);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function show($id)
    {
        try
        {
            $package = AdvertisingPackage::with(['page_view.Page','page_view.view','page_view_mobile.pageMobile','page_view_mobile.view'])->find($id);
            return $this->sendResponse(['package'=> $package],'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }



    public function statusPackage(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $package = AdvertisingPackage::find($request->id);
            $package->update(['status' => $request->status]);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function edit($id)
    {
        try
        {
            $dataPackage = AdvertisingPackage::with(['page_view','page_view_mobile'])->find($id);
            if($dataPackage)
            {
                $dataPackage->makeVisible('translations');
                $pageView = AdvertisingPage::with('views')->get();
                $pageViewMobile = AdvertisingPageMobile::with('views')->get();
                $notWebId = PageViewPackage::get()->pluck('page_view_id')->toArray();
                $notMobileId = PageViewMobilePackage::get()->pluck('page_view_mobile_id')->toArray();

                return $this->sendResponse(['pageView' => $pageView, 'pageViewMobile' => $pageViewMobile,'Package' => $dataPackage,'notWebId' => $notWebId,'notMobileId' => $notMobileId],'Data exited successfully');
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



    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try
        {
            $advertisingPackage = AdvertisingPackage::find($id);
            if($advertisingPackage)
            {
                // Validator request
                $v = Validator::make($request->all(),
                [
                    // 'ar.name' => ['required', Rule::unique('advertising_package_translations','name')->whereNot('package_id',$id)],
                    // 'en.name' => ['required', Rule::unique('advertising_package_translations','name')->whereNot('package_id',$id)],
                    'name'                => 'required',
                    'period'              => 'required|integer',
                    'visiter_num'         => 'required|integer',
                    'price'               => 'required|integer',
                    'pageView_id'         => 'required|array',
                    'pageView_id.*'       => 'required|integer',
                    'pageViewMobile_id'   => 'required|array',
                    'pageViewMobile_id.*' => 'required|integer'
                ]);

                if ($v->fails())
                {
                    return $this->sendError('There is an error in the data', $v->errors());
                }

                $data = $request->only([/*'ar','en',*/'name','period','visiter_num','price']);
                $advertisingPackage->update($data);
                $advertisingPackage->page_view()->sync($request->pageView_id);
                $advertisingPackage->page_view_mobile()->sync($request->pageViewMobile_id);

                DB::commit();
                return $this->sendResponse([],'Data exited successfully');
            }
            else
            {
                return $this->sendError('ID is not exist');
            }
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
            $advertisingPackage = AdvertisingPackage::find($id);
            if ($advertisingPackage)
            {
                // $advertisingSale = PackageSale::where('advertising_package_id',$id)->first();
                // if(!$advertisingSale)
                // {
                //     $advertisingPackage->delete();
                //     return $this->sendResponse([],'Deleted successfully');
                // }
                //     else
                //     {
                //         return $this->sendError("you can't delete this package. ");
                //     }

                $advertisingPackage->delete();
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
