<?php

/** Mail sender for PHP4 and 5
 *
 * @package psp.for.php.four
 * @author Justin J. Moses <justin@justinjmoses.com.au>
 * @copyright Copyright © 2007 Justin J. Moses
 * @version php4
 * @license http://web.justinjmoses.com.au/psp
 */

class Mail
{
	/**
	 * @var array
	 */
	var $To = null;
	
	
	var $From = null; 
	
	var $ReplyTo = null;
	
	/**
	 * @var array
	 */
	var $CC = null;
	
	
	
	/**
	 * @var array
	 */
	 var $BCC = null;
	
	
	var $Subject = null;
	
	
	var $PlainBody = null;
	
	var $HTMLBody = null;
	
	function Mail()
	{
		$this->To = array();
		
		$this->CC = array();
		
		$this->BCC = array();	
	}
	
	function AddRecipient($address)
	{
		if (!$this->TestEmail($address))
		{
			trigger_error("Invalid email address: $address.",E_USER_ERROR);
		}	
		
		$this->To[] = $address;	
	}
	
	function ResetRecipients()
	{
		$this->To = array();	
	}
	
	function AddCarbonCopy($address)
	{
		if (!$this->TestEmail($address))
		{
			trigger_error("Invalid email address: $address.",E_USER_ERROR);
		}
		
		$this->CC[] = $address;	
	}
	
	function AddBlankCarbonCopy($address)
	{
		if (!$this->TestEmail($address))
		{
			trigger_error("Invalid email address: $address.",E_USER_ERROR);
		}
		
		$this->BCC[] = $address;	
	}
	
	/** Checks the validity of an email address.
	 * @param string Email address
	 * @return bool Returns TRUE if the supplied address is a valid email address, FALSE otherwise.
	 */
	function TestEmail($email)
	{
		// regx to test for valid e-mail address
		$regex = '^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$';
		
		return eregi($regex, $email);
	}
	
	function GetBodyOutput($boundary)
	{
		
		$body = "";
		
		if ($this->PlainBody != null)
		{
			$body .= "\r\n--$boundary\r\nContent-Type: text/plain; charset=\"utf-8\"\r\n\r\n".$this->PlainBody;
		}		
	
		if ($this->HTMLBody != null)
		{
			$body .= "\r\n--$boundary\r\nContent-Type: text/html; charset=\"utf-8\"\r\n\r\n".$this->HTMLBody;
		}
		
		if ($body == "")
		{
			trigger_error("Neither Plain or HTML body set for email.",E_USER_ERROR);	
		}
			
		$body .= "\r\n--$boundary--";	
			
		return $body;
	}
	
	/** Send the mail.
	 *
	 * @return bool Returns TRUE if the mail was successfully accepted for delivery, FALSE otherwise. 
	 */
	function Send()
	{
		if ($this->From == null)
		{
			trigger_error("The From property must be set in a Mail object before the Send() method can be called.",E_USER_ERROR);	
		}
		
		if (count($this->To) < 1)
		{
			trigger_error("Recipients must be added to a Mail obejct before it can be sent.",E_USER_ERROR);	
		}
		
		
		
		$headers  = "MIME-Version: 1.0\r\n";
		
		$random_hash = md5(date('r', time())); 
		
		$boundary = "AltMail-".$random_hash;
		
		//allow both plain and HTML email
		$headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n"; 
			
		
		$headers .= "From: ".$this->From."\r\n";
		
		if ($this->ReplyTo != null)
		{
			$headers .= "Reply-To: ".$this->ReplyTo."\r\n";
		}
		
		//NOTE: not needed - adds another recipient.
		//$headers .= "To: ".implode(",",$this->To)."\r\n";
		
		if (count($this->CC) > 0)
		{
			$headers .= "CC: ".implode(",",$this->CC)."\r\n";	
		}
		
		if (count($this->BCC) > 0)
		{
			$headers .= "BCC: ".implode(",",$this->BCC)."\r\n";	
		}
		
		
		//NOTE: this won't work unless smtp is setup in php.ini
		//send mail
		return mail(implode(",",$this->To), $this->Subject, $this->GetBodyOutput($boundary), $headers);
		
	}
	
}

?>