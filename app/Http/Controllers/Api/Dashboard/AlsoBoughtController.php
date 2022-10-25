<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlsoBoughtRequest;
use App\Http\Requests\StoreAlsoBoughtRequest;
use App\Http\Requests\UpdateAlsoBoughtRequest;
use App\Repositories\AlsoBoughtRepository;

class AlsoBoughtController extends Controller
{
    private $alsoBoughtRepository;

    public function __construct(AlsoBoughtRepository $alsoBoughtRepository)
    {
        $this->alsoBoughtRepository = $alsoBoughtRepository;
        $this->middleware('permission:also-bought read', ['only' => [
            'index', 'getAlsoBoughtSettings', 'getProducts'
        ]]);
        $this->middleware('permission:also-bought create', ['only' => ['store', 'insertAlsoBoughtSettings']]);
        $this->middleware('permission:also-bought edit', ['only' => ['update']]);
        $this->middleware('permission:also-bought delete', ['only' => ['delete']]);
    }
    //General settings of also bought
    public function insertAlsoBoughtSettings(AlsoBoughtRequest $request)
    {
        $this->alsoBoughtRepository->insertAlsoBoughtSettings($request->validated());
    }
    public function getAlsoBoughtSettings()
    {
        return $this->alsoBoughtRepository->getAlsoBoughtSettings();
    }

    //Also bought products
    public function store(StoreAlsoBoughtRequest $request)
    {
        return $this->alsoBoughtRepository->store($request->validated());
    }

    public function update(UpdateAlsoBoughtRequest $request)
    {
        return $this->alsoBoughtRepository->update($request->validated());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->alsoBoughtRepository->getPage(request()->page_size, $text);
    }

    public function delete($id)
    {
        $this->alsoBoughtRepository->delete($id);
    }

    public function getProducts()
    {
        return $this->alsoBoughtRepository->getProducts();
    }
}
