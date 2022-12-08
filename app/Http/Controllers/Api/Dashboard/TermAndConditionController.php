<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\TermAndConditionRequest;
use App\Models\TermAndCondition;

class TermAndConditionController extends Controller
{
    public function getTermsAndConditions()
    {
        return TermAndCondition::first();
    }

    public function storeTermsAndConditions(TermAndConditionRequest $request)
    {
        $termAndCondition = TermAndCondition::first();
        if ($termAndCondition) {
            $termAndCondition->update(collect($request->validated())->filter()->toArray());
        } else {
            TermAndCondition::create($request->input());
        }
    }

}
