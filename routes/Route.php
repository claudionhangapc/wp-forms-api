<?php

namespace Routes;

use Routes\PingRoute;
use Routes\UserRoute;

class Route
{

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
	 * Setup action & filter hooks.
	 */
    public function init()
    {
        (new PingRoute($this->name))->initRoutes();
        (new UserRoute($this->name))->initRoutes();      
    }


}
