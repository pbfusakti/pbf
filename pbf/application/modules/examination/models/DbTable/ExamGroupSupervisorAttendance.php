<?php 

class Examination_Model_DbTable_ExamGroupSupervisorAttendance extends Zend_Db_Table_Abstract {
	
	protected $_name = 'exam_group_supervisor_attendance';
	protected $_primary = "ega_id";
	
	public function getData($group_id, $student_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db ->select()
			->from(array('ega'=>$this->_name))
			->where('ega.ega_eg_id = ?',$group_id)
			->where('ega.ega_supervisor_id =?', $student_id);
		
		$row = $db->fetchRow($select);
		
		 
			return $row; 
	}
	
	public function getGroupData($idGroup){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
					->from(array('ega'=>$this->_name))
					->where('ega.ega_eg_id = ?',$idGroup);
		
		$row = $db->fetchAll($select);
		
		if($row){
			return $row;
		}else{
			return null;
		}
		
	}
	
	public function getExamAttendaceStatus($idsemester,$idSubject,$type,$idstudent,$status){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->from(array('ega'=>$this->_name))
		->join(array('eg'=>'exam_group'),'ega.ega_eg_id=eg.eg_id',array())
		->where('eg.eg_sem_id = ?',$idsemester)
		->where('ega.ega_supervisor_id=?',$idstudent)
		->where('ega.ega_status=?',$status)
		->where('eg.eg_assessment_type=?',$type)
		->where('eg.eg_sub_id = ?',$idSubject);
	
		$row = $db->fetchRow($select);
		//echo $select;exit;
		return $row;
		 
	
	}	
	public function insert($data=array()){
	
		if( !isset($data['ega_last_edit_by']) ){
			$auth = $auth = Zend_Auth::getInstance();
				
			$data['ega_last_edit_by'] = $auth->getIdentity()->iduser;
		}
	
		if( !isset($data['ega_last_edit_date']) ){
			$data['ega_last_edit_date'] = date('Y-m-d H:i:a');
		}
	
		return parent::insert($data);
	}
	
	public function update($data=array(),$where){
		if( !isset($data['ega_last_edit_by']) ){
			$auth = $auth = Zend_Auth::getInstance();
		
			$data['ega_last_edit_by'] = $auth->getIdentity()->iduser;
		}
		
		if( !isset($data['ega_last_edit_date']) ){
			$data['ega_last_edit_date'] = date('Y-m-d H:i:a');
		}
		
		return parent::update($data,$where);
	}
	
}