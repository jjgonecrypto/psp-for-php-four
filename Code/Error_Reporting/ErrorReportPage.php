<?php

/** The error reporting page - used in development
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class ErrorReportPage extends Page
{
	var $ErrorNbr = null;
	var $ErrorStr = null;
	var $ErrorFile = null;
	var $ErrorLine = null;
	var $Stack = null;
	
	
	function ErrorReportPage($errno, $errstr, $errfile, $errline, $stack)
	{
		parent::Page(__FILE__);
		
		$this->Title = "Error Reporting";	
		
		if ($errno == E_USER_ERROR || $errno == E_ERROR)
		{
			$this->ErrorNbr = "Error";	
		}
		else
		{
			$this->ErrorNbr = "Warning";	
		}
		
		$this->ErrorStr = $errstr;
		
		$this->ErrorFile = Utilities::retrieveFilename($errfile);
		
		$this->ErrorLine = $errline;
		
		$this->Stack = $stack;	
	}
	
	
	function Page_Load()
	{
		//set the error details 
		parent::Page_Load();
		
		$error_num =& $this->FindControl("errorClass");
		$error_num->Text = $this->ErrorNbr;
		
		$error_str =& $this->FindControl("errorMessage");
		$error_str->Text = $this->ErrorStr;
		
		$error_file =& $this->FindControl("errorFile");
		$error_file->Text = $this->ErrorFile;
		
		$error_line =& $this->FindControl("errorLine");
		$error_line->Text = $this->ErrorLine;
		
			
		$grid =& $this->FindControl("stackGrid");
		$grid->setDatasource(new Datasource($this->Stack));
		
		//set columns
		$grid->AddColumn(new GridColumn("File","file"));
		$grid->AddColumn(new GridColumn("Line","line"));
		$grid->AddColumn(new GridColumn("Class","class"));
		$grid->AddColumn(new GridColumn("Function","function"));
		$grid->AddColumn(new GridColumn("Arguments","args"));
		
	}
}
?>