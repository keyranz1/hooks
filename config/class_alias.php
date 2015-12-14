<?php


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   Class aliases let you use classes Directly without :             +
    +   "use \hooks\Level\Class" statement                           +
    +                                                                    +
    +--------------------------------------------------------------------+

*/


class_alias("hooks\\MVC\\Route", "Route");
class_alias("hooks\\MVC\\Controller", "Controller");
class_alias("hooks\\MVC\\Redirect", "Redirect");
class_alias("hooks\\MVC\\View", "View");
class_alias("hooks\\Storage\\Cookie", "Cookie");
class_alias("hooks\\Storage\\File", "File");
class_alias("hooks\\Storage\\Globals", "Globals");
class_alias("hooks\\Storage\\Session", "Session");
class_alias("hooks\\Storage\\DB", "DB");
class_alias("hooks\\Storage\\DBObject", "DBOject");
class_alias("hooks\\Mail\\PHPMail", "Mail");
class_alias("hooks\\Services\\Image", "Image");
class_alias("hooks\\Services\\ServerTracker", "ServerTracker");