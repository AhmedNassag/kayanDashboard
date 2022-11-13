<?php

namespace App\Repositories;

use App\Models\AboutBanner;

class AboutBannerRepository
{
    public function update($aboutBannerInput)
    {
        $aboutBanner = AboutBanner::find($aboutBannerInput["id"]);
        $oldImage = $aboutBanner->image;
        $oldVideo = $aboutBanner->video;
        $aboutBannerInput["image"] = $aboutBannerInput["image"] ?? $oldImage;
        $aboutBannerInput["video"] = $aboutBannerInput["video"] ?? $oldVideo;
        $this->edit($aboutBanner, $aboutBannerInput);
        return ["old_image" => $oldImage,"old_video" => $oldVideo, "about_banner" => $aboutBanner];
    }
    public function getAboutBanners()
    {
        return AboutBanner::get();
    }
    //Commons
    private function edit(&$aboutBanner, $aboutBannerInput)
    {
        //There are three banners
        $aboutBanner->header = $aboutBannerInput["header"];
        $aboutBanner->button_label = $aboutBannerInput["button_label"];
        $aboutBanner->url = $aboutBannerInput["url"];
        $aboutBanner->image = $aboutBannerInput["image"];
        $aboutBanner->video = $aboutBannerInput["video"];
        $aboutBanner->sub_header = $aboutBannerInput["sub_header"] ?? null; //For last banner only
        $aboutBanner->first_text = $aboutBannerInput["first_text"] ?? null; //For first & last banner
        $aboutBanner->second_text = $aboutBannerInput["second_text"] ?? null; //For first & last banner
        $aboutBanner->color = $aboutBannerInput["color"] ?? null; //For first & second banner
        $aboutBanner->text = $aboutBannerInput["text"] ?? null; //for second banner
        $aboutBanner->save();
    }
}
