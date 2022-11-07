<?php

namespace App\Repositories;

use App\Models\OurStore;

class OurStoreRepository
{
    public function save($ourStoreInput)
    {
        $ourStore = OurStore::first();
        !$ourStore ? OurStore::create($ourStoreInput) : $this->update($ourStore, $ourStoreInput);
    }
    public function getOurStore(){
        return OurStore::first();
    }
    //Commons
    private function update(&$ourStore, $ourStoreInput)
    {
        $ourStore->first_line = $ourStoreInput["first_line"];
        $ourStore->second_line = $ourStoreInput["second_line"];
        $ourStore->facebook = $ourStoreInput["facebook"] ?? null;
        $ourStore->instgram = $ourStoreInput["instgram"] ?? null;
        $ourStore->youtube = $ourStoreInput["youtube"] ?? null;
        $ourStore->twitter = $ourStoreInput["twitter"] ?? null;
        $ourStore->linked_in = $ourStoreInput["linked_in"] ?? null;
        $ourStore->save();
    }
}
