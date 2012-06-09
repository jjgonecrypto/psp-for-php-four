<?php

/** The static website response system, used to control output and redirects.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class Response
{
	//NOTE: should have static variable here
	//public static $Headers

	/** Add a header. NOTE: this function should use the class'es static properties 
	 * but these aren;'t available in PHP4.
	 * @param string The header to send
	 */
	function AddHeader($header)
	{
		if (headers_sent(&$file, &$line))
		{
			trigger_error("Cannot add header. Headers were sent. File: $file. Line: $line. ", E_USER_ERROR);	
		}
		else
		{
			header($header);	
		}
	}
	
	/** Redirect the user to the new URL, immediately if necessary.
	 * @param string The URL to use.
	 * @param bool Whether or not to end execution of the processor immediately. Default is TRUE.
	 * @return void
	 */ 
	function Redirect($url, $immed = true)
	{
		$file = "";
		$line = "";
		
		Response::AddHeader("Location: ".$url);
		
		if ($immed)
		{
			exit;
		}
		
	}	
	
	/** Refresh the current page
	 * @param [Optional] Mixed: UrlVariable of array of UrlVariable items to append
	 */
	function Refresh($opt = null)
	{
		
		$uri = Server::GetFullUri();
		
		
		if (is_array($opt))
		{
			foreach ($opt as $uvar)
			{
				$uri->AddVariable($uvar);	
			}
		}
		else if (is_a($opt, "UrlVariable"))
		{
			$uri->AddVariable($opt);	
		}
		
		header('Refresh: 0; url='.$uri->Output());
		
		exit;	
	}
}
?>