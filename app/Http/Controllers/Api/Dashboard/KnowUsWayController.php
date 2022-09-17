<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKnowUsWayRequest;
use App\Http\Requests\UpdateKnowUsWayRequest;
use App\Repositories\KnowUsWayRepository;

class KnowUsWayController extends Controller
{
    private $knowUsWayRepository;
    public function __construct(KnowUsWayRepository $knowUsWayRepository)
    {
        $this->knowUsWayRepository = $knowUsWayRepository;
        $this->middleware('permission:know-us-way read', ['only' => ['index']]);
        $this->middleware('permission:know-us-way create', ['only' => ['store']]);
        $this->middleware('permission:know-us-way edit', ['only' => ['update']]);
        $this->middleware('permission:know-us-way delete', ['only' => ['delete']]);
    }
    public function store(StoreKnowUsWayRequest $request)
    {
        return $this->knowUsWayRepository->store($request->input());
    }

    public function update(UpdateKnowUsWayRequest $request)
    {
        return $this->knowUsWayRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->knowUsWayRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->knowUsWayRepository->getPage(request()->page_size, $text);
    }
}
