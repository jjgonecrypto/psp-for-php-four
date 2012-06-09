<?php

/** Initialisation of package.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */	


// {{{ Require List


//special code to recursively include folders
require_once dirname(__FILE__) . "/Tools/FoundFile.php";
require_once dirname(__FILE__) . "/Tools/RecursiveSearch.php";

//ensure app_path is set
if (!defined("APP_CODE_PATH"))
{
	trigger_error("Required setting APP_CODE_PATH not defined. This must be set in the application prior to loading the PSP base.",E_USER_ERROR);	
}

//include all common config files
RecursiveSearch::RequireAll(PSP_CODE_PATH."Common_Config".DIRECTORY_SEPARATOR);


//include user config
//NOTE: This should be replaced with a call to Parse the Application.config file in this directory
//		It should also occur after Common_Code has been loaded.
RecursiveSearch::RequireAll(APP_CODE_PATH."App_Config".DIRECTORY_SEPARATOR);




//include common files
RecursiveSearch::RequireAll(PSP_CODE_PATH."Common_Code".DIRECTORY_SEPARATOR);

//include error reports page
RecursiveSearch::RequireAll(PSP_CODE_PATH."Error_Reporting".DIRECTORY_SEPARATOR);

//include user files
RecursiveSearch::RequireAll(APP_CODE_PATH."App_Code".DIRECTORY_SEPARATOR);

	
// }}}

	
	
/** The error handler instance
 * @var ErrorHandler
 */
ErrorHandler::Initialise($ERRORREPORTING);



//NOTE:
// this shouldn't be here - should be loaded from CONFIG
 

//Initialise the global variables
global $Database;

/** The database interface for the website
 * @var Database 
 */
 if (defined("DB_SERVER"))
 {
	$Database = new Database_MySQL(DB_SERVER,DB_NAME,DB_UNAME,DB_PWORD);
	//$Database = new Database_MySQLi(DB_SERVER,DB_NAME,DB_UNAME,DB_PWORD);
  }	
?>