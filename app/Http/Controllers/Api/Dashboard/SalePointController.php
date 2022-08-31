<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalePointRequest;
use App\Http\Requests\UpdateSalePointRequest;
use App\Repositories\SalePointRepository;

class SalePointController extends Controller
{
    private $salePointRepository;

    public function __construct(SalePointRepository $salePointRepository)
    {
        $this->salePointRepository = $salePointRepository;
        $this->middleware('permission:sale-point read', ['only' => ['index']]);
        $this->middleware('permission:sale-point create', ['only' => ['store']]);
        $this->middleware('permission:sale-point edit', ['only' => ['update', 'toggleActivation']]);
    }

    public function store(StoreSalePointRequest $request)
    {
        $request->merge(["active" => 1]);
        return $this->salePointRepository->store($request->input());
    }

    public function update(UpdateSalePointRequest $request)
    {
        return $this->salePointRepository->update($request->input());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->salePointRepository->getPage(request()->page_size, $text);
    }
    public function toggleActivation($id)
    {
        $this->salePointRepository->toggleActivation($id);
    }
    public function getMainCategories()
    {
        return $this->salePointRepository->getMainCategories();
    }
    public function getClientGroups()
    {
        return $this->salePointRepository->getClientGroups();
    }
}
