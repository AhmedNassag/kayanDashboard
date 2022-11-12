<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutSectionRequest;
use App\Repositories\AboutSectionRepository;
use Illuminate\Support\Facades\Storage;

class AboutSectionController extends Controller
{
    private $aboutSectionRepository;
    public function __construct(AboutSectionRepository $aboutSectionRepository)
    {
        $this->middleware('permission:about read')->only("getAboutSections");
        $this->middleware('permission:about edit')->only("update");
        $this->aboutSectionRepository = $aboutSectionRepository;
    }

    public function update(AboutSectionRequest $request)
    {
        if ($request->file("image")) {
            $request->merge(["image" => $request->file("image")->store('about', 'general')]);
        }
        $updateResult = $this->aboutSectionRepository->update($request->input());
        if ($request->file("image") && $updateResult["old_image"]) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["about_section"];
    }

    public function getAboutSections()
    {
        return $this->aboutSectionRepository->getAboutSections();
    }
}
