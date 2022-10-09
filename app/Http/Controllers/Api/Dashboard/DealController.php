<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealRequest;
use App\Repositories\DealRepository;

class DealController extends Controller
{
    private $dealRepository;

    public function __construct(DealRepository $dealRepository)
    {
        $this->middleware('permission:deal insert');
        $this->dealRepository = $dealRepository;
    }

    public function insertDeal(DealRequest $request)
    {
        $this->dealRepository->insertDeal($request->validated());
    }

    public function getDeal()
    {
        return $this->dealRepository->getDeal();
    }

    public function getProducts()
    {
        return $this->dealRepository->getProducts();
    }
}
