<?php 

class Examination_Model_DbTable_ExamScriptContributor extends Zend_Db_Table_Abstract {
	
protected $_name = 'exam_script_contributor';
	protected $_primary = "IdContributor";
	

	public function getAllByScript($idScript){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->from(array('eg'=>$this->_name))
		->join(array('esm'=>'exam_script_main'),'eg.IdScript=esm.IdScript')
		->joinLeft(array('s'=>'tbl_staffmaster'),'s.IdStaff=eg.IdStaff',array('FullName','FrontSalutation','BackSalutation'))
		->joinLeft(array('s1'=>'tbl_staffmaster'),'s1.IdStaff=esm.verificator',array('verificatorname'=>'FullName','verfront'=>'FrontSalutation','verback'=>'BackSalutation'))
		->joinLeft(array('s2'=>'tbl_staffmaster'),'s2.IdStaff=esm.PrintedBy',array('printedby'=>'FullName','prtfront'=>'FrontSalutation','prtback'=>'BackSalutation'))
		
		->join(array('eat'=>'tbl_definationms'), 'eat.IdDefinition = eg.position', array('Position'=>'DefinitionDesc'))
		->where('eg.IdScript = ?',$idScript);
		$row = $db->fetchAll($select);
		//echo $select;exit;
		return $row;
	}
	
	public function getDataByContibutor($idscript,$idcontri){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->from(array('eg'=>$this->_name))
		 ->where('eg.IdScript = ?',$idscript)
		->where('eg.IdStaff=?',$idcontri);
		$row = $db->fetchRow($select);
		//echo $select;exit;
		return $row;
	}
	
	  
	public function insertData($data=array()){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->insert($this->_name,$data);
	}
	
	public function updateData($data=array(),$idcontributor){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->update($this->_name,$data,$this->_primary.'='.(int)$idcontributor);
	}
	
	public function deleteData($idcontributor){
	
		$db = Zend_Db_Table::getDefaultAdapter();
		return $db->delete($this->_name,$this->_primary.'='.(int)$idcontributor);
	}
}