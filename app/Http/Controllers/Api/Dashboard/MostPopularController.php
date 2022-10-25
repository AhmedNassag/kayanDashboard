<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MostPopularRequest;
use App\Http\Requests\StoreMostPopularRequest;
use App\Http\Requests\UpdateMostPopularRequest;
use App\Repositories\MostPopularRepository;

class MostPopularController extends Controller
{
    private $mostPopularRepository;

    public function __construct(MostPopularRepository $mostPopularRepository)
    {
        $this->mostPopularRepository = $mostPopularRepository;
        $this->middleware('permission:most-popular read', ['only' => [
            'index', 'getMostPopularSettings', 'getProducts'
        ]]);
        $this->middleware('permission:most-popular create', ['only' => ['store', 'insertMostPopularSettings']]);
        $this->middleware('permission:most-popular edit', ['only' => ['update']]);
        $this->middleware('permission:most-popular delete', ['only' => ['delete']]);
    }
    //General settings of most popular
    public function insertMostPopularSettings(MostPopularRequest $request)
    {
        $this->mostPopularRepository->insertMostPopularSettings($request->validated());
    }

    public function getMostPopularSettings()
    {
        return $this->mostPopularRepository->getMostPopularSettings();
    }

    //Best seller Items
    public function store(StoreMostPopularRequest $request)
    {
        return $this->mostPopularRepository->store($request->validated());
    }

    public function update(UpdateMostPopularRequest $request)
    {
        return $this->mostPopularRepository->update($request->validated());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->mostPopularRepository->getPage(request()->page_size, $text);
    }

    public function delete($id)
    {
        $this->mostPopularRepository->delete($id);
    }

    public function getProducts()
    {
        return $this->mostPopularRepository->getProducts();
    }
}
