<?php

/** The base class of any template based webpage.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class BasePage extends BaseHasControls
{
	
	/** The Template used by this page.
	 * @var TemplateReader Template 
	 */ 
	var $Template = null;
	
	
	/** [Optional] The Master Page associated with this page
	 * @var PageMaster 
	 */
	var $Master = null;


	/** Whether or not the current state is a postback or not
	 * @var bool 
	 */
	var $IsPostBack = null;
	
	
	
	/** The list of all control types already rendered. This allows us to know to 
	 *	only include the CSS templates once for each type only.
	 * @var array
	 */
	var $IncludedControlTypes = array();
	
	
	/** Whether or not this page is being called as an Ajax postback.
	 * @var bool
	 */
	var $IsAjax = null;
	
	
	/** When IsAjax is true, this is the ajax control that contains the transaction
	 * @var PageControl_Ajax
	 */
	var $AjaxSender = null;
	
	
	/** An array of all controls that are registered for Ajax processing.
	 * @var Array
	 */ 
	var $AjaxControlEvents = null;
	
	
	//private flags
	var $_Init = false;
	var $_Preload = false;
	var $_Load = false;
	var $_Loaded = false;
	var $_Render = false;
	
	function BasePage($filename)
	{
		//base constructor
		parent::BaseHasControls($filename);
		
		$this->IsPostBack = false;
		
		$this->IsAjax = false;
		
		$this->AjaxControlEvents = array();
		
		
		//DO initial work 
		
		//find if was a postback
		if (Postback::WasPostback())
		{
			$this->IsPostBack = true;
			
			//NOTE: should provide a user option here not to do this
			//clean the input from the postback - slashes and html characters
			Postback::CleanPostData();
		}
		
		//work out if ajax
		$this->IsAjax = PageControl_Ajax::IsAjaxState();
		
		
		
		//just for debugging
		//$this->PrintTree();
		
	}
	
	
	/** Searches for a control recursively through the collection.
	 *  Overwrites the child class
	 * @param string ControlID
	 * @param bool Strict search. Throw error if not found. Default is TRUE.
	 */
	function &FindControl($controlID, $strict = true)
	{
		$control =& parent::FindControl($controlID, $strict);
		
		//NOTE: MIGHT HAVE TO REMOVE THIS IF ADDCONTROL IS FIXED
		
		//if ($strict && $control == null)
		////{
		//	trigger_error("BasePage.FindControl($controlID): Cannot find control.",E_USER_ERROR);
	 	//}
		
		return $control;	
	}
	
	
	/** Sets the master page for this page
	 * @param string Filename of the master page
	 */
	function SetMaster($file)
	{
		if (!file_exists(MASTERPAGE_PATH.$file))
		{
			trigger_error("Master page cannot be found. Location: " . MASTERPAGE_PATH.$file, E_USER_ERROR);	
		}
		
		$Master = null;

		$Master = include_once(MASTERPAGE_PATH.$file);
		
		if ($Master == null)
		{
			trigger_error("Master page file must return a master page object. Please add this code to the end of the file.", E_USER_ERROR);
		}
		
		$this->Master =& $Master;
		
		//back reference the master page
		$Master->SetOwner($this);
		
	}
	
	
	function Page_Init()
	{
		//echo get_class($this->Parent);
	
		$this->_Init = true;
		
		//load the template for this page
		$this->LoadTemplate();
		
		
		if ($this->Master != null)
		{
			$this->Master->Page_Init();	
		}
		
		//call the control initialiser
		foreach ($this->Controls as $control)
		{
			$controlz =& $this->Controls[$control->ID];
			$controlz->Control_Init();	
		}	
		
		
		if ($this->IsAjax)
		{
			//get the id of the sender
			$cid = PageControl_Ajax::GetAjaxSenderId();
			
			//get the ajax sender - will be null for pages that don't contain 
			//the control (eg. maybe a Masterpage that doesn't have the control)
			//NOTE: watch this. could be trouble.
			$this->AjaxSender =& $this->FindControl($cid, false);
		}
		
	}
	
	
	
	function Page_Ajax(&$Ajax)
	{
	
		//if there is a control that is registered to handle this ajax object 
		//then call them 
		if (isset($this->AjaxControlEvents[$Ajax->ID]))
		{
			call_user_func($this->AjaxControlEvents[$Ajax->ID]);
			
			return;		
		}
		
		
		//call master ajax methods if any
		//NOTE: untested
		if ($this->Master != null)
		{
			$this->Master->Page_Ajax(&$this->Master->AjaxSender);	
		}
		
		//NOTE:: controls have to call their Base page and register 
		//an Ajax method to get called
	
		
	}
	
	
	function Page_Preload()
	{
		$this->_Preload = true;
		
		if ($this->IsPostBack)
		{
			//load this master's data
			if ($this->Master != null)
			{
				$this->Master->Page_Preload();	
			}
			
			//call the control loader
			foreach ($this->Controls as $control)
			{
				$controlx =& $this->Controls[$control->ID];
				$controlx->Control_Preload();	
			}	
		}
		
	}
		
	function Page_Load()
	{
		//base load	
		$this->_Load = true;
		
		//echo "<b>Page_Load</b>";
		//$this->PrintTree();
		
	}	
	
	//NOTE: is final
	/** Called after the page has been loaded
	 */
	function Page_Loaded()
	{
		//echo "<b>Page_Loaded</b>";
		//$this->PrintTree();
		
		$this->_Loaded = true;
		
		//call the master loader
		if ($this->Master != null)
		{
			$this->Master->Page_Load();
			
			$this->Master->Page_Loaded();			
		}
		
		
		//call the control loader
		foreach ($this->Controls as $control)
		{
			$controlx =& $this->Controls[$control->ID];
			$controlx->Control_Load();
		}
		
		if ($this->IsPostBack)
		{
			//call the control preloader(postback data loader)
			foreach ($this->Controls as $control)
			{
				$controlx =& $this->Controls[$control->ID];
				$controlx->Control_Preload();
			}
			
			//call the control postback
			foreach ($this->Controls as $control)
			{
				$controlx =& $this->Controls[$control->ID];
				$controlx->Control_Postback();
			}
		
			//call the postback event loader -> 
			//NOTE: needs to come before the controls are loaded
			//WHY?? here it is after
		
			$this->Page_Postback();
		}
		
		
	}
	
	function PrintTree()
	{
		if (is_a($this,"PageMaster"))
		{
			$this->PrintControlTree("Master",0);
		}
		else
		{
			$this->PrintControlTree("Page",0);
		}	
		
	}
	
	function RegisterAjaxControl($control, $callback)
	{
		$this->AjaxControlEvents[$control->ID] = $callback;
	}
	
	/** Record that this control type has been rendered
	 * @param PageControl
	 */
	function RegisterControlRendered(&$control)
	{
		$this->IncludedControlTypes[$control->Type] = true;	
	}
	
	/** Return whether or not a control type has already been rendered (within the page and the masters).
	 * @param string The type of control to check
	 */
	function IsControlTypeRendered($type)
	{
		//work out if control is registered on this page
		$value = isset($this->IncludedControlTypes[$type]) && $this->IncludedControlTypes[$type] === true;	
	
		//return if no master or if found
		if ($this->Master == null || $value)
		{
			return $value;	
		}
		//otherwise check the masters
		else
		{
			return $this->Master->IsControlTypeRendered($type);	
		}
	}	
	
	function Page_Postback()
	{
		$this->_Postback = true;
		
		$this->CallPostback();
	}
	
	
	/** Return the page DomDocument. 
	 * CANNOT be overridden by User class. 
	 * NOTE: should be final in PHP5
	 * @return PspDomDocument
	 */
	function Page_Render()
	{
		$this->_Render = true;
		
		$document = null; 
		$controlx = null;
		
		//echo "<b>Page_Render</b>";
		
		//$this->PrintTree();
		
		
		//parse the document for any special code insert tags
		Parser::ParseInlineBlocks(&$this, $this->Template->Document->DocumentElement());
		
		
		
		//append all the controls to the template
		foreach ($this->Controls as $control)
		{
			//NOTE: foreach doesnt retrieve reference - that is why we do this
			$controlx =& $this->Controls[$control->ID]; 
			
			$controlx->Control_Render($this);
			
		}
		
		
		//get the rendered document
		if ($this->Master != null)
		{
			//the document is a masterpage's document
			$document = $this->Master->Page_Render();
			
		}
		else
		{
			//the document is just this page's
			$document = $this->Template->Document;	
			
			
		}
		
		
		
		return $document;
	}
	
	
	function DidEventOccur($name)
	{
		$nname = "_".$name; 
		
		if (!isset($this->$nname))
		{
			trigger_error("Event $name doesn't exist!",E_USER_ERROR);	
		}
		else
		{
			return $this->$nname;	
		}	
	}
	
}

?>