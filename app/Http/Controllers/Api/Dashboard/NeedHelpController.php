<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\NeedHelpRequest;
use App\Models\NeedHelp;
use App\Repositories\NeedHelpRepository;

class NeedHelpController extends Controller
{
    private $needHelpRepository;
    public function __construct(NeedHelpRepository $needHelpRepository)
    {
        $this->middleware('permission:footer read');
        $this->needHelpRepository = $needHelpRepository;
    }

    public function save(NeedHelpRequest $request)
    {
        $this->needHelpRepository->save($request->validated());
    }

    public function getNeedHelp(){
        return $this->needHelpRepository->getNeedHelp();
    }
}
