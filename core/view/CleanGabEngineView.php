<?php
/**
 * CleanGab Framework
 * CleanGabEngineView.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 */

//header("Content-Type: text/html; charset=ISO-8859-1");

include_once ("Validate.php");

class CleanGabEngineView {
	
	protected $nameController;
	protected $operationController;
	protected $objects;
	protected $template;
	protected $xhtmlFile;
	protected $xhtmlContent;
	protected $translated;
	protected $navigator;
	
	public function __construct($nameController, $operationController) 
	{
		$this->nameController 		= $nameController;
		$this->operationController 	= $operationController;
		$this->template 			= CLEANGAB_XHTML_TEMPLATE;
		$this->objects 				= array();
		$this->navigator 			= new stdClass();
		$this->navigator->referer 	= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "#";
		if (isset($_SESSION) && isset($_SESSION["CLEANGAB"]))
		{
			$session = $_SESSION["CLEANGAB"];
			$this->addObject("session", $session);
		}
		$this->addObject("navigator", $this->navigator);
		$this->xhtmlFile = CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . strtolower($this->nameController) . DIRECTORY_SEPARATOR . strtolower($this->operationController) . ".xhtml";
		try 
		{
			if (file_exists($this->xhtmlFile)) 
			{
				$this->xhtmlContent = file_get_contents($this->xhtmlFile);
			} 
			elseif (file_exists("../cleangab/core/" . $this->xhtmlFile))
			{
				$this->xhtmlContent = file_get_contents("../cleangab/core/" . $this->xhtmlFile);
			}
			else 
			{
				include (CLEANGAB_501);
				die();
			}
		} 
		catch (Exception $e) 
		{
			CleanGab::stackTraceDebug($e);
		}
	}
	
	public function getNameController() 
	{
		return $this->nameController;
	}
	
	public function setTemplate($template) 
	{
		$this->template = $template;
	}
	
	public function getTemplate() 
	{
		return $this->template;
	}
	
	final function inject() 
	{
		$newContent = $this->xhtmlContent;
		$tagNames   = $this->parserELTag($newContent);
		foreach ($tagNames as $tag) 
		{
			if (strpos($tag, ".") > 0) 
			{
				$parts = explode(".", $tag);
				$methods = array();
				for ($i=1;$i<count($parts);$i++) 
				{
					$methods[] = $parts[$i];
				}
				$objectName = $parts[0];
			} 
			else 
			{
				$methods = false;
				$objectName = $tag;
			}
			$obj = $this->getObjectByName($objectName);
			if ($obj) 
			{
				if ($methods) 
				{
					$eval = implode(".", $methods);
					$newContent = str_replace("#{" . $tag . "}", $obj->{$eval}, $newContent);
				}
				else 
				{
					if ($obj instanceof XHTMLComponent)
					{
						$newContent = str_replace("#{" . $tag . "}", $obj->toXhtml(), $newContent);
					}
					else 
					{
						$newContent = str_replace("#{" . $tag . "}", (string)$obj, $newContent);
					}
				}
			}
		}
		$this->translated = $newContent;
	}
	
	public function addObject($identifier, $object)
	{
		if ($this->getObjectByName($identifier)) 
		{
			CleanGab::debug("Objects ja possui um membro com idName " . $identifier);
			return false;
		}
		$this->objects[$identifier] = (is_object($object)) ? $object : (object)$object;
		return true;
	}
	
	public function getTranslated()
	{
		return $this->translated;
	}
	
	public function renderize() 
	{
		$this->inject();
		$templatePath = "lib" . DIRECTORY_SEPARATOR . "xhtml" . DIRECTORY_SEPARATOR . $this->template;
		$templateContent = (file_exists($templatePath)) ? file_get_contents($templatePath) : file_get_contents("../cleangab/core/" . $templatePath);
		$templateContent = $this->parserConstants($templateContent);
		$viewContent = $this->parserConstants($this->translated);
		$completeContent = str_replace("#{content}", $viewContent, $templateContent);
		echo $completeContent;
	}
	
	private function isXhtmlComponent($mixed) 
	{
		return (is_object($mixed) && $mixed instanceof XHTMLComponent);
	}

	private function parserELTag($xhtml) 
	{
		//$pattern = "%(\#{){1}.[a-zA-Z_ ]+.(\.+)*.([a-zA-Z]*).(}){1}%";
		//$pattern = "%(\#{){1}.([a-zA-Z_]+)+.(\.+)*.([[:space:]]*)*.([a-zA-Z]+)+.(}){1}%";
		$pattern = "%(\#{){1}.(.)+.(\}){1}%";
		preg_match_all($pattern, $xhtml, $matches, PREG_PATTERN_ORDER);
		if (is_array($matches)) 
		{
			$tags = $matches[0];
		}
		for ($i=0; $i<count($tags); $i++) 
		{
			$tags[$i] = str_replace(array("#", "{", "}"), array("", "", ""), $tags[$i]);
		}
		return $tags;
	}
	
	private function getObjectByName($name) 
	{
		if (isset($this->objects[$name]))
		{
			return $this->objects[$name];
		}
		else 
		{
			return false;
		}
	}
	
	private function parserConstants($content)
	{
		$tags = $this->parserELTag($content);
		$constantPatter = '/^CLEANGAB_/';
		foreach ($tags as $tag)
		{
			if (preg_match($constantPatter, $tag))
			{
				if (defined($tag))
				{
					$content = str_replace("#{" . $tag . "}", constant($tag), $content);
				}
			}
		}
		return $content;
	}
}
?>