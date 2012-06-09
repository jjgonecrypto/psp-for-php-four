<?php

/** A file found in a recursive directory search.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class FoundFile
{
	/** The filename (including extension) of this file
	 *
	 */
	var $Filename;
	
	
	/** The absolute path to the file
	 *
	 */
	var $AbsolutePath;	
	

	/** The relative path to the file
	 *
	 */
	var $RelativePath;


	function FoundFile($filename, $apath, $rpath)
	{
		$this->Filename = $filename;
		
		$this->AbsolutePath = $apath;
		
		$this->RelativePath = $rpath;	
	}
}

?>