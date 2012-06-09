<?php

/** Flag to signify if any errors occurred 
 * @var int
 */
global $_app_ErrorCount;   $_app_ErrorCount = 0;

/** Flag to signify if any warnings occurred 
* @var int
*/
global $_app_WarningCount;  $_app_WarningCount = 0;


/** Current filename of the error log (today's date)
 */
global $_app_ErrorFilename; 



/** The static error handler class
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class ErrorHandler
{
	
	
	function handleError($errno, $errstr, $errfile, $errline)
	{
		global $_app_ErrorFilename; 
		
		$err_message = "<p><b>".$errstr."</b> in ".$errfile." on line #".$errline."<br>".
			"URI: ".Server::Get("REQUEST_URI")." at (".Utilities::getDatetime().") by ".Server::Get("REMOTE_HOST")."</p>\n";
		
		$error_file = CMS_ERROR_LOGFILES_PATH.$_app_ErrorFilename;
		
		//create the error file if it doesnt exist
		if (!file_exists($error_file)) {
			fclose(fopen($error_file,"wb"));
			chmod($error_file,02777);
		}
		
		//log the error message
		error_log($err_message,3,$error_file);
		
		//increment the static counters
		if ($errno == E_USER_ERROR || $errno == E_ERROR)
		{
			global $_app_ErrorCount;
			$ErrorCount ++;
			
			//show generic error page
			ErrorHandler::gotoErrorpage();
		}
		else if ($errno == E_WARNING || $errno == E_USER_WARNING)
		{
			global $_app_WarningCount;
			$WarningCount ++;
		}
				
			
	}
	
	function handleDevelopmentError($errno, $errstr, $errfile, $errline)
	{
		if ($errno != E_USER_NOTICE && $errno != E_NOTICE)
		{
			$Page = new ErrorReportPage($errno, $errstr, $errfile, $errline, array_slice(debug_backtrace(),1));
			
			//call with flag to prevent ajax errors escaping
			WebsiteControl::RegisterPage($Page, true);
			
			exit;
		}
		else
		{
			//TODO: should keep these in global variable and output them on the footer
			
			//NOTE: simple output of notices
			echo "<p>PHP Notice: $errstr.<br />File: $errfile<br />Line: $errline</p>";	
		}
		
	}
	
	function gotoErrorpage()
	{
		Response::Redirect(CMS_ERROR_PAGE);
		exit;
	}
	
	function onShutdown()
	{
		//send mail 
		global $_app_ErrorCount, $_app_WarningCount, $_app_ErrorFilename; 
	
		//NOTE: This isn't general enough 
		if ($_app_ErrorCount > 0 || $_app_WarningCount > 0) 
		{
			
			//create mail
			$mail = new Mail();
			
			//set subject 
			$mail->Subject = "Website Error(s): ".CMS_WEBSITE_NAME;
			
			//set body
			$mail->Body = "Errors were found today on ".CMS_WEBSITE_NAME." (".CMS_WEBSITE_DOMAIN."). <br />
				Please check today's error log for more details. <br />
				Warnings: $_app_WarningCount .<br />
				Errors: $_app_ErrorCount";
			
			//set sender
			$mail->From = "From: ".CMS_WEBSITE_NAME." (".CMS_WEBSITE_DOMAIN.")";
			
			$mail->AddRecipient(CMS_ERROR_EMAIL);
			
			//NOTE: HTML should be loaded from template 
			
			$mail->Send(true);
			
		}
		
	}
	
	
	/** Initialise the error handling control
	 *
	 * @param int One of WC_E_DEVELOPMENT or WC_E_PRODUCTION
	 */
	function Initialise($level = EREPORTING_PRODUCTION)
	{
		
		if (!is_numeric($level) || ($level != EREPORTING_DEVELOPMENT && $level != EREPORTING_PRODUCTION))
		{
			trigger_error("Level for error reporting must be defined.",E_USER_ERROR);
		}
		
		
		//turn on full error reporting for development
		if ($level == EREPORTING_DEVELOPMENT)
		{
			error_reporting(E_ALL);
			
		//	set_error_handler(array('ErrorHandler','handleDevelopmentError'));
			
			return;
		}
		else
		{
			error_reporting(0);
			
			//set the error filename
			$dt_now = Utilities::getDatetime();
			list($date_now,$time_now) = split(" ",$dt_now);
			global $_app_ErrorFilename;
			$_app_ErrorFilename = $date_now.".html";
			
			set_error_handler(array('ErrorHandler','handleError'));
			
			register_shutdown_function(array('ErrorHandler','onShutdown'));
			
		}
		
		
		
	}
	
	
}


?>