<?php

define('ENV_DEVELOPMENT', true);

define('REGISTER',false);
define('REGISTER_LEVEL',3);
define("USERS_TABLE","users");
define("DEFAULT_VIEW",null);
define("VIEW_404","errors/404");
define("VIEWS_EXTENSION", ".php");

define("SERVER_ADMIN", "admin@" . $_SERVER['HTTP_HOST']);
define("MAX_CPU_TIME",2); //Seconds
define("TRACK_RESOURCE", false);
define("FORBID_BLACKLISTED_USERS", false);

define("ITEMS_PER_PAGE",12);

define("COOKIE_CURRENCY", "cu1af0389838508d7016a9841eb6273962");
define("COOKIE_CART", "ca54013ba69c196820e56801f1ef5aad54");
define("COOKIE_CLIENT", "cl62608e08adc29a8d6dbc9754e659f125");
define("COOKIE_COUNTRY", "coe909c2d7067ea37437cf97fe11d91bd0");
define("COOKIE_DEVELOPMENT", "de759b74ce43947f5f4c91aeddc3e5bad3");
define("COOKIE_WISH_LIST", "wi27e76ef6b60400df7c6bedfb807191d6");


define("NOTIFY_ADMIN_EMAIL", "pahadi@live.com");
define("NOTIFY_DEVELOPER_EMAIL", "pahadi@live.com");

define("DATE_FORMAT_SUPER_LONG","l, Y/m/d h:i a");
define("DATE_FORMAT_LONG","Y/m/d h:i a");
define("DATE_FORMAT_SHORT","Y/m/d");

define("DATE_FORMAT_LONG_HTML5","Y-m-d h:i a");
define("DATE_FORMAT_SHORT_HTML5","Y-m-d");



define("CUSTOM_CAT_ID", 6);
define("PATTERN_CAT_ID", 8);


/*
   +------------------------------------------------------------------------------+
   +                                                                              +
   +   Facebook & Instagram API and App Secrets                                   +
   +                                                                              +
   +------------------------------------------------------------------------------+

*/

define("FACEBOOK_APP_ID","702881203146204");
define("FACEBOOK_APP_SECRET","be977787b5ea35aa2bc14d52a78e2a1d");


/* App Data */
define("INSTAGRAM_APP_ID","bfd815ad2a954e26af3bbcb124c46004");
define("INSTAGRAM_APP_SECRET","b7cf48a7b2c046f6aae0eeb7e2ddcb81");


/* App Auther And User Data */
define("INSTAGRAM_TOKEN","1302207764.bfd815a.e4a7e3ffed9140b89c2a06b3acac6d65");
define("INSTAGRAM_UID","1337199209");


define("GALLERY_PATH", "assets/gallery");
define("DEFAULT_PLACEHOLDER_IMAGE", "assets/images/placeholder.jpg");