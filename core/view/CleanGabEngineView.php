<?php
/**
 * CleanGab Framework
 * CleanGabEngineView.php
 * Date: 	2011-01-28
 * Author: 	Leopoldo Barreiro
 */

//header("Content-Type: text/html; charset=ISO-8859-1");

require_once ("Validate.php");
require_once ("UserToolsMenu.php");

class CleanGabEngineView {
	
	protected $controller;
	protected $operation;
	protected $objects;
	protected $templateFile;
	protected $templateXhtml;
	protected $file;
	protected $xhtml;
	protected $parsed;
	
	public function __construct($controller, $operation) 
	{
		$this->controller 	= $controller;
		$this->operation 	= $operation;
		$this->templateFile	= CLEANGAB_XHTML_TEMPLATE;
		$this->objects 		= array();
		
		$navigator 			= new stdClass();
		$navigator->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "#";
		$this->addObject("navigator", $navigator);
		if (isset($_SESSION) && isset($_SESSION["CLEANGAB"]))
		{
			$session = $_SESSION["CLEANGAB"];
			$this->addObject("session", $session);
			if (Session::isUserLogged())
			{
				$user = (object)$_SESSION["CLEANGAB"]["user"];
				$this->addObject("user", $user);
			}
		}
		$userToolsMenu = new UserToolsMenu();
		$this->addObject($userToolsMenu->getIdName(), $userToolsMenu);
		
		// Template XHTML
		$templatePath = "lib" . DIRECTORY_SEPARATOR . "xhtml" . DIRECTORY_SEPARATOR . $this->templateFile;
		$this->templateFile 	= (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $templatePath)) ? CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $templatePath : CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . $templatePath;
		$this->templateContent  = file_get_contents($this->templateFile);
		
		// XHTML Page
		$relativePath = "view" . DIRECTORY_SEPARATOR . strtolower($this->controller) . DIRECTORY_SEPARATOR . strtolower($this->operation) . ".xhtml";
		if (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $relativePath)) 
		{
			$this->file  = CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $relativePath;
			$this->xhtml = file_get_contents($this->file);
		} 
		elseif (file_exists(CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . $relativePath))
		{
			$this->file  = CLEANGAB_FWK_ROOT .  DIRECTORY_SEPARATOR . $relativePath;
			$this->xhtml = file_get_contents($this->file);
		}
		else 
		{
			$this->file  = $relativePath;
			$this->xhtml = file_get_contents(CLEANGAB_501);
		}
	}
	
	public function getController() 
	{
		return $this->controller;
	}
	
	public function setTemplate($template) 
	{
		$this->template = $template;
	}
	
	public function getTemplate() 
	{
		return $this->template;
	}
	
	final function parse() 
	{
		// TODO: revisar conteudo geral. Traduzir template, conteudo e blocos.
		$content = str_replace("#{content}", $this->xhtml, $this->templateContent);
		$tagNames   = $this->parserELTag($content);
		$content = $this->parseConstants($tagNames, $content);
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
					$content = str_replace("#{" . $tag . "}", $obj->{$eval}, $content);
				}
				else 
				{
					if ($obj instanceof XHTMLComponent)
					{
						$content = str_replace("#{" . $tag . "}", $obj->toXhtml(), $content);
					}
					else 
					{
						$content = str_replace("#{" . $tag . "}", (string)$obj, $content);
					}
				}
			}
		}
		$this->parsed = $content;
	}
	
	public function getParsed()
	{
		return $this->parsed;
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
	
	public function renderize() 
	{
		$this->parse();
		//$templateContent = $this->parserConstants($templateContent);
		//$viewContent = $this->parserConstants($this->parsed);
		echo $this->parsed;
	}
	
	private function isXhtmlComponent($mixed) 
	{
		return (is_object($mixed) && $mixed instanceof XHTMLComponent);
	}

	private function parserELTag($xhtml) 
	{
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
	
	private function parseConstants($tags, $content)
	{
		//$tags = $this->parserELTag($content);
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