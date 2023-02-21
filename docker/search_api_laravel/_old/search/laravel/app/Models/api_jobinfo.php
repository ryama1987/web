<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api_jobinfo extends Model
{
    use HasFactory;

    //Laravelでは、モデルは単数、テーブルは複数形のネーミングだが、例外もある。
    //この場合は、以下のようにテーブル名を指定する。
    protected $table = 'api_jobinfo';
}
