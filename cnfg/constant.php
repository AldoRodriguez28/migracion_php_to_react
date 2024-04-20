<?php
if(!defined('ENVIROMENT_MODE')) define("ENVIROMENT_MODE", "PRODUCTION");

if(ENVIROMENT_MODE!="TEST" && ENVIROMENT_MODE!="PRODUCTION" && ENVIROMENT_MODE!="TEST_PRODUCTION") die("Error Fatal in ENVIROMENT_MODE, line 0");

if(!defined('TIEMPO_EXTRA')) define("TIEMPO_EXTRA", "01:00:00");

switch(ENVIROMENT_MODE){
	case "TEST":
		
		if(!defined('BD_HOST_TDA')) define("BD_HOST_TDA", "10.44.172.49");
		if(!defined('BD_TDA')) define("BD_TDA", "tiendavirtual");
		if(!defined('BD_USER_TDA')) define("BD_USER_TDA", "sysecommerce");
		if(!defined('BD_PASSWORD_TDA')) define("BD_PASSWORD_TDA", "ykp25OcQc0wvDwYCAvRpeI8a80mJtLH5");
		if(!defined('BD_TYPE_TDA')) define("BD_TYPE_TDA", "pg");
		if(!defined('BD_PORT_TDA')) define("BD_PORT_TDA", "5432");
		break;
	case "TEST_PRODUCTION":
		//TDA
		if(!defined('BD_HOST_TDA')) define("BD_HOST_TDA", "10.0.4.58");
		if(!defined('BD_TDA')) define("BD_TDA", "tiendavirtual");
		if(!defined('BD_USER_TDA')) define("BD_USER_TDA", "sysecommerce");
		if(!defined('BD_PASSWORD_TDA')) define("BD_PASSWORD_TDA", "ykp25OcQc0wvDwYCAvRpeI8a80mJtLH5");
		if(!defined('BD_TYPE_TDA')) define("BD_TYPE_TDA", "pg");
		if(!defined('BD_PORT_TDA')) define("BD_PORT_TDA", "5466");
		break;
	case "PRODUCTION":
		
		if(!defined('BD_HOST_TDA')) define("BD_HOST_TDA", "bdecommerce1.coppel.com");
		if(!defined('BD_TDA')) define("BD_TDA", "tiendavirtual");
		if(!defined('BD_USER_TDA')) define("BD_USER_TDA", "sysecommerce");
		if(!defined('BD_PASSWORD_TDA')) define("BD_PASSWORD_TDA", "ykp25OcQc0wvDwYCAvRpeI8a80mJtLH5");
		if(!defined('BD_TYPE_TDA')) define("BD_TYPE_TDA", "pg");
		if(!defined('BD_PORT_TDA')) define("BD_PORT_TDA", "5432");		
		break;
}
?>