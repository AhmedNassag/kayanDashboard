<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterLinkRequest;
use App\Repositories\FooterLinkRepository;
use Illuminate\Support\Facades\Storage;

class FooterLinkController extends Controller
{
    private $footerLinkRepository;
    public function __construct(FooterLinkRepository $footerLinkRepository)
    {
        $this->middleware('permission:footer read', ['only' => ['index']]);
        $this->middleware('permission:footer edit', ['only' => ['update']]);
        $this->footerLinkRepository = $footerLinkRepository;
    }

    public function update(FooterLinkRequest $request)
    {
        if ($request->file("image")) {
            $request->merge(["image" => $request->file("image")->store('footer-links', 'general')]);
        }
        $updateResult = $this->footerLinkRepository->update($request->input());
        if ($request->file("image") && $updateResult["old_image"]) {
            Storage::disk('general')->delete($updateResult["old_image"]);
        }
        return $updateResult["footer_link"];
    }

    public function index()
    {
        return $this->footerLinkRepository->getFooterLinks();
    }
}
