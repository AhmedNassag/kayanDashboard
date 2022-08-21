<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Repositories\OfferRepository;

class OfferController extends Controller
{
    private $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->middleware('permission:offer read', ['only' => ['index']]);
        $this->middleware('permission:offer create', ['only' => ['store']]);
        $this->middleware('permission:offer edit', ['only' => ['update', 'toggleActivation']]);
        $this->middleware('permission:offer delete', ['only' => ['delete']]);
    }

    public function store(StoreOfferRequest $request)
    {
        $request->merge(["active" => 1]);
        return $this->offerRepository->store($request->input());
    }

    public function update(UpdateOfferRequest $request)
    {
        return $this->offerRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->offerRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->offerRepository->getPage(request()->page_size, $text);
    }

    public function toggleActivation($id)
    {
        $this->offerRepository->toggleActivation($id);
    }
    
}
