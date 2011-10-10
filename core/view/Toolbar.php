<?php
require_once ("XHTMLComponent.php");
class Toolbar implements XHTMLComponent {
	
	protected $idName;
	private   $controller;
	private   $operation;
	protected $content;
	public    $buttons;
	protected $xml;
	
	public function __construct($controller, $operation) 
	{
		$this->idName     = "toolbar";
		$this->controller = $controller;
		$this->operation  = $operation;
		$this->buttons    = array();
		
		if ($_SESSION["CLEANGAB"]["xmlmenu"] == null)
		{
			Session::loadXmlPermissions();
		} 
		$this->xml = simplexml_load_string($_SESSION["CLEANGAB"]["xmlmenu"]);
	}
	
	public function assemble() 
	{
		$xhtml   = array();
		$xhtml[] = "<div class=\"toolbar\">";
		$xhtml[] = "<ul class=\"dropdown\" id=\"menu\">";
		$xhtml[] = "<li><a href=\"#\" class=\"user\">#{user.name}</a>";
		$xhtml[] = "<ul class=\"sub_menu\">";
		foreach ($this->xml as $module) 
		{
			$xhtml[] = "<li>";
			//TODO Listar somente os modulos onde o usuario possua ao menos uma permissao
			$xhtml[] = "<a href=\"#\" class=\"" . $module["key"] . "\">" . $module["name"] . "</a>";
			$xhtml[] = "<ul>";
			foreach ($module->permission as $prm) 
			{
				if (Session::permissionExists($prm["key"]) && $prm["menu"] == "yes") 
				{
					$xhtml[] = "<li><a href=\"#{CLEANGAB_URL_BASE_APP}" . $prm['uri'] . "\" class=\"" . $prm["key"] . "\">" . $prm["name"] . "</a></li>";
				}
			}
			$xhtml[] = "</ul>";
			$xhtml[] = "</li>";
		}
		$xhtml[] = "<li><a href=\"#{CLEANGAB_URL_BASE_APP}/user/options/\" class=\"user-settings\">My account</a></li>";
		$xhtml[] = "<li><a href=\"#{CLEANGAB_URL_BASE_APP}/user/logoff/\" class=\"logoff\">Logoff</a></li>";
		$xhtml[] = "</ul>";
		$xhtml[] = "</li>";
		$xhtml[] = "</ul>";
		$xhtml[] = "<div id=\"toolbar-buttons\">";
		$xhtml[] = $this->assembleToolbarButtons();
		$xhtml[] = "</div>";
		$xhtml[] = "</div>";
		$this->content = implode("\n", $xhtml);
	}
	
	public function getIdName() 
	{
		return $this->idName;
	}
	
	public function inject($mixedContent) 
	{
		$this->content = $mixedContent;
	}

	public function toXhtml() 
	{
		$this->assemble();
		return $this->content;
	}
	
	private function assembleToolbarButtons()
	{
		$xhtml = array();
		foreach ($this->buttons as $bt)
		{
			$xhtml[] = $bt->toXhtml();
		}
		return implode("\n", $xhtml);
	}
	
	public function getController()
	{
		return $this->controller;
	}
	
	public function getOperation()
	{
		return $this->operation;
	}
	
	public function setController($controller)
	{
		$this->controller = $controller;
	}
	
	public function setOperation($operation)
	{
		$this->operation = $operation;
	}
	
	public function addButton($actButton)
	{
		foreach ($this->buttons as $tbBt)
		{
			if (strtolower($tbBt->getIdName()) == strtolower($actButton->getIdName()))
			{
				return false;
			}
		}
		$this->buttons[] = $actButton;
		return true;
	}
}
?>