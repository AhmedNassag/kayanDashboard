<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Repositories\CityRepository;

class CityController extends Controller
{
    private $cityRepository;
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->middleware('permission:city read', ['only' => ['index']]);
        $this->middleware('permission:city create', ['only' => ['store']]);
        $this->middleware('permission:city edit', ['only' => ['update']]);
    }
    public function store(StoreCityRequest $request)
    {
        return $this->cityRepository->store($request->input());
    }

    public function update(UpdateCityRequest $request)
    {
        return $this->cityRepository->update($request->input());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->cityRepository->getPage(request()->page_size, $text);
    }
}
