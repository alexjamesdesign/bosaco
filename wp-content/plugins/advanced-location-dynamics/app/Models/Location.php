<?php
namespace Adtrak\AdvancedLocationDynamics\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Location extends Eloquent
{
    
    protected $table = 'ald_locations';
    public $timestamps = false;

    protected $fillable = [
        'location',
        'ids',
        'area_code',
        'country'
    ];

}