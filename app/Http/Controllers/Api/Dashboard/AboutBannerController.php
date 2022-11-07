<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutBannerRequest;
use App\Repositories\AboutBannerRepository;
use Illuminate\Support\Facades\Storage;

class AboutBannerController extends Controller
{
    private $aboutBannerRepository;
    public function __construct(AboutBannerRepository $aboutBannerRepository)
    {
        $this->middleware('permission:about read')->only("getAboutBanners");
        $this->middleware('permission:about edit')->only("update");
        $this->aboutBannerRepository = $aboutBannerRepository;
    }

    public function update(AboutBannerRequest $request)
    {
        if ($request->file("image")) {
            $request->merge(["image" => $request->file("image")->store('about', 'general')]);
        }
        $updateResult = $this->aboutBannerRepository->update($request->input());
        if ($request->file("image") && $updateResult["old_image"]) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["about_banner"];
    }

    public function getAboutBanners()
    {
        return $this->aboutBannerRepository->getAboutBanners();
    }
}
