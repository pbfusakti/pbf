<?php 

class Examination_Model_DbTable_ExamGroupProgram extends Zend_Db_Table_Abstract {
	
	protected $_name = 'exam_group_program';
	protected $_primary = "egp_id";
	
	public function getGroupData($exam_group_id){
	
	  $db = Zend_Db_Table::getDefaultAdapter();
	
	  $select = $db ->select()
	  ->from(array('egp'=>$this->_name))
	  ->join(array('p'=>'tbl_program'), 'p.IdProgram = egp.egp_program_id')
	  ->where('egp.egp_eg_id = ?',$exam_group_id);
	  	
	  $row = $db->fetchAll($select);
	  	
	  return $row;
	}
}