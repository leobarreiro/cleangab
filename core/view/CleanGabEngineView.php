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
	
	public function __construct($nameController, $operationController, $objects=null) 
	{
		$this->nameController 		= $nameController;
		$this->operationController 	= $operationController;
		$this->template 			= CLEANGAB_XHTML_TEMPLATE;
		$this->objects 				= array();
		if (is_array($objects)) 
		{
			foreach ($objects as $obj) 
			{
				$this->add($obj);
			}
		} 
		else 
		{
			$this->add($objects);
		}
		$this->xhtmlFile = "view" . DIRECTORY_SEPARATOR . strtolower($this->nameController) . DIRECTORY_SEPARATOR . strtolower($this->operationController) . ".xhtml";
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
			if (strpos($tag, ".")) 
			{
				$parts = explode(".", $tag);
				$parts = array_shift($parts);
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
			$obj = $this->getXhtmlComponentByName($objectName);
			if ($obj) 
			{
				if ($methods) 
				{
					$eval = implode("->", $methods);
					$newContent = str_replace("#{".$tag."}", eval($obj->$eval), $newContent);
				} 
				else 
				{
					$newContent = str_replace("#{".$tag."}", $obj->toXhtml(), $newContent);
				}
			}
		}
		$this->translated = $newContent;
	}
	
	public function add($object) 
	{
		if ($this->isXhtmlComponent($object)) 
		{
			if ($this->getXhtmlComponentByName($object->getIdName())) 
			{
				CleanGab::debug("Objects ja possui um membro com idName ".$object->getIdName());
				return false;
			}
			$this->objects[] = $object;
			return true;
		}
		CleanGab::debug("Objects so aceita objetos que implementam XhtmlComponent");
		return false;
	}
	
	public function renderize() 
	{
		$this->inject();
		$templatePath = "lib" . DIRECTORY_SEPARATOR . "xhtml" . DIRECTORY_SEPARATOR . $this->template;
		$content = (file_exists($templatePath)) ? file_get_contents($templatePath) : file_get_contents("../cleangab/core/" . $templatePath);
		$content = $this->parserConstantsIntoTemplateBase($content);
		$content = str_replace("#{content}", $this->translated, $content);
		echo $content;
	}
	
	private function isXhtmlComponent($mixed) 
	{
		return (is_object($mixed) && $mixed instanceof XHTMLComponent);
	}

	private function parserELTag($xhtml) 
	{
		//TODO: aprimorar esta expressao regular
		$pattern = "%(\#{){1}.[a-zA-Z_]+.(\.+)*.([a-zA-Z]*).(}){1}%";
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
	
	private function getXhtmlComponentByName($name) 
	{
		foreach ($this->objects as $obj) 
		{
			if ($obj->getIdName() == $name) 
			{
				return $obj;
			}
		}
		return false;
	}
	
	private function parserConstantsIntoTemplateBase($templateContent)
	{
		$tagsTemplateBase = $this->parserELTag($templateContent);
		$constantPatter = '/^CLEANGAB_/';
		foreach ($tagsTemplateBase as $tag)
		{
			if (preg_match($constantPatter, $tag))
			{
				if (defined($tag))
				{
					$templateContent = str_replace("#{" . $tag . "}", constant($tag), $templateContent);
				}
			}
		}
		return $templateContent;
	}
}
?>