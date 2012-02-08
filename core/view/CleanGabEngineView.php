<?php
/**
 * CleanGab Framework
 * CleanGabEngineView.php
 * Date: 2011-01-28
 * Author: Leopoldo Barreiro
 */

//header("Content-Type: text/html; charset=ISO-8859-1");

require_once ("Validation.php");
require_once ("UserToolsMenu.php");
require_once ("Toolbar.php");

class CleanGabEngineView {
	
	protected $controller;
	protected $operation;
	public    $toolbar;
	protected $objects;
	protected $templateFile;
	protected $templateXhtml;
	protected $file;
	protected $xhtml;
	protected $parsed;
	
	public function __construct($controller, $operation) 
	{
		$this->controller 	= strtolower($controller);
		$this->operation 	= strtolower($operation);
		
		$this->setTemplateFile(CLEANGAB_XHTML_TEMPLATE);
		$this->objects 		= array();
		
		$navigator 			= new stdClass();
		$navigator->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "#";
		$this->addObject("navigator", $navigator);
		
		if (isset($_SESSION) && isset($_SESSION["CLEANGAB"]))
		{
			$session = $_SESSION["CLEANGAB"];
			$this->addObject("session", $session);
		}

		if (Session::isUserLogged())
		{
			$user = (object)$_SESSION["CLEANGAB"]["user"];
			$this->addObject("user", $user);
			$this->toolbar = new Toolbar($this->controller, $this->operation);
		}
		else 
		{
			$userToolsMenu = new UserToolsMenu();
			$this->addObject($userToolsMenu->getIdName(), $userToolsMenu);
		}
		
		// Template XHTML
		//$templatePath = "lib" . DIRECTORY_SEPARATOR . "xhtml" . DIRECTORY_SEPARATOR . $this->templateFile;
		//$this->templateFile 	= (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $templatePath)) ? CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $templatePath : CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . $templatePath;
		//$this->templateContent  = file_get_contents($this->templateFile);
		
		// XHTML Page
		$relativePath = "view" . DIRECTORY_SEPARATOR . strtolower($this->controller) . DIRECTORY_SEPARATOR . strtolower($this->operation) . ".xhtml";
		if (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $relativePath)) 
		{
			$this->file  = CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $relativePath;
			$this->xhtml = file_get_contents($this->file);
		} 
		elseif (file_exists(CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . $relativePath))
		{
			$this->file  = CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . $relativePath;
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
	
	public function getOperation()
	{
		return $this->operation;
	}
	
	public function getToolbar()
	{
		return $this->toolbar;
	}
	
	public function setTemplateFile($nameFile=null) 
	{
		if ($nameFile != null)
		{
			$templatePath 		 = "lib" . DIRECTORY_SEPARATOR . "xhtml" . DIRECTORY_SEPARATOR . $nameFile;
			$this->templateFile  = (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $templatePath)) ? CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $templatePath : CLEANGAB_FWK_ROOT . DIRECTORY_SEPARATOR . $templatePath;
			$this->templateXhtml = file_get_contents($this->templateFile);
		}
		else 
		{
			$this->templateFile  = "";
			$this->templateXhtml = "#{content}";
		}
	}
	
	public function getTemplateFile() 
	{
		return $this->templateFile;
	}
	
	public function setTemplateXhtml($templateXhtml)
	{
		$this->templateXhtml = $templateXhtml;
	}
	
	public function getTemplateXhtml()
	{
		return $this->templateXhtml;
	}
	
	private final function injectXhtmlFromComponents($content) 
	{
		foreach ($this->objects as $obj) 
		{
			if ($this->isXhtmlComponent($obj)) 
			{
				$content = str_replace("#{" . $obj->getIdName() . "}", $obj->toXhtml(), $content);
			}
		}
		return $content;
	}
	
	public final function parse() 
	{
		if (Session::isUserLogged())
		{
			$this->addObject($this->toolbar->getIdName(), $this->toolbar);
		}
		$content = str_replace("#{content}", $this->xhtml, $this->templateXhtml);
		$content = $this->injectXhtmlFromComponents($content);
		$tags    = $this->parseELTag($content);
		$content = $this->parseConstants($tags, $content);
		foreach ($tags as $tag) 
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
					if (isset($obj->{$eval}))
					{
						if ($obj->{$eval} instanceof XHTMLComponent)
						{
							$content = str_replace("#{" . $tag . "}", $obj->{$eval}->toXhtml(), $content);
						}
						else if ($obj->{$eval} instanceof Formatter)
						{
							$content = str_replace("#{" . $tag . "}", $obj->{$eval}->toScreen($obj->{$eval}->getDataBaseContent()), $content);
						}
						else 
						{
							$content = str_replace("#{" . $tag . "}", $obj->{$eval}, $content);
						}
					}
					else 
					{
						$content = str_replace("#{" . $tag . "}", "", $content);
					}
				}
				else 
				{
					if ($obj instanceof XHTMLComponent)
					{
						$content = str_replace("#{" . $tag . "}", $obj->toXhtml(), $content);
					}
					else if ($obj instanceof Formatter)
					{
						$content = str_replace("#{" . $tag . "}", $obj->toScreen($obj->getDataBaseContent()), $content);
					}
					else 
					{
						
						$content = str_replace("#{" . $tag . "}", $obj, $content);
					}
				}
			}
			else 
			{
				$content = str_replace("#{" . $tag . "}", "", $content);
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
			CleanGab::log("Objects ja possui um membro com idName " . $identifier);
			return false;
		}
		$this->objects[$identifier] = $object;
		return true;
	}
	
	public function renderize() 
	{
		$this->parse();
		echo $this->parsed;
	}
	
	private function isXhtmlComponent($mixed) 
	{
		return (is_object($mixed) && $mixed instanceof XHTMLComponent);
	}

	private function parseELTag($xhtml) 
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