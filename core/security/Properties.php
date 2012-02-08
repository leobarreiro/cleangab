<?php
/**
 * CleanGab Framework
 * Properties.php
 * Date: 	2012-01-11
 * Author: 	Leopoldo Barreiro
 *
 */
require_once("CleanGab.php");
require_once("Validation.php");

class Properties {
	
	/**
	 * Conteudo das properties carregadas
	 * @var array
	 */
	private $content;
	
	/**
	 * 
	 * Carrega o arquivo de properties para uso
	 * @param string $pathPropertiesFile
	 * @return boolean
	 */
	public function getProperties($pathPropertiesFile)
	{
		if (file_exists($pathPropertiesFile)) 
		{
			$text = file_get_contents($pathPropertiesFile);
			$linesContent = explode("\n", $text);
			$parsedContent = array();
			
			foreach ($linesContent as $line)
			{
				if (strpos($line, "=") > 0)
				{
					$arLine = explode("=", $line);
					$parsedContent[$arLine[0]] = $arLine[1];
				}
			}
			$this->content = $parsedContent;
			return true;
		}
		else 
		{
			CleanGab::log(get_class($this) . " - The specified file does not exist: " . $pathPropertiesFile);
			return false;
		}
	}
	
	public function setContent($arContent)
	{
		$this->content = $arContent;
	}
	
	public function getContent()
	{
		return $this->content;
	}
	
	public static function get($key) 
	{
		Validation::notEmpty($key, "Key property is empty");
		if (Properties::isPropertyMessagesLoaded() && Properties::isKeyDefined($key))
		{
			return $_SESSION["CLEANGAB"]["propertymessages"][$key];
		}
		else 
		{
			return $key;
		}
	}
	
	public static function isPropertyMessagesLoaded()
	{
		return (isset($_SESSION["CLEANGAB"]) && isset($_SESSION["CLEANGAB"]["propertymessages"]) && count($_SESSION["CLEANGAB"]["propertymessages"]) > 0);
	}
	
	public static function isKeyDefined($key)
	{
		return (isset($_SESSION["CLEANGAB"]["propertymessages"][$key]));
	}
	
	public static function getAllProperties()
	{
		if (Properties::isPropertyMessagesLoaded())
		{
			return $_SESSION["CLEANGAB"]["propertymessages"];
		}
		else 
		{
			return false;
		}
	}
	
}

?>