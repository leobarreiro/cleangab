<?php
require_once ("CleanGabModel.php");

class RedirModel extends CleanGabModel {
	
	public function __construct() 
	{
		parent::__construct();
		$this->listableFields = array("id", "name", "value", "type");
		$this->masks = array();
		$this->hintFields = array("id"=>"Id", "name"=>"Name", "value"=>"Value", "type"=>"Type");
	}
	
	public function addRedir($url)
	{
		if (preg_match("|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i", $url))
		{
			$entity = new Entity("redirection");
			$redir = new stdClass();
			$redir->url = $url;
			$user = Session::getUser();
			$redir->user_id = $user->id;
			$redir->uuid = uniqid();
			$redir = $entity->createObjectToPersist($redir);
			$entity->setObjectToPersist($redir);
			$saved = $entity->save();
			if ($saved !== false)
			{
				$entity->addArgument("id", $saved, "=");
				$entity->setCountThis(false);
				$entity->setLimit(1);
				$recordset = $entity->retrieve();
				if ($recordset->hasRecords())
				{
					$record = $recordset->getRecord();
					return $record->uuid;
				}
			}
			else 
			{
				CleanGab::log("URL redir could not be retrieved after being saved.");
				return false;
			}
		}
		else 
		{
			CleanGab::log("URL redir is not valid");
			return false;
		}
	}
	
	public function getRedirByUuid($uuid)
	{
		$entity = new Entity("redirection");
		$entity->setCountThis(false);
		$entity->setLimit(1);
		$entity->addArgument("uuid", $uuid, "=");
		// TODO: Implementar data de validade para o link de redirecionamento
		$recordset = $entity->retrieve();
		if ($recordset->hasRecords())
		{
			return $recordset->getRecord();
		}
		else 
		{
			return false;
		}
	}
	
	public function getUrlByUuuid($uuid)
	{
		$redir = $this->getRedirByUuid($uuid);
		if ($redir)
		{
			return $redir->url;
		}
		else 
		{
			return false;
		}
	}
	
	public function getRedirLink($uuid)
	{
		$redir = $this->getRedirByUuid($uuid);
		if ($redir)
		{
			return CLEANGAB_URL_BASE_APP . "/redir/goto/" . $uuid;
		}
		else 
		{
			return false;
		}
	}

}

?>