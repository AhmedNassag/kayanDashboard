<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\UnavailableCityClientRepository;

class UnavailableCityClientController extends Controller
{
    private $unavailableCityClientRepository;
    function __construct(UnavailableCityClientRepository $unavailableCityClientRepository)
    {
        $this->middleware('permission:unavailable-city-client read');
        $this->unavailableCityClientRepository = $unavailableCityClientRepository;
    }

    public function getUnavailableCitiesClients()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->unavailableCityClientRepository
            ->getUnavailableCitiesClients(request()->page_size, $text);
    }
    
    public function getAllUnavailableCitiesClients()
    {
        return $this->unavailableCityClientRepository->getAllUnavailableCitiesClients();
    }
}
