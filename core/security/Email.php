<?php
/**
 * CleanGab Framework
 * Email.php
 * Date: 	2011-07-13
 * Author: 	Leopoldo Barreiro
 */

require_once("Validation.php");
// dependency: PEAR Mail http://pear.php.net/manual/en/package.mail.mail-mime.php
require_once("Mail.php");

class Email {
	
	private $headers;
	private $recipients;
	private $body;
	private $params;
	private $mailPear;
	
	public function __construct()
	{
		$this->headers = array();
		$this->recipients = array();
		$this->params = array();
	}
	
	public function addHeader($key, $value)
	{
		// TODO ver outros headers suportados
		$headersAllowed = array('To', 'From', 'Cc', 'Cco', 'Subject', 'X-Priority', 'Reply-To', 'Errors-To', 'Content-Type');
		if (in_array($key, $headersAllowed))
		{
			$this->headers[$key] = $value;
			return true;
		}
		return false; 
	}
	
	public function setSubject($subject)
	{
		$this->headers['Subject'] = $subject;
	}
	
	public function setParams($arrayParams)
	{
		$this->params = $arrayParams;
	}
	
	public function setTypeHtml()
	{
		$this->addHeader("Content-Type", "text/html");
		return true;
	}
	
	public function setTypePlainText()
	{
		$this->addHeader("Content-Type", "text/plain");
		return true;
	}
	
	public function addParam($key, $value)
	{
		$paramsAllowed = array("auth", "host", "username", "password", "port", "debug", "localhost", "timeout", "verp", "persist", "pipelining");
		if (in_array($key, $paramsAllowed))
		{
			$this->params[$key] = $value;
			return true;
		}
		return false; 
	}
	
	public function injectDefaultParams()
	{
		$defaultParams = unserialize(CLEANGAB_EMAIL_PARAMS);
		foreach ($defaultParams as $key=>$value)
		{
			$this->addParam($key, $value);
		}
		return true;
	}
	
	public function setBody($body)
	{
		$this->body = $body;
	}
	
	public function addRecipient($mixedEmail)
	{
		if (is_array($mixedEmail))
		{
			foreach ($mixedEmail as $email)
			{
				$this->recipients[] = $email;
			}
		}
		else 
		{
			$this->recipients[] = $mixedEmail;
		}
	}
	
	public function getRecipient($i=0)
	{
		if ($i < count($this->recipients))
		{
			return $this->recipients[$i];
		}
		return false;
	}
	
	public function send()
	{
		$this->mailPear = &Mail::factory('smtp', $this->params);
		$result = $this->mailPear->send($this->recipients, $this->headers, $this->body);
		if (PEAR::IsError($result))
		{
			CleanGab::debug($result->getMessage());
			return false;
		}
		return true;
	}

}
?>