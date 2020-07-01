<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model query()
 * @mixin \Eloquent
 */
class Client extends Model
{
    protected $fillable = [
        'name'
    ];
}
