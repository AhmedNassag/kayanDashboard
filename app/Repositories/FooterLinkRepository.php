<?php

namespace App\Repositories;

use App\Models\FooterLink;

class FooterLinkRepository
{
    public function update($footerLinkInput)
    {
        $footerLink = FooterLink::find($footerLinkInput["id"]);
        $oldImage = $footerLink->image;
        $footerLink->content = $footerLinkInput["content"];
        $footerLink->image = $footerLinkInput["image"] ?? $oldImage;
        $footerLink->save();
        return ["old_image" => $oldImage, "footer_link" => $footerLink];
    }

    public function getFooterLinks()
    {
        return FooterLink::get();
    }
}
