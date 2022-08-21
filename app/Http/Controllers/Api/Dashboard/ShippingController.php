<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingRequest;
use App\Http\Requests\UpdateShippingRequest;
use App\Repositories\ShippingRepository;

class ShippingController extends Controller
{
    private $shippingRepository;

    public function __construct(ShippingRepository $shippingRepository)
    {
        $this->shippingRepository = $shippingRepository;
        $this->middleware('permission:shipping read', ['only' => ['index']]);
        $this->middleware('permission:shipping create', ['only' => ['store']]);
        $this->middleware('permission:shipping edit', ['only' => ['update', 'toggleActivation']]);
        $this->middleware('permission:shipping delete', ['only' => ['delete']]);
    }

    public function store(StoreShippingRequest $request)
    {
        $request->merge(["active" => 1]);
        return $this->shippingRepository->store($request->input());
    }

    public function update(UpdateShippingRequest $request)
    {
        return $this->shippingRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->shippingRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->shippingRepository->getPage(request()->page_size, $text);
    }

    public function toggleActivation($id)
    {
        $this->shippingRepository->toggleActivation($id);
    }
}
