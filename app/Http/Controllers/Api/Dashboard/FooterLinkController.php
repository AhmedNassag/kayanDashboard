<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\FooterLinkRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FooterLinkController extends Controller
{
    private $footerLinkRepository;
    public function __construct(FooterLinkRepository $footerLinkRepository)
    {
        $this->middleware('permission:footer-link read', ['only' => ['index']]);
        $this->middleware('permission:footer-link edit', ['only' => ['update']]);
        $this->footerLinkRepository = $footerLinkRepository;
    }

    public function update(Request $request)
    {
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store('footer-links', 'general');
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->footerLinkRepository->update($request->input());
        if ($request->file("image")) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["footer_link"];
    }

    public function index()
    {
        return $this->footerLinkRepository->getFooterLinks();
    }
}
