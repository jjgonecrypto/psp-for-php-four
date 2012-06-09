<?php

/** Datasource that provides an enumerator for datarows.
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */ 
class Datasource
{
	var $Data = null;
	
	/** Either a new datasource, or one from existing data
	 * @param Mixed [Optional, default is empty array]
	 */
	function Datasource($data = array())
	{
		$this->Data = $data;
	}
	
	function AddRow(&$datarow)
	{
		$this->Data[] =& $datarow;
	}
	
	function RowCount()
	{
		return count($this->Data);	
	}
	
	//public abstract function GetEnumerator();	
	function GetEnumerator()
	{
		return new DatasourceEnumerator($this);
		
	}
	
	
	function SortRows($fieldname, $ascending)
	{
		//TODO::
		
		//sort the data based on the fieldname	
		
	}
	
	
	/** Return this datasource as an XML document.
	 *
	 * @return PspDomDocument
	 *
	 * @example
	 *	<data>
	 *		<row>
	 *			<dinner>Chips</dinner>
	 *			<breakfast>Cereal</breakfast>
	 *			...	
	 *		</row>
	 */
	function GetAsXMLDocument()
	{
		$doc = new PspDomDocument();
		
		$rootNode = $doc->AppendChild($doc->CreateElement("data"));
		
		$enum = $this->GetEnumerator();
	
		$done_cols = false;
		
		while (($row = $enum->GetNext()) != null)
		{
			$rowNode = $doc->CreateElement("row");
			
			$cols_enum = $row->GetEnumerator();
				
			while (($field = $cols_enum->GetNext()) != null)
			{
				$fieldNode = $doc->CreateElement($field->Name);	
				
				$fieldNode->SetNodeContent($field->Value);
				
				$rowNode->AppendChild($fieldNode);
			}
			
			$rootNode->AppendChild($rowNode);
		}
		
		return $doc;
	}
}


?>