<?php

namespace App\Repositories;

use App\Models\TopFooterSection;

class TopFooterSectionRepository
{
    public function save($topFooterSectionInput)
    {
        $topFooterSection = TopFooterSection::first();
        !$topFooterSection ? TopFooterSection::create($topFooterSectionInput) : $this->update(
            $topFooterSection,
            $topFooterSectionInput
        );
    }
    public function getTopFooterSections()
    {
        return TopFooterSection::first();
    }
    //Commons
    private function update(&$topFooterSection, $topFooterSectionInput)
    {
        $topFooterSection->first_section = $topFooterSectionInput["first_section"] ?? null;
        $topFooterSection->second_section = $topFooterSectionInput["second_section"] ?? null;
        $topFooterSection->third_section = $topFooterSectionInput["third_section"] ?? null;
        $topFooterSection->save();
    }
}
