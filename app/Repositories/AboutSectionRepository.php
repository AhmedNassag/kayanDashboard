<?php

namespace App\Repositories;

use App\Models\AboutSection;

class AboutSectionRepository
{
    public function update($aboutSectionInput)
    {
        $aboutSection = AboutSection::find($aboutSectionInput["id"]);
        $oldImage = $aboutSection->image;
        $aboutSectionInput["image"] = $aboutSectionInput["image"] ?? $oldImage;
        $this->edit($aboutSection, $aboutSectionInput);
        return ["old_image" => $oldImage, "about_section" => $aboutSection];
    }

    public function getAboutSections()
    {
        return AboutSection::get();
    }

    //Commons
    private function edit(&$aboutSection, $aboutSectionInput)
    {
        //There are four sections
        $aboutSection->header = $aboutSectionInput["header"];
        $aboutSection->sub_header = $aboutSectionInput["sub_header"];
        $aboutSection->image = $aboutSectionInput["image"];
        $aboutSection->save();
    }
}
