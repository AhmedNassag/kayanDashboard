<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Repositories\ShippingRepository;
use App\Repositories\SupplierRepository;

class SupplierController extends Controller
{

    private $supplierRepository;
    private $shippingRepository;

    public function __construct(
        SupplierRepository $supplierRepository,
        ShippingRepository $shippingRepository
    ) {
        $this->supplierRepository = $supplierRepository;
        $this->shippingRepository = $shippingRepository;
        $this->middleware('permission:supplier read', ['only' => ['index']]);
        $this->middleware('permission:supplier create', ['only' => ['store']]);
        $this->middleware('permission:supplier edit', ['only' => ['update', 'toggleActivation']]);
        $this->middleware('permission:supplier delete', ['only' => ['delete']]);
    }

    public function store(StoreSupplierRequest $request)
    {
        $request->merge(["active" => 1]);
        return $this->supplierRepository->store($request->input());
    }

    public function update(UpdateSupplierRequest $request)
    {
        return $this->supplierRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->supplierRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->supplierRepository->getPage(request()->page_size, $text);
    }

    public function toggleActivation($id)
    {
        $this->supplierRepository->toggleActivation($id);
    }
    public function getAllEmployees()
    {
        return $this->supplierRepository->getAllEmployees();
    }
    public function getAllShippings()
    {
        return $this->shippingRepository->getAllShippings();
    }
}
