<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Repositories\AreaRepository;

class AreaController extends Controller
{
    private $areaRepository;
    public function __construct(AreaRepository $areaRepository)
    {
        $this->areaRepository = $areaRepository;
        $this->middleware('permission:area read', ['only' => ['index']]);
        $this->middleware('permission:area create', ['only' => ['store']]);
        $this->middleware('permission:area edit', ['only' => ['update']]);
    }

    public function store(StoreAreaRequest $request)
    {
        return $this->areaRepository->store($request->input());
    }

    public function update(UpdateAreaRequest $request)
    {
        return $this->areaRepository->update($request->input());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        $city = isset(request()->city) ? request()->city : '';
        return $this->areaRepository->getPage(request()->page_size, $text,$city);
    }

    public function getCities()
    {
        return $this->areaRepository->getCities();
    }
}
