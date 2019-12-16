<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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


$hostname = "localhost";
$username = "root";
$password = "";


$security   = "isib_security";
$empcenter  = "isib_employee";
$public   = "isib_public";
$fieldpayroll   = "isib_fieldpayroll";
$estimates  = "isib_estimates";
$attendance   = "isib_attendance";
$attendance_apps   = "isib_attendance";
$attendancehis = 'isib_attendance_history';

$empcenterhis  = "isib_employee_history";
$publichis   = "isib_public_history";




$active_group = "default";
$active_record = TRUE;
$db['default']['hostname'] = $hostname;
$db['default']['username'] = $username;
$db['default']['password'] = $password;
$db['default']['database'] = $security;
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
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
$db['security']['pconnect'] = FALSE;
$db['security']['db_debug'] = TRUE;
$db['security']['cache_on'] = FALSE;
$db['security']['cachedir'] = "";
$db['security']['char_set'] = "utf8";
$db['security']['dbcollat'] = "utf8_general_ci";
$db['security']['client_flags'] = 128;


$active_group = "attendance";
$active_record = TRUE;
$db['attendance']['hostname'] = $hostname;
$db['attendance']['username'] = $username;
$db['attendance']['password'] = $password;
$db['attendance']['database'] = $attendance;
$db['attendance']['dbdriver'] = "mysql";
$db['attendance']['dbprefix'] = "";
$db['attendance']['pconnect'] = FALSE;
$db['attendance']['db_debug'] = TRUE;
$db['attendance']['cache_on'] = FALSE;
$db['attendance']['cachedir'] = "";
$db['attendance']['char_set'] = "utf8";
$db['attendance']['dbcollat'] = "utf8_general_ci";
$db['attendance']['client_flags'] = 128;

$db['attendance_apps']['hostname'] = $hostname;
$db['attendance_apps']['username'] = $username;
$db['attendance_apps']['password'] = $password;
$db['attendance_apps']['database'] = $attendance_apps;
$db['attendance_apps']['dbdriver'] = "mysql";
$db['attendance_apps']['dbprefix'] = "";
$db['attendance_apps']['pconnect'] = FALSE;
$db['attendance_apps']['db_debug'] = TRUE;
$db['attendance_apps']['cache_on'] = FALSE;
$db['attendance_apps']['cachedir'] = "";
$db['attendance_apps']['char_set'] = "utf8";
$db['attendance_apps']['dbcollat'] = "utf8_general_ci";
$db['attendance_apps']['client_flags'] = 128;


$db['attendancehis']['hostname'] = $hostname;
$db['attendancehis']['username'] = $username;
$db['attendancehis']['password'] = $password;
$db['attendancehis']['database'] = $attendancehis;
$db['attendancehis']['dbdriver'] = "mysql";
$db['attendancehis']['dbprefix'] = "";
$db['attendancehis']['pconnect'] = FALSE;
$db['attendancehis']['db_debug'] = TRUE;
$db['attendancehis']['cache_on'] = FALSE;
$db['attendancehis']['cachedir'] = "";
$db['attendancehis']['char_set'] = "utf8";
$db['attendancehis']['dbcollat'] = "utf8_general_ci";
$db['attendancehis']['client_flags'] = 128;


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



//$active_group = "logistic";
//$active_record = TRUE;

$db['fieldpayroll']['hostname'] = $hostname;
$db['fieldpayroll']['username'] = $username;
$db['fieldpayroll']['password'] = $password;
$db['fieldpayroll']['database'] = $fieldpayroll;
$db['fieldpayroll']['dbdriver'] = "mysql";
$db['fieldpayroll']['dbprefix'] = "";
$db['fieldpayroll']['pconnect'] = FALSE;
$db['fieldpayroll']['db_debug'] = TRUE;
$db['fieldpayroll']['cache_on'] = FALSE;
$db['fieldpayroll']['cachedir'] = "";
$db['fieldpayroll']['char_set'] = "utf8";
$db['fieldpayroll']['dbcollat'] = "utf8_general_ci";
$db['fieldpayroll']['client_flags'] = 128;



$db['empcenterhis']['hostname'] = $hostname;
$db['empcenterhis']['username'] = $username;
$db['empcenterhis']['password'] = $password;
$db['empcenterhis']['database'] = $empcenterhis;
$db['empcenterhis']['dbdriver'] = "mysql";
$db['empcenterhis']['dbprefix'] = "";
$db['empcenterhis']['pconnect'] = FALSE;
$db['empcenterhis']['db_debug'] = TRUE;
$db['empcenterhis']['cache_on'] = FALSE;
$db['empcenterhis']['cachedir'] = "";
$db['empcenterhis']['char_set'] = "utf8";
$db['empcenterhis']['dbcollat'] = "utf8_general_ci";
$db['empcenterhis']['client_flags'] = 128;



$db['publichis']['hostname'] = $hostname;
$db['publichis']['username'] = $username;
$db['publichis']['password'] = $password;
$db['publichis']['database'] = $publichis;
$db['publichis']['dbdriver'] = "mysql";
$db['publichis']['dbprefix'] = "";
$db['publichis']['pconnect'] = FALSE;
$db['publichis']['db_debug'] = TRUE;
$db['publichis']['cache_on'] = FALSE;
$db['publichis']['cachedir'] = "";
$db['publichis']['char_set'] = "utf8";
$db['publichis']['dbcollat'] = "utf8_general_ci";
$db['publichis']['client_flags'] = 128;




/* End of file database.php */
/* Location: ./application/config/database.php */