<?php 

class Examination_Model_DbTable_ExamGroupSupervisor extends Zend_Db_Table_Abstract {
	
	protected $_name = 'exam_group_supervisor';
	protected $_primary = "egs_id";
	

	public function getData($id){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->from(array('egs'=>$this->_name))
		->where('eg.eg_id = ?',$idGroup);
		$row = $db->fetchRow($select);
		
		return $row;
	}
	
	public function getSupervisorList($group_id){
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from(array('egs'=>$this->_name))
					  ->joinLeft(array('sm'=>'tbl_staffmaster'),'sm.IdStaff = egs.egs_staff_id')
					  ->joinLeft(array('def'=>'tbl_definationms'),'egs.status=def.IdDefinition',array('status'=>'BahasaIndonesia'))
					  ->where('egs.egs_eg_id = ?',$group_id)
					  ->order('def.Description ASC');
							  
		$row = $db->fetchAll($select);
		 	
		return $row;
	}
	
	public function getTotalSupervisorByGroup($id){
		
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from($this->_name)
					  ->where("IdSubject = ?",$idCourse)
					  ->where('IdSemester = ?',$idSemester);					  
		 $row = $db->fetchAll($select);	
		 
		 if($row)
		 	return count($row);
		 else
		 return 0;
	}
	
	public function insert($data=array()){
	
		if( !isset($data['egs_create_by']) ){
			$auth = $auth = Zend_Auth::getInstance();
				
			$data['egs_create_by'] = $auth->getIdentity()->iduser;
		}
	
		if( !isset($data['egs_create_date']) ){
			$data['egs_create_date'] = date('Y-m-d H:i:a');
		}
	
		return parent::insert($data);
	}
	
	
	
}