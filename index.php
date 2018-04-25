<?php
define("URLROOT","/Blog/");
define("DOCROOT",$_SERVER["DOCUMENT_ROOT"].URLROOT);

define("APP_PATH",DOCROOT."application/");
define("CORE_PATH",DOCROOT."core/");

define("MEDIA_URL",URLROOT."media/");
define("MEDIA_PATH",DOCROOT."media/");

define("CONTROLLERS_PATH",APP_PATH."controllers/");
define("MODELS_PATH",APP_PATH."models/");
define("VIEWS_PATH",APP_PATH."views/");
define("TEMPLATES_PATH",APP_PATH."templates/");
define("APP_CONFIG_PATH",APP_PATH."config/");

define("CLASS_PATH",CORE_PATH."classes/");
define("CONFIG_PATH",CORE_PATH."config/");
define("MODULES_PATH",CORE_PATH."modules/");

include DOCROOT."vendor/autoload.php";

include CORE_PATH."loader.php";
