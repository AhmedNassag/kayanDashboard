<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\OurStoreRequest;
use App\Repositories\OurStoreRepository;

class OurStoreController extends Controller
{
    private $ourStoreRepository;
    public function __construct(OurStoreRepository $ourStoreRepository)
    {
        $this->middleware('permission:footer read');
        $this->ourStoreRepository = $ourStoreRepository;
    }

    public function save(OurStoreRequest $request)
    {
        $this->ourStoreRepository->save($request->input());
    }

    public function getOurStore()
    {
        return $this->ourStoreRepository->getOurStore();
    }
    
}
