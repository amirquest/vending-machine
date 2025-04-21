<?php

namespace App\Models;

use App\Services\Logger\Contracts\InteractsWithLoggerTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    use HasFactory;
    use InteractsWithLoggerTrait;

    protected $connection = 'mysql';

    protected $fillable = [
        'from',
        'to',
    ];
}
