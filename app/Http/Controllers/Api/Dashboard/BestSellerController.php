<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BestSellerRequest;
use App\Http\Requests\StoreBestSellerProductRequest;
use App\Http\Requests\UpdateBestSellerProductRequest;
use App\Repositories\BestSellerRepository;

class BestSellerController extends Controller
{
    private $bestSellerRepository;

    public function __construct(BestSellerRepository $bestSellerRepository)
    {
        $this->bestSellerRepository = $bestSellerRepository;
        $this->middleware('permission:best-seller read', ['only' => [
            'index', 'getBestSellerSettings', 'getProducts'
        ]]);
        $this->middleware('permission:best-seller create', ['only' => ['store', 'insertBestSellerSettings']]);
        $this->middleware('permission:best-seller edit', ['only' => ['update']]);
        $this->middleware('permission:best-seller delete', ['only' => ['delete']]);
    }
    //General settings of deal
    public function insertBestSellerSettings(BestSellerRequest $request)
    {
        $this->bestSellerRepository->insertBestSellerSettings($request->validated());
    }

    public function getBestSellerSettings()
    {
        return $this->bestSellerRepository->getBestSellerSettings();
    }

    //Best seller Items
    public function store(StoreBestSellerProductRequest $request)
    {
        return $this->bestSellerRepository->store($request->validated());
    }

    public function update(UpdateBestSellerProductRequest $request)
    {
        return $this->bestSellerRepository->update($request->validated());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->bestSellerRepository->getPage(request()->page_size, $text);
    }

    public function delete($id)
    {
        $this->bestSellerRepository->delete($id);
    }

    public function getProducts()
    {
        return $this->bestSellerRepository->getProducts();
    }
}
