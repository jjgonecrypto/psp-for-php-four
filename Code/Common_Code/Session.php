<?php

/** Static session handling class.
 *
 * @version Php4
 */ 
class Session
{
	function SetLifetime($int)
	{
		//provide for a session lifetime
		ini_set("session.gc_maxlifetime", $int);
		
		//provide for cookie expiration
		session_set_cookie_params($int);
		
		
		//NOTE: UNTESTED
		//set in minutes, the cache expiration
		//ini_set("session.cache_expire",$int/60);
		
		//NOTE: Set path also effects the lifetime. It must be called
		
		//see 
		//http://us.php.net/manual/en/ref.session.php
		//"Eric dot Deplagne at nerim dot net"
	} 	
	
	function SetPath($path)
	{
		if (!is_dir($path))
		{
			trigger_error("Supplied path for Session.SetPath() doesn't exist: $path",E_USER_ERROR);	
		}
		
		session_save_path($path);
	}
	
	
	function SetName($name)
	{
		return session_name($name);	
	}
	
	
	function Start()
	{
		return session_start();	
	}
	
	
	/** Check for the existence of a session variable.
	 *
	 */
	function Exists($key)
	{
		return isset($_SESSION[$key]);	
	}
	
	
	function Get($key)
	{
		if (!Session::Exists($key))
		{
			return null;	
		}
		else
		{
			return $_SESSION[$key];	
		}
	}
	
	
	function Set($key, $value)
	{
		$_SESSION[$key] = $value;	
	}
	
	
	function End()
	{
		return session_destroy();	
	}
	
}
?>