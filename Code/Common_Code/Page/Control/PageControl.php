<?php

/** A webpage control - located within the template. This class is ABSTRACT
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 * 
 * @abstract 
 */
class PageControl extends BaseHasControls
{
	/** The parent that contains this control
	 * @var IHasControls
	 */
	var $Parent = null;


	/** The type name of the control.
	 * @var string
	 */
	var $Type = null;


	/** The unique identifier given to this control.
	 * @var string
	 */ 
	var $ID = null; 


	/** The list of all specific attributes to this control.
	 * @var array [int => ControlAttribute]
	 */ 
	var $SpecialAttributes = null;


	/** All attributes that this control has
	 * @var array [int => DomAttribute]
	 */	
	var $Attributes = null;
	

	/** The original DOM node of the control. 
	 * @var DomElement
	 */
	var $OriginalNode = null;


	/** The node that will replace the one in the template
	 * @var DomElement
	 */ 
	var $NewNode = null;


	/** Allows a node within the template to inherit the public attributes of this control.
	 * @var DomElement
	 */
	var $AssignedTemplateNode = null;


	/** The name of the element of the new name. Optional.
	 * @var string
	 */ 
	var $NewNodeName = null;


	/** Determines if this control should be processed and rendered.
	 * @var bool
	 */
	var $Visible = true;


	/** Determines the ResourceID if required
	 * @var string
	 */
	 var $ResourceID = null;
	 
	  
	 /** Whether or not to use the supplied template. Render uses NewNode otherwise.
	  *
	  * @var Boolean
	  */
	 var $UseTemplate = true;
	 
	 
	/** Determines if this control is allowed to have control children
	 * @var bool
	 */
	var $CanHaveControls = null;


	/** Determines if this control's children are unique
	 * @var bool
	 */
	var $ChildrenAreUnique = null;

	/** Determines if this control is a decendant of one who has unique controls
	 * @var bool
	 */
	//NOTE: not used
	//var $IsUnique = null; 


	/** Whether or not to use the default css template
	 * @var bool
	 */
	var $DefaultCss = null;


	/** The template, containing css, to be used for this control.
	 * @var TemplateReader
	 */ 
	var $CssTemplate = null;


	/** The Javascript file for this control, if any. 
	 * @var TemplateReader
	 */
	var $JsTemplate = null;


	
	function PageControl($filename)
	{
		
		if ($this->Type == null)
		{
			trigger_error("The control type must be set before calling the base constructor.",E_USER_ERROR);	
		}
		
		$this->Attributes = array();
		
		$this->SpecialAttributes = array();
		
		$this->AppendSpecialAttribute(new ControlAttribute("id","ID"));
		$this->AppendSpecialAttribute(new ControlAttribute("runat",null));
		$this->AppendSpecialAttribute(new ControlAttribute("visible","Visible","BOOL"));
		$this->AppendSpecialAttribute(new ControlAttribute("resourceid","ResourceID"));
		
		
		//generate an ID
		if ($this->Type == PC_HTML && $this->NodeName != null)
		{
			$this->ID = $this->GenerateId($this->NodeName);
		}
		else
		{
			$this->ID = $this->GenerateId($this->Type);
		}
		
		//set default unique status
		//$this->IsUnique = false;
		
		//set css on by default
		$this->DefaultCss = true;
		
		
		//load the template now if any
		parent::BaseHasControls($filename);
		
		
		//set css file if exists
		if (file_exists($this->Filename.SYSTEM_CSS_EXTENSION))
		{
			$this->CssTemplate = new Template($this->Filename.SYSTEM_CSS_EXTENSION, "CSS");
		}
		
		//set js file if exists
		if (file_exists($this->Filename.SYSTEM_JS_EXTENSION))
		{
			$this->JsTemplate = new Template($this->Filename.SYSTEM_JS_EXTENSION,"JS");
		}
		
		
	}



	/** Static factory function to return a control. 
	 * Called from a template search with the found node.
	 * @param PspDomElement The node of the control
	 */ 
	function Factory($node)
	{
		//get the type
		$c_type = $node->GetNodeName();
		
		$control = null;
		
		switch ($c_type)
		{
			case PC_FORM:
				//check if another form has been found
				if (WebsiteControl::GetFormControl() != null)
				{
					trigger_error("Multiple FORM controls found. A website may contain only a single FORM control.",E_USER_ERROR);	
				}
					
				$control = new PageControl_Form();
				
				//keep this form
				WebsiteControl::SetFormControl($control);
				
				break;
			case PC_ATTRIBUTE:
				$control = new PageControl_Attribute();
				break;
			case PC_AJAX:
				$control = new PageControl_Ajax();
				break;
			case PC_INPUT:
				$control = new PageControl_Input();
				break;
			case PC_CONTENT:
				$control = new PageControl_Content();
				break;
			case PC_LITERAL:
				$control = new PageControl_Literal();
				break;
			case PC_ANCHOR:
				$control = new PageControl_Anchor();
				break;
			case PC_REGION:
				$control = new PageControl_Region();
				break;
			case PC_GRID:	
				//NOTE: Grid has been deactivated
				//trigger_error("The GRID control has been deactivated. Please use the DataTable control.");
				
				$control = new PageControl_Grid();
				break;
			case PC_DATATABLE:
				$control = new PageControl_DataTable();
				break;
			case PC_PLACEHOLDER:
				$control = new PageControl_Placeholder();
				break;	
			case PC_BUTTON:
				$control = new PageControl_Button();
				break;
			case PC_REPEATER:
				$control = new PageControl_Repeater();
				break;
			case PC_INPUTGROUP:
				$control = new PageControl_InputGroup();
				break;
			case PC_GROUPITEM:
				$control = new PageControl_GroupItem();
				break;
			case PC_INPUTTABLE:
				$control = new PageControl_InputTable();
				break;
			case PC_CONDITIONAL:
				$control = new PageControl_Conditional();
				break;
			default:
				//set a new html control by default
				$control = new PageControl_Html($node->GetNodeName());
				break;
		}
		
		$control->SetOriginalNode($node);
		
		return $control;
	}

	/** This control has been found within a user template, so set 
	 * the found node. 
	 * @param PspDomElement The node that has been found
	 */
	function SetOriginalNode($node) 
	{
		$this->OriginalNode = $node;
		
		//set all attributes
		$attrs = $node->GetAttributes();
		
		if (is_array($attrs))
		{
			$this->Attributes = $attrs;
		}
		
		//set special attributes
		foreach ($this->SpecialAttributes as $attribute)
		{
			$property = $attribute->Property;
			
			//dont append empty properties
			if ($property == null)
			{
				continue;
			}
			
			//NOTE: not working
			//ensure property exists for control
			//if (!isset($this->$property))
			//{
			//	trigger_error("Property not found for control. The control of type: ".$this->Type." with ID ".$this->ID." is missing property ".$property, E_USER_ERROR);
			//}
			
			//only set the property if not empty
			if ($node->GetAttribute($attribute->Attribute) != "")
			{
				//check and set datatype
				$this->$property = $attribute->VerifyValue($this, $node->GetAttribute($attribute->Attribute));	
			}	
		}
	}
	
	function SetParent(&$myParent)
	{
		$this->Parent =& $myParent;	
		
		//set unique status if we are a decendant of a unique control
		//$this->IsUnique = is_a($this->Parent,"PageControl") && ($this->Parent->ChildrenAreUnique || $this->Parent->IsUnique);
	}
	
	function AppendSpecialAttribute($controlAtt)
	{
		$this->SpecialAttributes[] = $controlAtt;
	}
	
	/** Returns if this attribute is one of the specially allocated ones
	 * 
	 */
	function IsSpecialAttribute($name)
	{
		foreach ($this->SpecialAttributes as $sa)
		{
			if ($sa->Attribute == $name)
			{
				return true;	
			}	
		}
		return false;
	}
	
	/** Generates a random identifier for this control.
	 * STATIC FUNCTION
	 */
	function GenerateId($type)
	{
		static $seed = 0;
		
		return "_".$type."_".$seed++;
	}

	
	/** Get the clientID for this control.
	 * @return string
	 */ 
	function GetClientID()
	{
		$Owner =& $this->GetOwner();
		
		if (!is_a($Owner,"PageControl"))
		{
			return PCCLIENTID_PREFIX.$this->ID;
		}
		else
		{
			return $Owner->GetClientID()."_".$this->ID;
		}
		
	}
	
	/** Search back through controls for the owner control
	 * NOTE: assumes that the parent is a control with a template.
	 */
	function &GetOwner()
	{
		if (!is_a($this->Parent,"PageControl"))
		{
			return $this->Parent;	
		}
		
		//owner is a control
		if (is_a($this->Parent,"PageControl")  && ($this->Parent->Template != null || is_a($this->Parent,"RepeaterRow")))
		{
			return $this->Parent;	
		}
		else
		{
			return $this->Parent->GetOwner();		
		}
		
		
	}
	

	/** Adds a single attribute to the template. 
	 * @param string The name of the attribute to set
	 * @param string THe value of the attribute
	 * @return bool Success state.
	 */
	function AddAttribute($name, $value)
	{
		if ($this->OriginalNode != null)
		{
			$doc = $this->OriginalNode->OwnerDocument();
			$this->Attributes[] = $doc->CreateAttribute($name, $value);	
			return true;
		}
		else
		{
			return false;
		}
	}

	
	
	/** Initialise the control
	 *
	 */
	function Control_Init()
	{
		//load a template for this control if one exists
		$this->LoadTemplate();
		
		
		//set back reference in owner
		$Owner =& $this->GetOwner();
		
		$Owner->SetProperty(&$this);
		
		
		//load each child control
		foreach ($this->Controls as $controlx)
		{
			$control =& $this->Controls[$controlx->ID];
			$control->Control_Init();	
		}
		
			
	}
	
	
	
	/** Load up controls from postback
	 *
	 */ 
	function Control_Preload()
	{
		//echo "Preload ".$this->Type." ".$this->ID."<br>";
		
		//load each child control
		foreach ($this->Controls as $controlx)
		{
			$control =& $this->Controls[$controlx->ID];
			$control->Control_Preload();	
		}	
	}
	
	
	/** Base loader. Loads all the child controls. 
	 * This should be called after the control is loaded.
	 */
	function Control_Load()
	{
		
		//load each child control
		foreach ($this->Controls as $controlx)
		{
			$c =& $this->Controls[$controlx->ID];
			$c->Control_Load();
		}	
		
	}
	
	function Control_Postback()
	{
		//echo "Postback ".$this->Type." ".$this->ID."<br>";
		
		
		foreach ($this->Controls as $controlx)
		{
			$control =& $this->Controls[$controlx->ID];
			$control->Control_Postback();	
		}
		
		$this->CallPostback();

			
	}
	
	
	
	/** Base renderer. 
	 *
	 */	
	function Control_Render(&$Owner)
	{
		
		//don't render invisible control
		if (!$this->Visible)
		{
			if ($this->OriginalNode != null)
			{
				$this->RemoveMe();
			}
			
			return false;
		}
		
		//first call the resource if any 
		if ($this->ResourceID != null)
		{
			$res =& WebsiteControl::GetResource();
			
			if ($res != null)
			{
				$res->PopulateControl(&$this);	
			}
		}
		
		//get a reference to the page
		$Page =& WebsiteControl::GetPage();
	
		//set the new replacement node for this control
		if ($this->Template != null && $this->UseTemplate)
		{
			
			//either from a template
			$this->NewNode = $this->Template->Document->GetRoot();
			
		
			//set the client id if reqd
			$this->NewNode->SetAttribute("id",$this->GetClientID());
			
			//copy any attributes from the template node 
			if ($this->AssignedTemplateNode != null && $this->OriginalNode != null)
			{
				
				foreach ($this->Attributes as $a)
				{
					//don't get special attributes
					if (!$this->IsSpecialAttribute($a->GetName()))
					{
						$this->AssignedTemplateNode->SetAttribute($a->GetName(),$a->GetValue());		
					}
				}
			}
		}
		else if ($this->NewNodeName != null || $this->NewNodeName == "")
		{
			
			if ($this->OriginalNode != null)
			{
				//or a new node
				$doc = $this->OriginalNode->OwnerDocument();
			}
			else
			{
				$doc = new PspDomDocument();	
			}
			
			if ($this->NewNodeName == "")
			{
				//NOTE: This might not work in PHP5	
				//add a whitespace node
				$this->NewNode = $doc->CreateTextNode("");
			}
			else
			{
				//add an element node
				$this->NewNode = $doc->CreateElement($this->NewNodeName);
				
				foreach ($this->Attributes as $a)
				{	
					//don't get special attributes
					if (!$this->IsSpecialAttribute($a->GetName()))
					{
						$this->NewNode->SetAttribute($a->GetName(),$a->GetValue());		
					}
				}
				
				
				
				//set the client id if reqd
				$this->NewNode->SetAttribute("id",$this->GetClientID());
					
			}
			
				
			
		}
		
		
		//Add css and js templates if required
		if ($this->Template != null && !$Page->IsControlTypeRendered($this->Type))
		{
			
			$doc = $this->Template->Document->OwnerDocument();
			$first_node = $doc->GetRoot();
			
			//NOTE: only add css once for each control type
			if ($this->CssTemplate != null && $this->DefaultCss)
			{
				$css_node = $this->CssTemplate->Document->GetRoot();
				$doc->InsertFirst($css_node);
			}
			
			//ADD JAVASCRIPT IF REQUIRED
			if ($this->JsTemplate != null)
			{
				$js_node = $this->JsTemplate->Document->GetRoot();
				$doc->InsertFirst($js_node);
			}
			
		}
		
		
		//signal that this control was rendered - to prevent successive css / js
		$Page->RegisterControlRendered($this);
		
		
		
		//render each child control
		foreach ($this->Controls as $control)
		{
			$controlx =& $this->Controls[$control->ID];
			$controlx->Control_Render($Owner);	
		}
		
		return true;
	}
	
	function RemoveMe()
	{
		$parent = $this->OriginalNode->ParentNode();
		
		$parent->RemoveChild($this->OriginalNode);
	}
	
	
	
	/** Replace this control with it's new element, copying all the children 
	 * from the original node into the new node.
	 * @param bool Whether or not to copy children into new element or not.
	 */
	function ReplaceElement($doChildren = true)
	{
		if ($doChildren)
		{
			//append all children 
			foreach ($this->OriginalNode->ChildNodes() as $child)
			{
				$this->NewNode->AppendChild($child);	
			}
		}
		
		$parent = $this->OriginalNode->ParentNode();
		
		$parent->ReplaceChild($this->NewNode,$this->OriginalNode);	
	}
	
	
	/** The control has been loaded programmatically and needs to be 
	 * loaded into the document
	 */
	function InsertElement()
	{
		//TODO
	}
	
	
	
}

?>