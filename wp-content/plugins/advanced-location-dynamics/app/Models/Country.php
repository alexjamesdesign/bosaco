<?php
namespace Adtrak\AdvancedLocationDynamics\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Country extends Eloquent
{
    
    protected $table = 'ald_countries';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'filename',
        'last_updated',
        'last_checked'
    ];

}