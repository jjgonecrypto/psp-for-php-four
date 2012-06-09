<?php

//implements IHasTemplate

/** The base class of any object using controls.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class BaseHasControls
{
	
	/** The array of controls within this object.
	* @var array [element => PageControl]
	*/ 
	var $Controls = array();
	
	
	/** The template used by this Page/Control instance.
	 *
	 * @var TemplateReader
	 */
	var $Template = null;
	
	
	/** The file and path of this Page/Control instance.
	 * @var string
	 */
	var $Filename = null;
	
	
	/** The list of events that could cause a postback and the callbacks to call 
	 * in response
	 * @var array
	 */
	var $PostbackEvents = array();
	
	
	function BaseHasControls($filename = null)
	{
		$this->Filename = $filename;
	
		
	}
	
	function LoadTemplate()
	{
		//Set the template if it exists
		if ($this->Filename != null && file_exists($this->Filename.SYSTEM_TEMPLATE_EXTENSION))
		{
			
			$this->Template = new Template($this->Filename.SYSTEM_TEMPLATE_EXTENSION);
			
			
			//load controls
			Parser::ParseForControls($this,$this->Template->Document->GetRoot());
			
		}	
	}
	

	
	/** Adds a control to the collection
	 * @implements IHasTemplate
	 */
	function AddControl(&$controlx)
	{
		
		//enforce no children for controls that are restricted
		if (isset($this->CanHaveControls) && !$this->CanHaveControls)
		{
			trigger_error("The control type: ".$controlx->Type." cannot contain child controls.",E_USER_ERROR);
		}
		
		//ensure no control exists with this name
		if ($this->FindControl($controlx->ID, false) != null)
		{
			trigger_error("Control ID found more than once. Each control must have a unique ID. (ID = " . $controlx->ID . ").",E_USER_ERROR);	
		}
		
		//set the parent
		$controlx->SetParent(&$this);
		
		//add the control
		$this->Controls[$controlx->ID] =& $controlx;
		
		
			
	}
	
	/** Searches for a control recursively through the collection
	 * @implements IHasTemplate
	 */
	function &FindControl($controlID, $strict = true)
	{
		$found = null;
		
		if (isset($this->Controls[$controlID]))
		{
			$controly =& $this->Controls[$controlID];
			return $controly;
		}
		
		
		//search through each child	
		foreach ($this->Controls as $c)
		{
			//NOTE: foreach doesnt retrieve reference - that is why we do this
			$cx =& $this->Controls[$c->ID];
			
			//if the children are unique, then we cant find them using recursive FindControl
			if ($cx->ChildrenAreUnique || !$cx->CanHaveControls)
			{
				continue;	
			}
			
			$found =& $cx->FindControl($controlID, false);
			
			if ($found != null)
			{
				return $found;	
			}
		}
		
		if ($found == null && $strict)
		{
			trigger_error("Cannot find control: $controlID",E_USER_ERROR);	
		}
		
		return $found;	
	}
	
	/** Search back through the list of parents for a control.
	 *
	 */
	function &FindAncestor($controlID)
	{
		if ($this->ID == $controlID)
		{
			return $this;	
		}
		else if (isset($this->Parent))
		{
			return $this->Parent->FindAncestor($controlID);		
		}	
		else
		{
			return null;	
		}		
	}
	
	/** Search back through controls for the base page/master
	 * @return BaseHasControls
	 */
	function &GetBase()
	{
		if (isset($this->Parent))
		{
			return $this->Parent->GetBase();		
		}	
		else
		{
			return $this;	
		}
	}
	
	
	
	function PrintControlTree($name,$spaces)
	{
		for ($i = 0; $i < $spaces; $i++)
		{
			echo "--- ";	
		}
		
		echo $name . "<br />";
		
		foreach ($this->Controls as $k=>$c)
		{
			$p = $c->Parent->Type;
			$c->PrintControlTree($c->ID." ($k) ($p)",$spaces + 1);	
		}
	}
	
	
	/** Ensure if this control is activated, then the function is called
	 * @param PageControl
	 * @param Array Callback function
	 */
	function RegisterPostbackEvent($control, $callback)
	{
		$this->PostbackEvents[$control->GetClientID()] = $callback;
	}
	
	
	function CallPostback()
	{
		foreach ($this->PostbackEvents as $control_clientid => $callback)
		{
			//then this event fired the postback
			if (Postback::GetPostbacker() == $control_clientid)
			{
				//so call the event
				call_user_func($callback);
			}
		}	
	}
	
	//NOTE: not working for Control with parent Control (does work for Page/Master)
	function SetProperty(&$controlx)
	{
		$cid = $controlx->ID;
		
		if (isset($this->$cid))
		{
			trigger_error("The ID: $cid cannot be used due to internal naming conflict. Please choose another,", E_USER_ERROR);
		}
		
		$this->$cid =& $controlx;
		
		//echo "setting $cid to ".get_class($this)." ".$this->ID."<br>";
	}
	
	
}
?>