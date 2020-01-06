<?php 
class App_Model_General_DbTable_Semestermaster extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_semestermaster';
    protected $_primary = 'IdSemesterMaster';
    
    private $lobjDbAdpt;
    
	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}
    
	public function fnGetSemestermaster($semester_id){
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"))
		->where('a.IdSemesterMaster = ?',$semester_id)
		->order("a.SemesterMainName Desc");
		$larrResult = $this->lobjDbAdpt->fetchRow($lstrSelect);
		
		if($larrResult){
			return $larrResult;
		}else{
			return null;
		}
	}
	
    public function fngetSemestermainDetails($IdSemesterMaster="") { //Function to get the user details
        if(trim($IdSemesterMaster) == "") {
     			
     			$select = $this->select()
     			        ->setIntegrityCheck(false)
     			        ->from(array('a'=>$this->_name))
             			->join(array('acy' => 'tbl_academic_year'),'acy.ay_id = a.idacadyear',array("academicYear"=>'ay_code', 'ay_id'=>'ay_id'))
             			->order('acy.ay_code DESC')
             			->order('a.SemesterCountType DESC')
     			        ->order('a.SemesterFunctionType DESC');
     			
     			$result = $this->fetchAll($select);
        }else{
          
            $select = $this->select()
                      ->setIntegrityCheck(false)
                      ->from(array('a'=>$this->_name))
                      ->join(array('acy' => 'tbl_academic_year'),'acy.ay_id = a.idacadyear',array("academicYear"=>'ay_code', 'ay_id'=>'ay_id'))
                      ->where("a.IdSemesterMaster = $IdSemesterMaster")
                      ->order('a.SemesterMainName');
            
            $result = $this->fetchAll($select);
        }
        
        return $result->toArray();
     }
    
    public function fnaddSemester($formData) { //Function for adding the University details to the table
    	unset($formData['SemesterCode']);
    	unset($formData['StudentIntake']);
    	unset($formData['SemesterStartDate']);
    	unset($formData['SemesterEndDate']);
    	unset($formData['Program']);
    	unset($formData['SemesterStatus']);
    	unset($formData['Save']);        
    	$this->insert($formData);
    	$insertId = $this->lobjDbAdpt->lastInsertId('tbl_semestermaster','IdSemesterMaster');	
	    return $insertId;
	}
    
    public function fnupdateSemester($formData,$lintIdSemesterMaster) { //Function for updating the university
    	unset($formData['SemesterCode']);
    	unset($formData['IdSemester']);
    	unset($formData['StudentIntake']);
    	unset($formData['SemesterStartDate']);
    	unset($formData['SemesterEndDate']);
    	unset($formData['Program']);
    	unset($formData['SemesterStatus']);
    	unset($formData['Save']);
		$where = 'IdSemesterMaster = '.$lintIdSemesterMaster;
		$this->update($formData,$where);
    }
    
	public function fnSearchSemester($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
			   ->setIntegrityCheck(false)  	
			   ->join(array('a' => 'tbl_semestermaster'),array('IdSemesterMaster'))
			   ->where('a.SemesterMainName  like "%" ? "%"',$post['field3'])
			   ->where($field7);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
	
	/**
	 * 
	 * Method to get all list of semester main to be diaplyed in a dropdown
	 */
	public function fnGetSemestermasterListLong(){
		
		$session = new Zend_Session_Namespace('sis');
		
		$lstrSelect = $this->lobjDbAdpt->select()
 				 ->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
 				 ->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
				->order("a.SemesterCountType DESC")
				->order("a.SemesterFunctionType DESC");
		
		if ($session->IdRole == 3) {
			//get programcode from course_program
			$id=$session->IdStaff;
			$select= $this->lobjDbAdpt->select()
			->distinct()
			->from(array('cgt'=>'tbl_course_tagging_group'),array('IdSemester'))
			->join(array('srs'=>'tbl_studentregsubjects'),'cgt.IdCourseTaggingGroup=srs.IdCourseTaggingGroup',array())
			->join(array('pr'=>'course_group_program'),'pr.group_id=cgt.IdCourseTaggingGroup',array())
			->where('cgt.IdLecturer=?',$id)
			->orwhere('cgt.VerifyBy=?',$id);
			
			$lstrSelect->where('a.IdSemesterMaster in ?',$select);
		} else {
			$id=$session->IdStaff;
			$select= $this->lobjDbAdpt->select()
			->distinct()
			->from(array('cgt'=>'tbl_course_tagging_group'),array('IdSemester'))
			->join(array('srs'=>'tbl_studentregsubjects'),'cgt.IdCourseTaggingGroup=srs.IdCourseTaggingGroup',array())
			->join(array('pr'=>'course_group_program'),'pr.group_id=cgt.IdCourseTaggingGroup',array())
			->join(array('p'=>'tbl_program'),'pr.program_id=p.IdProgram',array())
			->group('cgt.IdSemester');
			
			if($session->IdRole == 605 || $session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("p.IdCollege='".$session->idCollege."'");
			}
			if($session->IdRole == 470 || $session->IdRole == 480){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("p.IdProgram='".$session->idDepartment."'");
			}
			
			$lstrSelect->where('a.IdSemesterMaster in ?',$select);
		}
		//echo $lstrSelect;exit;
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		
		return $larrResult;
	}
	public function fnGetExamSemestermasterList(){
	
		$session = new Zend_Session_Namespace('sis');
	
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
		->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
		->order("a.SemesterCountType DESC")
		->order("a.SemesterFunctionType DESC");
	
		if ($session->IdRole == 3) {
			//get programcode from course_program
			$id=$session->IdStaff;
			$select= $this->lobjDbAdpt->select()
			->distinct()
			->from(array('cgt'=>'tbl_course_tagging_group'),array('IdSemester'))
			->join(array('pr'=>'course_group_program'),'pr.group_id=cgt.IdCourseTaggingGroup',array())
			->where('cgt.IdLecturer=?',$id)
			->orwhere('cgt.VerifyBy=?',$id);
				
			$lstrSelect->where('a.IdSemesterMaster in ?',$select);
		} else {
			$id=$session->IdStaff;
			$select= $this->lobjDbAdpt->select()
			->distinct()
			->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
			->join(array('cgt'=>'tbl_course_tagging_group'),'a.IdSemesterMaster=cgt.IdSemester',array())
			->join(array('pr'=>'course_group_program'),'pr.group_id=cgt.IdCourseTaggingGroup',array())
			->join(array('p'=>'tbl_program'),'pr.program_id=p.IdProgram',array())
			->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
			->order("a.SemesterCountType DESC")
			->order("a.SemesterFunctionType DESC");
				
			if($session->IdRole == 605 || $session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("p.IdCollege='".$session->idCollege."'");
			}
			if($session->IdRole == 470 || $session->IdRole == 480){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("p.IdProgram='".$session->idDepartment."'");
			}
				
			//$lstrSelect->where('a.IdSemesterMaster in ?',$select);
		}
		//echo $lstrSelect;exit;
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
	
		return $larrResult;
	}
	public function fnGetAllSemestermasterList($periodetype=null){
	
		$session = new Zend_Session_Namespace('sis');
	
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
		->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
		->order("a.SemesterCountType DESC")
		->order("a.SemesterFunctionType DESC");
		
		if ($periodetype!=null) $lstrSelect->where('a.SemesterPeriodType==?',$periodetype);
		
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		//echo var_dump($larrResult);exit;
		return $larrResult;
	}
	
	public function getSemesterPDPT($id=0){
		$id = (int)$id;
	
		 
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
			->from($this->_name)
			->where('IdSemesterMaster = ?', $id)
			->where('SemesterFuctionType in ("0","1","3","4","6")');
			
				
			$row = $db->fetchRow($select);
		 
	
		if(!$row){
			throw new Exception("There is No Data");
		}
	
		return $row;
	}
	public function getData($id=0){
		$id = (int)$id;
		
		if($id!=0){
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from($this->_name)
					->where('IdSemesterMaster = ?', $id);
					
				$row = $db->fetchRow($select);
		}else{
			
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from($this->_name)
					->order('SemesterMainStartDate DESC');
								
			$row = $db->fetchAll($select);
		}
		
		if(!$row){
			throw new Exception("There is No Data");
		}
		
		return $row;
	}
	
	
	/* List Countable Semester */
	public function getCountableSemester($periodetype=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					->from(array('a'=>'tbl_semestermaster'),array("key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName"))
						 ->where('IsCountable=1')
					->order('SemesterMainStartDate DESC');
					
		//echo $select;
		if ($periodetype!=null) $select->where('a.SemesterPeriodType==?',$periodetype);
		
		$row = $db->fetchAll($select);
		
		return $row;
	}
	
	/* List Countable Semester */
	public function getSemesterTransfer(){
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db->select()
		->from(array('a'=>'tbl_semestermaster'),array("key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName"))
		->where('SemesterMainName like "%K%"')
		->ORwhere('SemesterMainName like "%T%"')
		->order('SemesterMainStartDate DESC');
			
		//echo $select;exit;
		$row = $db->fetchAll($select);
	
		return $row;
	}
	
	public function getUnCountableSemester($periodetype=null){
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db->select()
		->from(array('a'=>'tbl_semestermaster'),array("key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName"))
		->where('IsCountable=0')
		 ->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
				->order("a.SemesterCountType DESC")
				->order("a.SemesterFunctionType DESC");
		//echo $select;
		if ($periodetype!=null) $select->where('a.SemesterPeriodType==?',$periodetype);
		
		$row = $db->fetchAll($select);
	
		return $row;
	}
	/* Regitration Semester */
	public function getSemesterCourseRegistration($id_semester){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					     ->from(array('sm'=>'tbl_semestermaster'))						
						 ->join(array('ac'=>'tbl_activity_calender'),'ac.IdSemesterMain = sm.IdSemesterMaster')
						// ->where('CURDATE()	BETWEEN ac.StartDate AND ac.EndDate')
						 ->where('idActivity=18')	
						 ->where('IdSemesterMaster = ?',$id_semester);
		
		$row = $db->fetchRow($select);
		
		return $row;
	}
	
	
	public function getSemesterList($academicYearId=null){
		
		$lstrSelect = $this->lobjDbAdpt->select()
					->from(array("a"=>"tbl_semestermaster"))
					->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
					->order("a.SemesterCountType DESC")
					->order("a.SemesterFunctionType DESC");
	
		if($academicYearId!=null){
			$lstrSelect->where('a.idacadyear=?',$academicYearId);
		}
		
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
	
		return $larrResult;
	}
	
	public function getSemesterConversionList($academicYearId=null){
	
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"))
		->where('a.SemesterFunctionType="2"')
		->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
		->order("a.SemesterCountType DESC")
		->order("a.SemesterFunctionType DESC");
	
		if($academicYearId!=null){
			$lstrSelect->where('a.idacadyear=?',$academicYearId);
		}
	
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
	
		return $larrResult;
	}
	public function getSemesterPregraduation(){
	
		$session = new Zend_Session_Namespace('sis');
		$select = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"),array('key'=>'IdSemesterMaster','value'=>'SemesterMainName','SemesterMainCode'))
		->join(array("skr"=>'graduation_skr'),'skr.IdSemesterMain=a.IdSemesterMaster',array())
		->join(array('pre'=>'pregraduate_list'),'pre.dean_approval_skr=skr.id',array())
		->join(array('sr'=>'tbl_studentregistration'),'sr.IdStudentRegistration=pre.IdStudentRegistration',array())
		->join(array('pr'=>'tbl_program'),'pr.IdProgram=sr.IdProgram',array())
		->group('a.IdSemesterMaster')
		->order("SUBSTRING(a.SemesterMainCode,1,4) DESC");
		
		if($session->IdRole == 605 || $session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
			$select->where("pr.IdCollege='".$session->idCollege."'");
		}
		if($session->IdRole == 470 || $session->IdRole == 480){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
			$select->where("pr.IdProgram='".$session->idDepartment."'");
		}
	
		
	
		$larrResult = $this->lobjDbAdpt->fetchAll($select);
	
		return $larrResult;
	}
	
	public function getSemesterGraduation(){
	
		$session = new Zend_Session_Namespace('sis');
		$select = $this->lobjDbAdpt->select()
		->from(array("a"=>"tbl_semestermaster"),array('key'=>'IdSemesterMaster','value'=>'SemesterMainName','SemesterMainCode'))
		->join(array("skr"=>'graduation_skr'),'skr.IdSemesterMain=a.IdSemesterMaster',array())
		->join(array('pre'=>'graduate'),'pre.rector_approval_skr=skr.id',array())
		->join(array('sr'=>'tbl_studentregistration'),'sr.IdStudentRegistration=pre.IdStudentRegistration',array())
		->join(array('pr'=>'tbl_program'),'pr.IdProgram=sr.IdProgram',array())
		->group('a.IdSemesterMaster')
		->order("SUBSTRING(a.SemesterMainCode,1,4) DESC");
	
		if($session->IdRole == 605 || $session->IdRole == 311 || $session->IdRole == 298){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
			$select->where("pr.IdCollege='".$session->idCollege."'");
		}
		if($session->IdRole == 470 || $session->IdRole == 480){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
			$select->where("pr.IdProgram='".$session->idDepartment."'");
		}
	
	
	
		$larrResult = $this->lobjDbAdpt->fetchAll($select);
	
		return $larrResult;
	}
	
    public function getCurrentSemester(){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
					     ->from(array('sm'=>'tbl_semestermaster'))						
						 ->where('CURDATE() between sm.SemesterMainStartDate AND sm.SemesterMainEndDate')
                         ->order('sm.SemesterMainEndDate DESC');
		
		$row = $db->fetchRow($select);
		
		return $row;
	}	
	
	public function getSemesterByDate($date){
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db->select()
		->from(array('sm'=>'tbl_semestermaster'))
		->where("'".date('Y-m-d',strtotime($date))."'> sm.SemesterMainStartDate AND '".date('Y-m-d',strtotime($date))."' < sm.SemesterMainEndDate")
		->order('sm.SemesterMainStartDate');
		//echo $select;exit;
		$row = $db->fetchRow($select);
		//echo var_dump($row);exit;
		return $row;
	}
	
	public function fnGetSemestermasterListNoCheck($periodetype=null){
		
		$session = new Zend_Session_Namespace('sis');
		
		$lstrSelect = $this->lobjDbAdpt->select()
 				 ->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
 				 ->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
				->order("a.SemesterCountType DESC")
				->order("a.SemesterFunctionType DESC");
		
		if ($periodetype!=null) $lstrSelect->where('s.SemesterPeriodType==?',$periodetype); 
		//echo $lstrSelect;exit;
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		
		return $larrResult;
	}
	public function fnGetSemestermasterParentList(){
		
		$lstrSelect = $this->lobjDbAdpt->select()
		->distinct()
		->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
		->join(array("sr"=>'tbl_studentregsubjects'),'a.IdSemesterMaster=sr.IdSemesterMain', array())
		->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
		->order("a.SemesterCountType DESC")
		->order("a.SemesterFunctionType DESC");
	
	
		//echo $lstrSelect;exit;
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
	
		return $larrResult;
	}
	
	public function fnGetSemestermasterList($periodetype=null){ //problem with slow query in fnGetSemestermasterListLong
		
		$session = new Zend_Session_Namespace('sis');
		
		$lstrSelect = $this->lobjDbAdpt->select()
 				 ->from(array("a"=>"tbl_semestermaster"),array("a.IdSemesterMaster","key"=>"a.IdSemesterMaster","value"=>"a.SemesterMainName","name"=>"a.SemesterMainName","SemesterMainName"))
 				 ->order("SUBSTRING(a.SemesterMainCode,1,4) DESC")
				->order("a.SemesterCountType DESC")
				->order("a.SemesterFunctionType DESC");
		if ($periodetype!=null) $lstrSelect->where('a.SemesterPeriodType==?',$periodetype); 
		
		//echo $lstrSelect;exit;
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		
		return $larrResult;
	}
}
?>