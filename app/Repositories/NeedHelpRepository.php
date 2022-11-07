<?php

namespace App\Repositories;

use App\Models\NeedHelp;

class NeedHelpRepository
{
    public function save($needHelpInput)
    {
        $needHelp = NeedHelp::first();
        !$needHelp ? NeedHelp::create($needHelpInput) : $this->update($needHelp, $needHelpInput);
    }
    public function getNeedHelp()
    {
        return NeedHelp::first();
    }
    //Commons
    private function update(&$needHelp, $needHelpInput)
    {
        $needHelp->header = $needHelpInput["header"];
        $needHelp->start_work_deadline = $needHelpInput["start_work_deadline"];
        $needHelp->end_work_deadline = $needHelpInput["end_work_deadline"];
        $needHelp->email = $needHelpInput["email"];
        $needHelp->save();
    }
}
