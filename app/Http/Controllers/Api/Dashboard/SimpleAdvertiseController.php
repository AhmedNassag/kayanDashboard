<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSimpleAdvertiseRequest;
use App\Http\Requests\UpdateSimpleAdvertiseRequest;
use App\Repositories\SimpleAdvertiseRepository;
use Illuminate\Support\Facades\Storage;

class SimpleAdvertiseController extends Controller
{
    private $simpleAdvertiseRepository;
    public function __construct(SimpleAdvertiseRepository $simpleAdvertiseRepository)
    {
        $this->middleware('permission:simple-advertise read', ['only' => ['index', 'getProducts']]);
        $this->middleware('permission:simple-advertise create', ['only' => ['store']]);
        $this->middleware('permission:simple-advertise edit', ['only' => ['update']]);
        $this->middleware('permission:simple-advertise delete', ['only' => ['delete']]);
        $this->simpleAdvertiseRepository = $simpleAdvertiseRepository;
    }

    public function store(StoreSimpleAdvertiseRequest $request)
    {
        $image = $request->file("image")->store('simple-advertises', 'general');
        $request->merge(["image" => $image]);
        $simpleAdvertise = $this->simpleAdvertiseRepository->store($request->input());
        return $simpleAdvertise;
    }

    public function update(UpdateSimpleAdvertiseRequest $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store('simple-advertises', 'general');
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->simpleAdvertiseRepository->update($request->input());
        if ($request->file("image")) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["updated_simple_advertise"];
    }

    public function delete($id)
    {
        $oldImage = $this->simpleAdvertiseRepository->delete($id);
        if ($oldImage) {
            Storage::disk('general')->delete("$oldImage");
        }
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->simpleAdvertiseRepository->getPage(request()->page_size, $text);
    }

    public function getProducts()
    {
        return $this->simpleAdvertiseRepository->getProducts();
    }
}
