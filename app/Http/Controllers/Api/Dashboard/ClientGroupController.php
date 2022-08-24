<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientGroupRequest;
use App\Http\Requests\UpdateClientGroupRequest;
use App\Repositories\ClientGroupRepository;

class ClientGroupController extends Controller
{
    private $clientGroupRepository;
    public function __construct(ClientGroupRepository $clientGroupRepository)
    {
        $this->clientGroupRepository = $clientGroupRepository;
        $this->middleware('permission:client-group read', ['only' => ['index']]);
        $this->middleware('permission:client-group create', ['only' => ['store']]);
        $this->middleware('permission:client-group edit', ['only' => ['update']]);
        $this->middleware('permission:client-group delete', ['only' => ['delete']]);
    }
    public function store(StoreClientGroupRequest $request)
    {
        return $this->clientGroupRepository->store($request->input());
    }

    public function update(UpdateClientGroupRequest $request)
    {
        return $this->clientGroupRepository->update($request->input());
    }

    public function delete($id)
    {
        $this->clientGroupRepository->delete($id);
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->clientGroupRepository->getPage(request()->page_size, $text);
    }
}
