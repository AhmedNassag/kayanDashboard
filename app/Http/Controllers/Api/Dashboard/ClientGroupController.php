<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientGroupRequest;
use App\Http\Requests\UpdateClientGroupRequest;
use App\Repositories\ClientGroupRepository;
use App\Repositories\ClientRepository;

class ClientGroupController extends Controller
{
    private $clientGroupRepository;
    private $clientRepository;
    public function __construct(
        ClientGroupRepository $clientGroupRepository,
        ClientRepository $clientRepository
    ) {
        $this->clientGroupRepository = $clientGroupRepository;
        $this->clientRepository = $clientRepository;
        $this->middleware('permission:client-group read', ['only' => ['index']]);
        $this->middleware('permission:client-group create', ['only' => ['store']]);
        $this->middleware('permission:client-group edit', ['only' => ['update']]);
    }
    public function store(StoreClientGroupRequest $request)
    {
        return $this->clientGroupRepository->store($request->input());
    }

    public function update(UpdateClientGroupRequest $request)
    {
        return $this->clientGroupRepository->update($request->input());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->clientGroupRepository->getPage(request()->page_size, $text);
    }
    public function getAllClients()
    {
        return $this->clientRepository->getAllClients();
    }
}
