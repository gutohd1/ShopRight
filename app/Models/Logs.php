<?php

namespace App\Models;

use App\Models\Base\Model;

class Logs extends Model
{
    protected function write($content): bool
    {
        var_dump('hit here');
        return parent::write();
    }
}
