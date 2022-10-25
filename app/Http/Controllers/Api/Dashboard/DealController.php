<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealRequest;
use App\Http\Requests\StoreDealPriceRequest;
use App\Http\Requests\UpdateDealPriceRequest;
use App\Repositories\DealRepository;

class DealController extends Controller
{
    private $dealRepository;

    public function __construct(DealRepository $dealRepository)
    {
        $this->dealRepository = $dealRepository;
        $this->middleware('permission:deal read', ['only' => [
            'index', 'getDealSettings', 'getProducts'
        ]]);
        $this->middleware('permission:deal create', ['only' => ['store', 'insertDealSettings']]);
        $this->middleware('permission:deal edit', ['only' => ['update']]);
        $this->middleware('permission:deal delete', ['only' => ['delete']]);
    }
    //General settings of deal
    public function insertDealSettings(DealRequest $request)
    {
        $this->dealRepository->insertDealSettings($request->validated());
    }

    public function getDealSettings()
    {
        return $this->dealRepository->getDealSettings();
    }

    //Deal Prices
    public function store(StoreDealPriceRequest $request)
    {
        return $this->dealRepository->store($request->validated());
    }

    public function update(UpdateDealPriceRequest $request)
    {
        return $this->dealRepository->update($request->validated());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->dealRepository->getPage(request()->page_size, $text);
    }

    public function delete($id)
    {
        $this->dealRepository->delete($id);
    }

    public function getProducts()
    {
        return $this->dealRepository->getProducts();
    }
}
