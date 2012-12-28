<?php

/**
 * @category  configuration
 * @author    John Mitros
 * @copyright 2012
 */
 
// CB_SITE_ROOT contains the full path to the cbires folder
define('CB_SITE_ROOT', dirname(dirname(__FILE__)));
// Application directories
define('CB_INCLUDES_DIR', CB_SITE_ROOT.'/includes/');
define('CB_DB_DIR', CB_SITE_ROOT.'/db/');
// Settings needed to configure the cbires
define('CB_CONFIG_DIR', CB_SITE_ROOT.'/config/');
define('CB_ERROR_LOGS', CB_SITE_ROOT.'/logs/');

// These should be true while developing the web site
define('CB_IS_WARNING_FATAL', true);
define('CB_DEBUGGING', true);
// The error types to be reported
define('CB_ERROR_TYPES', E_ALL);
// Settings about mailing the error messages to admin
define('CB_SEND_ERROR_MAIL', false);
define('CB_ADMIN_ERROR_MAIL', 'giannismitros@gmail.com');
define('CB_SENDMAIL_FROM', 'info@cbires.com');
ini_set('sendmail_from', CB_SENDMAIL_FROM);
// By default we log errors to a file -> if not then turn CB_LOG_ERRORS, false
define('CB_LOG_ERRORS', true);
define('CB_LOG_ERRORS_FILE', CB_ERROR_LOGS.'\\error_logs.txt'); // Windows
// define('LOG_ERRORS_FILE', '/home/username/tshirtshop/errors.log'); // Linux
/** Generic error message to be displayed instead of debug info
(when DEBUGGING is false) */
define('CB_GENERIC_ERROR_MESSAGE', '<h1>CBIRES Error!</h1>');

//COOKIE AUTHNTICATION
define('_COOKIE_KEY_', 'GEn2Li0LsqGyXg7qEIGvvsNSBRgvXm2TzmRrkKDHw11Sv3NIDjWmfFwr');
define('_COOKIE_IV_', 'ENi58bBN');
define('__BASE_URI__', '/');

// Database connectivity setup
define('DB_PERSISTENCY', 'true');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'postgres');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'cbires');
define('DB_PORT', '5432');
define('PDO_DSN', 'pgsql:dbname='.DB_NAME.';user='.DB_USERNAME.';password='.DB_PASSWORD.';host='.DB_SERVER.';port='.DB_PORT);
define('DB_PREFIX', 'tbl_'); // define the databse table prefix