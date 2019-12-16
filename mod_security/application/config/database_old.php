<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/
$active_group = "default";
$active_record = TRUE;

$hostname = "localhost";
$username = "riadi";
$password = "qwe123";

$security   = "riadi_triasnet_security";
$empcenter  = "riadi_triasnet_employee";
$estimasi   = "riadi_triasnet_estimation";
$public   = "riadi_triasnet_public";
$logistic   = "riadi_triasnet_logistic";

$db['default']['hostname'] = $hostname;
$db['default']['username'] = $username;
$db['default']['password'] = $password;
$db['default']['database'] = $security;
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['client_flags'] = 128;

$db['security']['hostname'] = $hostname;
$db['security']['username'] = $username;
$db['security']['password'] = $password;
$db['security']['database'] = $security;
$db['security']['dbdriver'] = "mysql";
$db['security']['dbprefix'] = "";
$db['security']['pconnect'] = TRUE;
$db['security']['db_debug'] = TRUE;
$db['security']['cache_on'] = FALSE;
$db['security']['cachedir'] = "";
$db['security']['char_set'] = "utf8";
$db['security']['dbcollat'] = "utf8_general_ci";
$db['security']['client_flags'] = 128;

$db['empcenter']['hostname'] = $hostname;
$db['empcenter']['username'] = $username;
$db['empcenter']['password'] = $password;
$db['empcenter']['database'] = $empcenter;
$db['empcenter']['dbdriver'] = "mysql";
$db['empcenter']['dbprefix'] = "";
$db['empcenter']['pconnect'] = FALSE;
$db['empcenter']['db_debug'] = TRUE;
$db['empcenter']['cache_on'] = FALSE;
$db['empcenter']['cachedir'] = "";
$db['empcenter']['char_set'] = "utf8";
$db['empcenter']['dbcollat'] = "utf8_general_ci";
$db['empcenter']['client_flags'] = 128;

$db['estimasi']['hostname'] = $hostname;
$db['estimasi']['username'] = $username;
$db['estimasi']['password'] = $password;
$db['estimasi']['database'] = $estimasi;
$db['estimasi']['dbdriver'] = "mysql";
$db['estimasi']['dbprefix'] = "";
$db['estimasi']['pconnect'] = FALSE;
$db['estimasi']['db_debug'] = TRUE;
$db['estimasi']['cache_on'] = FALSE;
$db['estimasi']['cachedir'] = "";
$db['estimasi']['char_set'] = "utf8";
$db['estimasi']['dbcollat'] = "utf8_general_ci";
$db['estimasi']['client_flags'] = 128;


$db['public']['hostname'] = $hostname;
$db['public']['username'] = $username;
$db['public']['password'] = $password;
$db['public']['database'] = $public;
$db['public']['dbdriver'] = "mysql";
$db['public']['dbprefix'] = "";
$db['public']['pconnect'] = FALSE;
$db['public']['db_debug'] = TRUE;
$db['public']['cache_on'] = FALSE;
$db['public']['cachedir'] = "";
$db['public']['char_set'] = "utf8";
$db['public']['dbcollat'] = "utf8_general_ci";
$db['public']['client_flags'] = 128;


$db['logistic']['hostname'] = $hostname;
$db['logistic']['username'] = $username;
$db['logistic']['password'] = $password;
$db['logistic']['database'] = $logistic;
$db['logistic']['dbdriver'] = "mysql";
$db['logistic']['dbprefix'] = "";
$db['logistic']['pconnect'] = FALSE;
$db['logistic']['db_debug'] = TRUE;
$db['logistic']['cache_on'] = FALSE;
$db['logistic']['cachedir'] = "";
$db['logistic']['char_set'] = "utf8";
$db['logistic']['dbcollat'] = "utf8_general_ci";
$db['logistic']['client_flags'] = 128;

/* End of file database.php */
/* Location: ./application/config/database.php */
