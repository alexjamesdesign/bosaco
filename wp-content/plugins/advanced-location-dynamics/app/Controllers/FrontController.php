<?php

namespace Adtrak\AdvancedLocationDynamics\Controllers;

use Adtrak\AdvancedLocationDynamics\Controllers\LDController;

class FrontController extends Controller
{

    private $_ldController;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->_ldController = new LDController;
    }

}
