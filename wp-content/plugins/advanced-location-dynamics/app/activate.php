<?php 

/**
 * @var \Billy\Framework\Enqueue $enqueue 
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Adtrak\AdvancedLocationDynamics\Helper;

/**
 * Create countries table
 */
if (!Capsule::schema()->hasTable('ald_countries')) {
    Capsule::schema()->create(
        'ald_countries', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->string('filename');
            $table->integer('installed')->default(0);
            $table->datetime('last_updated');
            $table->datetime('last_checked');
            $table->timestamps();
        }
    );
    Helper::processCountries();
}

/**
 * Create locations table
 */
if (!Capsule::schema()->hasTable('ald_locations')) {
    Capsule::schema()->create(
        'ald_locations', function ($table) {
            $table->increments('id');
            $table->string('location');
            $table->text('ids');
            $table->string('area_code');
            $table->string('country');
            $table->timestamps();
        }
    );
    Helper::processCountryPack('csv/uk_locations.csv');
}

/**
 * Create location dynamics table
 */
if (!Capsule::schema()->hasTable('ald_numbers')) {
    Capsule::schema()->create(
        'ald_numbers', function ($table) {
            $table->increments('id');
            $table->string('location_name');
            $table->string('location_label')->nullable();
            $table->string('calltag')->nullable();
            $table->string('seo')->nullable();
            $table->string('ppc')->nullable();
            $table->string('insights')->nullable();
            $table->string('tracking_label')->nullable();
            $table->timestamps();
        }
    );
}

if (get_option('ald_advanced') === null) {
    add_option('ald_advanced', 0);
    add_option('ald_advanced_number', '0800 000 0000');
}