<?php

require_once ("XHTMLComponent.php");

class UserToolsMenu implements XHTMLComponent {
	
	protected $idName;
	protected $content;
	
	public function __construct() 
	{
		$this->idName = "userToolsMenu";
		$this->assemble();
	}
	
	public function assemble() 
	{
		$userMenuPart = (Session::isUserLogged()) ? "user_menu.xhtml" : "user_login.xhtml";
		$relativeUserToolsMenuPath = "lib" . DIRECTORY_SEPARATOR . "xhtml" . DIRECTORY_SEPARATOR . $userMenuPart;
		$file = (file_exists(CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $relativeUserToolsMenuPath)) ? CLEANGAB_PATH_BASE_APP . DIRECTORY_SEPARATOR . $relativeUserMenuPath : CLEANGAB_FWK_ROOT .  DIRECTORY_SEPARATOR . $relativeUserToolsMenuPath;
		$this->content = file_get_contents($file);
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
}
?>