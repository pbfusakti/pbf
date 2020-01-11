<?php 

class Examination_Model_DbTable_ExamGroup extends Zend_Db_Table_Abstract {
	
	protected $_name = 'exam_group';
	protected $_primary = "eg_id";
	

	public function getData($idGroup){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->from(array('eg'=>$this->_name))
		->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=eg.eg_sub_id',array('subject_code'=>'SubCode','subject_name'=>'BahasaIndonesia','faculty_id'=>'IdFaculty'))
		->joinLeft(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=eg.eg_sem_id',array('semester_name'=>'SemesterMainName'))
		->joinLeft(array('r'=>'appl_room'),'r.av_id=eg.eg_room_id')
		->join(array('eat'=>'tbl_examination_assessment_type'), 'eat.IdExaminationAssessmentType = eg.eg_assessment_type', array('eg_exam_name'=>'DescriptionDefaultlang'))
		->where('eg.eg_id = ?',$idGroup);
		$row = $db->fetchRow($select);
		
		return $row;
	}
	
	public function getGroupList($idSubject,$idSemester,$idprogram=null,$examtype=null){
		
		$session = new Zend_Session_Namespace('sis');
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					  ->from(array('eg'=>$this->_name))
					  ->joinLeft(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=eg.eg_sub_id',array('subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage','faculty_id'=>'IdFaculty'))
					  ->joinLeft(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=eg.eg_sem_id',array('semester_name'=>'SemesterMainName'))
					  ->joinLeft(array('r'=>'appl_room'),'r.av_id=eg.eg_room_id')
					  ->joinLeft(array('ep'=>'exam_group_program'),'eg.eg_id=ep.egp_eg_id')
					  ->joinLeft(array('p'=>'tbl_program'),'p.IdProgram=ep.egp_program_id')
					  ->join(array('eat'=>'tbl_examination_assessment_type'), 'eat.IdExaminationAssessmentType = eg.eg_assessment_type', array('eg_exam_name'=>'DescriptionDefaultlang'))
					  ->where('eg.eg_sub_id = ?',$idSubject)
					  ->where('eg.eg_sem_id = ?',$idSemester);
			if ($examtype!='') $select->where('eg.eg_assessment_type=?',$examtype);
			if($session->IdRole == 605 || $session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("p.IdCollege='".$session->idCollege."' or p.IdCollege is null");
			}
			if($session->IdRole == 470 || $session->IdRole == 480){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("p.IdProgram='".$session->idDepartment."' or ep.egp_program_id is null");
			}	  
		 
			$row = $db->fetchAll($select);
		 	
		 return $row;
	}
	 
	
	public function getDateList($idSemester=null,$idprogram=null,$idsubject=null,$examtype=null){
		
		$session = new Zend_Session_Namespace('sis');
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$select = $db ->select()
					->distinct()
					  ->from(array('eg'=>$this->_name),array('examdate'=>'eg_date'))
					   ->joinLeft(array('ep'=>'exam_group_program'),'eg.eg_id=ep.egp_eg_id',array())
					  ->joinLeft(array('p'=>'tbl_program'),'p.IdProgram=ep.egp_program_id',array())
					 ;
			if ($idSemester!=null) $select->where('eg.eg_sem_id = ?',$idSemester);
			if ($idsubject!=null) $select->where('eg.eg_sub_id = ?',$idsubject)	;	  
			if ($idprogram!=null) $select->where('p.IdProgram = ?',$idprogram)	; 
			if ($examtype!=null) $select->where('eg.eg_assessment_type = ?',$examtype)	;
			//echo $select;exit;
			$row = $db->fetchAll($select);
		 	
		 return $row;
	}
	
	public function getSubjectList($idSemester=null,$idprogram=null,$idsubject=null,$examtype=null){
	
		$session = new Zend_Session_Namespace('sis');
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->distinct()
		->from(array('eg'=>$this->_name),array())
		->join(array('sb'=>'tbl_subjectmaster'),'sb.IdSubject=eg.eg_sub_id',array('key'=>'sb.IdSubject','value'=>'CONCAT(sb.BahasaIndonesia," ",sb.shortname)'))
		->joinLeft(array('ep'=>'exam_group_program'),'eg.eg_id=ep.egp_eg_id',array())
		->joinLeft(array('p'=>'tbl_program'),'p.IdProgram=ep.egp_program_id',array());
		
		if ($idSemester!=null) $select->where('eg.eg_sem_id = ?',$idSemester);
		if ($idsubject!=null) $select->where('eg.eg_sub_id = ?',$idSubject)	;
		if ($idprogram!=null) $select->where('p.IdProgram = ?',$idprogram)	;
		if ($examtype!=null) $select->where('eg.eg_assessment_type = ?',$examtype)	;
	
		$row = $db->fetchAll($select);
	
		return $row;
	}
	
	
	public function getRecapeGroupList($idSemester,$idprogram,$type,$idsubject=null,$day=null,$room=null){
	
		$session = new Zend_Session_Namespace('sis');
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
		->from(array('eg'=>$this->_name))
		->join(array('sm'=>'tbl_subjectmaster'),'sm.IdSubject=eg.eg_sub_id',array('subject_code'=>'SubCode','subject_name'=>'subjectMainDefaultLanguage','faculty_id'=>'IdFaculty'))
		->join(array('s'=>'tbl_semestermaster'),'s.IdSemesterMaster=eg.eg_sem_id',array('semester_name'=>'SemesterMainName'))
		->joinLeft(array('r'=>'appl_room'),'r.av_id=eg.eg_room_id')
		->join(array('ep'=>'exam_group_program'),'eg.eg_id=ep.egp_eg_id')
		->join(array('p'=>'tbl_program'),'p.IdProgram=ep.egp_program_id')
		->join(array('eat'=>'tbl_examination_assessment_type'), 'eat.IdExaminationAssessmentType = eg.eg_assessment_type', array('eg_exam_name'=>'DescriptionDefaultlang'))
		->where('eg.eg_sem_id = ?',$idSemester)
		->where('p.IdProgram = ?',$idprogram)
		->where('eg.eg_assessment_type = ?',$type)
		->order('eg.eg_date')
		->order('eg.eg_start_time'); 
		
		if ($idsubject!=null) $select->where('eg.eg_sub_id = ?',$idsubject);
		if ($day!=null) $select->where('eg.eg_date = ?',$day);
		if ($room!=null && $room!='') $select->where('eg.eg_room_id = ?',$room);
		//echo $select;exit;
			
		$row = $db->fetchAll($select);
	
		return $row;
	}
	public function getTotalGroupByCourse($idCourse,$idSemester){
		
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
	
		if( !isset($data['eg_create_by']) ){
			$auth = $auth = Zend_Auth::getInstance();
				
			$data['eg_create_by'] = $auth->getIdentity()->iduser;
		}
	
		if( !isset($data['eg_create_date']) ){
			$data['eg_create_date'] = date('Y-m-d H:i:a');
		}
	
		return parent::insert($data);
	}
	
	
	
}