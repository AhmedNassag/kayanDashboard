<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Repositories\SliderRepository;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    private $sliderRepository;
    public function __construct(SliderRepository $sliderRepository)
    {
        $this->middleware('permission:slider read', ['only' => ['index','getProducts']]);
        $this->middleware('permission:slider create', ['only' => ['store']]);
        $this->middleware('permission:slider edit', ['only' => ['update']]);
        $this->middleware('permission:slider delete', ['only' => ['delete']]);
        $this->sliderRepository = $sliderRepository;
    }

    public function store(StoreSliderRequest $request)
    {
        $image = $request->file("image")->store('sliders', 'general');
        $request->merge(["image" => $image]);
        $slider = $this->sliderRepository->store($request->input());
        return $slider;
    }

    public function update(UpdateSliderRequest $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store('sliders', 'general');
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->sliderRepository->update($request->input());
        if ($request->file("image")) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["updated_slider"];
    }

    public function delete($id)
    {
        $oldImage = $this->sliderRepository->delete($id);
        if ($oldImage) {
            Storage::disk('general')->delete("$oldImage");
        }
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->sliderRepository->getPage(request()->page_size, $text);
    }

    public function getProducts()
    {
        return $this->sliderRepository->getProducts();
    }
}
