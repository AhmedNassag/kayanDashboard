<?php

namespace App\Repositories;

use App\Models\AboutInformation;

class AboutInformationRepository
{
    public function update($aboutSectionInput)
    {
        $aboutSection = AboutInformation::find($aboutSectionInput["id"]);
        $oldImage = $aboutSection->image;
        $aboutSectionInput["image"] = $aboutSectionInput["image"] ?? $oldImage;
        $this->edit($aboutSection, $aboutSectionInput);
        return ["old_image" => $oldImage, "about_section" => $aboutSection];
    }

    public function getAboutSections()
    {
        return AboutInformation::get();
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
