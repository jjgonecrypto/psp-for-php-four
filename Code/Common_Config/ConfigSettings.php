<?php

/** Common Config settings.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 *
 * @todo	Replace this with a static Config class, and implement the reading of 
 *			user data via Application.config.
 *			TODO: Currently, all uses of CMS must go. This is a BAD soln.
 */ 
 


// {{{ Define the Environment
define("ENV_DEVELOPMENT",1001);
define("ENV_PRODUCTION",1002);

global $_ENVIRONMENT;

//set the current environment
$_ENVIRONMENT = ENV_DEVELOPMENT;
//$_ENVIRONMENT = ENV_PRODUCTION;

// }}}


// {{{ Define the Error Reporting
define("EREPORTING_DEVELOPMENT",101);
define("EREPORTING_PRODUCTION",102);

global $ERRORREPORTING;

//TODO:: set the current level of error reporting
$ERRORREPORTING = EREPORTING_DEVELOPMENT;
//$ERRORREPORTING = EREPORTING_PRODUCTION;

// }}}

define("CMS_ISACTIVE","1");

//NOTE: These website defns should be in app.config, not here!

//set the configurations based on the environment

	if ($_ENVIRONMENT == ENV_DEVELOPMENT)
	{
		
		// {{{ Local Config Settings
		
		
		// }}}
		
	}
	else if ($_ENVIRONMENT == ENV_PRODUCTION)
	{
		
		// {{{ Live Config Settings
		
		// }}}
		
	}
	else
	{
		trigger_error("Environment not set.",E_USER_ERROR);	
		exit;
	}



// {{{ Generic Configuration Settings


//Any generic config settings go here


define("TEMPLATE_EXTENSION", ".tpl");
define("SYSTEM_TEMPLATE_EXTENSION", ".tpl");
define("SYSTEM_CSS_EXTENSION", ".css");
define("SYSTEM_JS_EXTENSION", ".js");


//the different control types
define("PC_CONTENT","content");
define("PC_REGION","region");
define("PC_PLACEHOLDER","placeholder");
define("PC_ANCHOR","anchor");
define("PC_GRID","grid");
define("PC_DATATABLE","datatable");
define("PC_BUTTON","button");
define("PC_FORM","form");
define("PC_CONDITIONAL","conditional");
define("PC_LITERAL","literal");
define("PC_REPEATER","repeater");
	define("PCNODE_ITEM","item");
	define("PCNODE_TEMPLATE","template");
define("PC_HTML",".");
define("PC_ATTRIBUTE","attribute");
define("PC_INPUT","input");
define("PC_INPUTGROUP","inputgroup");
define("PC_GROUPITEM","groupitem");
define("PC_AJAX","ajax");
	define("PCNODE_AJAXINPUT","input");
	define("PCNODE_AJAXOUTPUT","output");
	define("PCNODE_AJAXSTARTFNC","onstartup");
	define("PCNODE_AJAXCOMPLETEFNC","oncomplete");
define("PC_INPUTTABLE","inputtable");

define("PCPREFIX","psp");


define("PCCLIENTID_PREFIX","__psp_");

/// }}}
?>