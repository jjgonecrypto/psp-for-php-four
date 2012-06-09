<?php

/** Static class to recursively search directories.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * @static 
 */
class RecursiveSearch
{
	var $Extensions = array();
	
	function RecursiveSearch($exts)
	{
		foreach ($exts as $e)
		{
			$this->Extensions[] = $e;	
		}
	}
	
	function Search($dir, $cdir = "")
	{
		$found = array();
		
		$dirs = array();
		
		$_dh  = opendir($dir);
		
		//include all files in the App_Code folder
		while (false !== ($filename = readdir($_dh))) 
		{
			if ($filename == "." || $filename == "..")
			{
				//ignore . and .. directories
				continue;
			}
			else if ($this->CheckExtension($filename))
			{
				
				$found[] = new FoundFile($filename, $dir.$filename,  $cdir.$filename);
			}	
			else if (is_dir($dir.$filename))
			{
				//do the directories separately at the end
				$dirs[] = new FoundFile($filename, $dir.$filename.DIRECTORY_SEPARATOR,  $cdir.$filename."/");
				
				//include all files in subdirectories
				//$found = array_merge($found, $this->Search($dir.$filename.DIRECTORY_SEPARATOR, $cdir.$filename."/"));
			}
		}
		
		closedir($_dh);
		
		//now add all the directories at the end
		foreach ($dirs as $d)
		{
			$found = array_merge($found, $this->Search($d->AbsolutePath, $d->RelativePath));	
		}
		
		
		return $found;
	}
	
	/** Check to see if this file is one of the supplied extensions.
	 *
	 */
	function CheckExtension($filename)
	{
		
		foreach ($this->Extensions as $ext)
		{
			$len = strlen($filename)-strlen(".$ext");
			
			//don't process if no filename
			if ($len <= 0)
			{
				continue;	
			}
			if (substr($filename,$len) == ".$ext")
			{
				return true;
			}
		}
		
		return false;
	}
	
	/** Static method to require the files
	 * @param string The path to the directory to open
	 */
	function RequireAll($dir)
	{
		$searcher = new RecursiveSearch(array("php","inc","req"));	
		
		$results = $searcher->Search($dir);
		
		foreach ($results as $res)
		{
			require_once $res->AbsolutePath;	
		}
	}
	
	/** Static method to recursively get all stylesheets from the directory
	 * @param string Absolute path to the directory to open
	 */
	function GetStyleSheets($dir)
	{
		$searcher = new RecursiveSearch(array("css"));	
		
		return $searcher->Search($dir);
		
	}

	/** Static method to recursively get all scripts from the directory
	 * @param string Absolute path to the directory to open
	 */
	function GetScripts($dir)
	{
		$searcher = new RecursiveSearch(array("js"));	
		
		return $searcher->Search($dir);
	}
}


?>