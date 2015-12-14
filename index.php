<?php


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   init.php contains defines basic BASE_DIR and URL(s).             +
    +   It also imports:                                                 +
    +   config/constants.php                                             +
    +   config/autoloader.php                                            +
    +   config/routes.php                                                +
    +   config/helperFunctions.php                                       +
    +   config/class_alias.php                                           +
    +--------------------------------------------------------------------+

*/

require_once __DIR__ . '/config/init.php';
require_once BASE_DIR . '/vendor/autoload.php';

/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   Blacklisted Users do not get to view the resource. Edit this     +
    +   on config/constants.php (FORBID_BLACKLISTED_USERS)               +
    +   tracker() is aliased as hooks\Services\ServerTracker         +
    +                                                                    +
    +--------------------------------------------------------------------+

*/


if(FORBID_BLACKLISTED_USERS){
    if(tracker()->isBlacklisted()){
        tracker()->kill();
    }
}


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   Everything Clear? Let's Route the URL from Route class below:    +
    +   Route class is aliased as hooks\MVC\Route                    +
    +                                                                    +
    +--------------------------------------------------------------------+

*/

route()->setRoutes(routes)->deliver();


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   If TRACK_RESOURCE is enabled, we track Url, IP, User agent and   +
    +   and the time taken to deliver the resources.                      +
    +                                                                    +
    +--------------------------------------------------------------------+

*/

if(TRACK_RESOURCE){
    tracker()->track();
}