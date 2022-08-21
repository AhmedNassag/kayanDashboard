<?php

namespace App\Repositories;

use App\Models\Offer;

class OfferRepository
{
    public function store($offer)
    {
        return Offer::create($offer);
    }

    public function update($offerInput)
    {
        $offer = Offer::find($offerInput["id"]);
        $offer->name = $offerInput["name"];
        $offer->discount = $offerInput["discount"];
        $offer->ratio = $offerInput["ratio"];
        $offer->start_date = $offerInput["start_date"];
        $offer->end_date = $offerInput["end_date"];
        $offer->save();
        return $offer;
    }

    public function delete($id)
    {
        $offer = Offer::find($id);
        if ($offer) {
            $offer->delete();
        }
    }
    public function getPage($pageSize, $text)
    {
        return offer::where("name", "like", "%$text%")
            ->orWhere("discount", $text)
            ->paginate($pageSize);
    }
    public function toggleActivation($id)
    {
        $offer = Offer::find($id);
        $offer->active = !$offer->active;
        $offer->save();
    }
}
