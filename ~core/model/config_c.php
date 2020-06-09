<?php
class Config{
	public $db;
	public $fields;
	private $usual_fields;
	public $list;
	/** Конструктор
		 * @return
	 	 */
	 public function __construct (){
		$this->db =& $GLOBALS['db'];
		$this->usual_fields = array("id_config", "name", "caption", "value");
	}


	// по id
	public function SetFieldsById($id){
		$sql = "SELECT ".implode(", ",$this->usual_fields)."
			FROM "._DB_PREFIX_."config
			WHERE sid = 1 AND id_config = \"$id\"";
		$this->fields = $this->db->GetOneRowArray($sql);
		if(!$this->fields){
			return false;
		}
		return true;
	}

	// Обновление по имени
	public function SetFieldsByName($name){
		$sql = "SELECT ".implode(", ",$this->usual_fields)."
			FROM "._DB_PREFIX_."config
			WHERE sid = 1 AND name = '".$name."'";
		$this->fields = $this->db->GetOneRowArray($sql);
		if(!$this->fields){
			return false;
		}
		return true;
	}

	// Список
	public function SetList($param=0, $limit=""){
		if($limit != ""){
			$limit = " limit $limit";
		}
		$sql = "SELECT *
			FROM "._DB_PREFIX_."config
			WHERE sid = 1
			ORDER BY ord, name
			$limit";
		$this->list = $this->db->GetArray($sql);
		if(!$this->list){
			return false;
		}else{
			return true;
		}
	}

	// Добавление
	public function Add($arr){
		$f['id_config'] = trim($arr['id_config']);
		$f['name'] = trim($arr['name']);
		$f['caption'] = trim($arr['caption']);
		$f['value'] = trim($arr['value']);
		$this->db->StartTrans();
		if(!$this->db->Insert(_DB_PREFIX_.'config', $f)){
			$this->db->FailTrans();
			return false;
		}
		$id = $this->db->GetLastId();
		$this->db->CompleteTrans();
		return $id;
	}

	// Обновление
	public function Update($arr){
		$f['id_config'] = trim($arr['id_config']);
		$f['name'] = trim($arr['name']);
		$f['caption'] = trim($arr['caption']);
		$f['value'] = trim($arr['value']);
		$this->db->StartTrans();
		if(!$this->db->Update(_DB_PREFIX_."config", $f, "id_config = {$f['id_config']}")){
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	// Обновление по имени
	public function UpdateByName($name, $value){
		$f['name'] = trim($name);
		$f['value'] = trim($value);
		$this->db->StartTrans();
		if(!$this->db->Update(_DB_PREFIX_."config", $f, "name = '".$name."'")){
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	// Удаление
	public function Del($id){
		$sql = "DELETE FROM "._DB_PREFIX_."config WHERE `id_config` =  $id";
		$this->db->Query($sql) or G::DieLoger("<b>SQL Error - </b>$sql");
		return true;
	}

	// Сортировка страниц
	public function Reorder($arr){
		foreach ($arr['ord'] as $id_config=>$ord){
			$sql = "UPDATE "._DB_PREFIX_."config SET `ord` = $ord
					WHERE id_config = $id_config";
			$this->db->Query($sql) or G::DieLoger("<b>SQL Error - </b>$sql");
		}
	}
}
?>