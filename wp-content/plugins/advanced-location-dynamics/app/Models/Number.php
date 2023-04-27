<?php

namespace Adtrak\AdvancedLocationDynamics\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Number extends Eloquent
{

    protected $table = 'ald_numbers';

    protected $fillable = [
        'location_name',
        'location_label',
        'calltag',
        'seo',
        'ppc',
        'insights',
    ];
}
