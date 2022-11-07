<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\TopFooterSectionRepository;
use Illuminate\Http\Request;

class TopFooterSectionController extends Controller
{
    private $topFooterSectionRepository;
    public function __construct(TopFooterSectionRepository $topFooterSectionRepository)
    {
        $this->middleware('permission:footer read');
        $this->topFooterSectionRepository = $topFooterSectionRepository;
    }

    public function save(Request $request)
    {
        $this->topFooterSectionRepository->save($request->input());
    }

    public function getTopFooterSections()
    {
        return $this->topFooterSectionRepository->getTopFooterSections();
    }
}
