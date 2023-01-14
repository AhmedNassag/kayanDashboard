<?php

namespace App\Repositories;

use App\Models\Newsletter;

class NewsletterRepository
{
    public function getPage($pageSize, $text)
    {
        return Newsletter::where("email", "like", "%$text%")->orWhereDate("created_at", "like", "%$text%")->paginate($pageSize);
    }
    public function getNewsletters()
    {
        return Newsletter::get(["id", "email"]);
    }
}
