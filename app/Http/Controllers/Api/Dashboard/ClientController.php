<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\KnowUsWay;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{

    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->middleware('permission:client read', ['only' => ['index']]);
        $this->middleware('permission:client create', ['only' => ['store']]);
        $this->middleware('permission:client edit', ['only' => ['update', 'toggleActivation']]);
        $this->middleware('permission:client delete', ['only' => ['delete']]);
    }

    public function store(StoreClientRequest $request)
    {
        return $this->clientRepository->store($request->input());
    }

    public function update(UpdateClientRequest $request)
    {
        return $this->clientRepository->update($request->input());
    }

    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->clientRepository->getPage(request()->page_size, $text);
    }

    public function toggleActivation($id)
    {
        $this->clientRepository->toggleActivation($id);
    }
    public function getCitiesWithAreas()
    {
        return $this->clientRepository->getCitiesWithAreas();
    }
    public function getKnowusWays()
    {
        return $this->clientRepository->getKnowusWays();
    }
}
