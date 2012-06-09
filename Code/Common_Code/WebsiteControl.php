<?php


//NOTE: These global references should be changed to a static property in PHP5


/** The single form control that resides on the page.
 * @var PageControl_Form
 */
global $__psp__Form; 
$__psp__Form = "__psp__Form_global"; 
$GLOBALS[$__psp__Form] = null;

/** The page that is being run
 * @var Page
 */
global $__psp__Page; 
$__psp__Page = "__psp__Page_global"; 
$GLOBALS[$__psp__Page] = null;



/** The current resource file
 * @var Resource
 */
global $__psp__Resource; 
$__psp__Resource = "__psp__Resource_global"; 
$GLOBALS[$__psp__Resource] = null;


/** The static website control system, used to register pages.
 * 
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */
class WebsiteControl 
{
	
	function RegisterPage(&$Page, $IgnoreAjax = false)
	{
	
		$Profile = new Profiler("Total");
		$Profile->Start();
		
		
		//set the static reference to this page 
		WebsiteControl::SetPage($Page);
		
		
		//load up an application config
		$app_file = APP_CODE_PATH."App_Config".DIRECTORY_SEPARATOR."Application.config";
		 
		if (file_exists($app_file))
		{
			//load the configuration file
			$config = new Config($app_file);
			
		}
		else
		{
			//TODO
			//should error because no config is given 
				
		}
		
		
		//NOTE: simple check if CACHE_PATH exists then go 
		
		if (defined("CACHE_PATH"))
		{
			$cache_file = CACHE_PATH."cache.dat";
			
			//check for caching
			//TODO  - this should be in the application.config
			if (!$Page->IsPostBack)
			{
				
				$Cache = Cache::Load($cache_file);
				
				if ($Cache === false)
				{
					//then this is the first load of the cache ever
					
					$Cache = new Cache();	
					
				}
				
				$lang = "";
				
				if ($Page->CurrentLanguage != null)
				{
					$lang = $Page->CurrentLanguage->Code;	
				}
				
				
				if (($found = $Cache->Find(Request::GetUri(),$lang)) !== false)
				{
					echo $found->Contents;
					return;	
				}
				
			}
			
		}
		
		//call the initialiser - load teh templates
		$Page->Page_Init();
		
		//check it ran
		//TODO: should be done within each page/master/control....
		if (!$Page->DidEventOccur("Init"))
		{
			trigger_error("The page file cannot override the Init event without calling the parent::Page_Init().", E_USER_ERROR);	
		}
		
	
		
		if ($Page->IsAjax && !$IgnoreAjax)
		{
			//call the ajax event 
			$Page->Page_Ajax($Page->AjaxSender);
			
			return;
		}
		
		//call the preload event, to load postback data
		$Page->Page_Preload();
		
		if (!$Page->DidEventOccur("Preload"))
		{
			trigger_error("The page file cannot override the Preload event without calling the parent::Page_Preload().", E_USER_ERROR);	
		}
		
		//call the page load event
		$Page->Page_Load();
		
		if (!$Page->DidEventOccur("Load"))
		{
			trigger_error("The page file cannot override the Load event without calling the parent::Page_Load().", E_USER_ERROR);	
		}
		
		//call the page loaded event
		$Page->Page_Loaded();
		
		if (!$Page->DidEventOccur("Loaded"))
		{
			trigger_error("The Page_Loaded() event is FINAL and should not be overrided.", E_USER_ERROR);	
		}
		
		//call the render event
		$doc = $Page->Page_Render();
		
		//get the xhtml code
		$xml = $doc->GetAsXHTML();
		
		
		//stop profiling
		$Profile->Stop();
		
		
		//output entire page
		echo $xml;
		
		
		//time output
		global $_ENVIRONMENT;
	
	
		//NOTE: should append this to the BODY node of the document 
		
		//output 
		if ($_ENVIRONMENT == ENV_DEVELOPMENT)
		{	
			//echo "<div style='background-color:#aaa; font-size: x-small; position:fixed; bottom: 0px; left: 0px; right: 0px; '><div style='padding: 2px;'>script generation took <b>".$Profile->Time." s</b> | <a target='_blank' href='/Notes/DatabaseChanges.xml'>Database Changelog</a> | <a target='_blank' href='http://localhost/psp/Documentation/Controls.xml'>PSP Controls</a> | <a target='_blank' href='http://localhost/psp/Documentation/Todo.xml'>PSP Todo</a> | <a target='_blank' href='http://localhost/psp/Documentation/VersionHistory.xml'>PSP Version History</a></div></div>";
		}
		
		
		//cache the page
		if (!$Page->IsPostBack)
		{
			$lang = "";
			
			if ($Page->CurrentLanguage != null)
			{
				$lang = $Page->CurrentLanguage->Code;	
			}
			
			
			//TODO: implement cache sizing
			if (defined("CACHE_PATH"))
			{
				$Cache->AddFile(new CacheFile($xml,Request::GetUri(),$lang));	
				
				Cache::Save($cache_file, $Cache);
			}
		}
	}
	
	/** Currently not required. Added for future requirements
	 *
	 */
	function RegisterMasterPage($Master)
	{
		//Currently does nothing
		
		return $Master;
	}	
	
	
	/** Static setting of the page
	 *
	 */
	function SetPage(&$page)
	{
		global $__psp__Page;
		
		//Double the use of globals to ensure PHP4 references
		$GLOBALS[$__psp__Page] =& $page;
	
	}
	
	/** Static retrival of the page
	 *
	 */
	function &GetPage()
	{
		global $__psp__Page;
		
		return $GLOBALS[$__psp__Page];	
	}
	
	
	/** Static setting of the form
	 *
	 */
	function SetFormControl(&$form)
	{
		global $__psp__Form; 

		$GLOBALS[$__psp__Form] =& $form;	
	}
	
	/** Static retrival of the form
	 *
	 */
	function &GetFormControl()
	{
		global $__psp__Form; 
		
		return $GLOBALS[$__psp__Form];	
	}
	
	
	function SetResource($resource_file)
	{
		//set the resource file - only one per website at any time
		global $__psp__Resource; 
		
		$GLOBALS[$__psp__Resource] =& new Resource($resource_file);	
		
	}
	
	function &GetResource()
	{
		global $__psp__Resource; 
		
		return $GLOBALS[$__psp__Resource];		
	}
}

?>