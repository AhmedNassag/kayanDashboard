<?php

namespace App\Commons\Consts;

class SupplierType
{
    const STORE = "STORE";
    const COMPANY = "COMPANY";
    const ALL = self::STORE . "," . self::COMPANY;
}
