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


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   Blacklisted Users do not get to view the resource. Edit this     +
    +   on config/constants.php (FORBID_BLACKLISTED_USERS)               +
    +   ServerTracker is aliased as Framework\Services\ServerTracker     +
    +                                                                    +
    +--------------------------------------------------------------------+

*/


if(FORBID_BLACKLISTED_USERS){
    if(ServerTracker::isBlacklisted()){
        die(
            "<h1>Ooops!</h1>
            We are sorry but you are blacklisted in our server.
            Please send a written application to our server admin (".SERVER_ADMIN.")
            explaining why it happened.
            We will investigate and look if you can be re-enlisted."
        );
    }
}


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   Everything Clear? Let's Route the URL from Route class below:    +
    +   Route class is aliased as Framework\MVC\Route                    +
    +                                                                    +
    +--------------------------------------------------------------------+

*/

Route::setRoutes($routes);
Route::route();


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   If TRACK_RESOURCE is enabled, we track Url, IP, User agent and   +
    +   and the time taken to deliver the resources.                      +
    +                                                                    +
    +--------------------------------------------------------------------+

*/

if(TRACK_RESOURCE){
    ServerTracker::track();
}