<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\NewsletterRepository;

class NewsletterController extends Controller
{
    private $newsletterRepository;
    function __construct(NewsletterRepository $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
        $this->middleware('permission:newsletter read', ['only' => ['index']]);
    }
    public function index()
    {
        $text = isset(request()->text) ? request()->text : '';
        return $this->newsletterRepository->getPage(request()->page_size, $text);
    }
    public function getNewsletters()
    {
        return $this->newsletterRepository->getNewsletters();
    }
}
