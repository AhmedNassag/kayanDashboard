<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Repositories\UnitRepository;

class UnitController extends Controller
{
    private $unitRepository;
    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
        $this->middleware('permission:unit read', ['only' => ['index']]);
        $this->middleware('permission:unit create', ['only' => ['store']]);
        $this->middleware('permission:unit edit', ['only' => ['update']]);
        $this->middleware('permission:unit delete', ['only' => ['delete']]);
    }
    public function store(StoreUnitRequest $request)
    {
        return $this->unitRepository->store($request->input());
    }

    public function update(UpdateUnitRequest $request)
    {
        return $this->unitRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->unitRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->unitRepository->getPage(request()->page_size, $text);
    }
}
