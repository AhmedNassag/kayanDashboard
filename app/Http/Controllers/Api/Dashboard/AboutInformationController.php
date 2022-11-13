<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutInformationRequest;
use App\Repositories\AboutInformationRepository;
use Illuminate\Support\Facades\Storage;

class AboutInformationController extends Controller
{
    private $aboutInformationRepository;

    public function __construct(AboutInformationRepository $aboutInformationRepository)
    {
        $this->middleware('permission:about read')->only("getAboutSections");
        $this->middleware('permission:about edit')->only("update");
        $this->aboutInformationRepository = $aboutInformationRepository;
    }

    public function update(AboutInformationRequest $request)
    {
        if ($request->file("image")) {
            $request->merge(["image" => $request->file("image")->store('about', 'general')]);
        }
        $updateResult = $this->aboutInformationRepository->update($request->input());
        if ($request->file("image") && $updateResult["old_image"]) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["about_section"];
    }

    public function getAboutInformations()
    {
        return $this->aboutInformationRepository->getAboutInformation();
    }
}
