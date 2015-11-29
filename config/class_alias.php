<?php


/*
    +--------------------------------------------------------------------+
    +                                                                    +
    +   Class aliases let you use classes Directly without :             +
    +   "use \Framework\Level\Class" statement                           +
    +                                                                    +
    +--------------------------------------------------------------------+

*/


class_alias("Framework\\MVC\\Route", "Route");
class_alias("Framework\\MVC\\Controller", "Controller");
class_alias("Framework\\MVC\\Redirect", "Redirect");
class_alias("Framework\\MVC\\View", "View");
class_alias("Framework\\Storage\\Cookie", "Cookie");
class_alias("Framework\\Storage\\File", "File");
class_alias("Framework\\Storage\\Globals", "Globals");
class_alias("Framework\\Storage\\Session", "Session");
class_alias("Framework\\Storage\\DB", "DB");
class_alias("Framework\\Storage\\DBObject", "DBOject");
class_alias("Framework\\Mail\\PHPMail", "Mail");
class_alias("Framework\\Services\\Image", "Image");
class_alias("Framework\\Services\\ServerTracker", "ServerTracker");