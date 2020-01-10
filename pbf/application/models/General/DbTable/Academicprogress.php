<?php
class App_Model_General_DbTable_Academicprogress extends Zend_Db_Table { //Model Class for Academicprogress Details
	protected $_name = 'tbl_academic_progress';
	
	
      /**
       * Function to FETCh student registered Details
       * @author vipul
       */	
	
	public function checkcompleted($idstud,$idsubject){
		$table = "tbl_studentregsubjects";
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from(array("a"=>$table))
				->where("a.exam_status = 'C'") //kena check balik apa yati letak utk complete status
				->where("a.grade_status = 'Pass'") //kena check balik apa yati letak utk status pass
				->where('a.IdStudentRegistration = ?',$idstud)
				->where('IdSubject = ?',$idsubject);
		$result = $db->fetchRow($select); 

		/*if(is_array($result)){
			return $result["grade_name"];
		}else{
			return false;
		}*/
		
		if($result){
			return $result['grade_name'];
		}else{
			return false;
		}
		
		
		
	}
	public function checkcompletedHighest($idstud,$idsubject){
		$table = "tbl_studentregsubjects";
		
		$db = new Examination_Model_DbTable_StudentRegistrationSubject();
		$result = $db->getHighestMarkofAllSemester($idstud, $idsubject);
		if($result ){
			//if ($result['exam_status']!='C' && $result['grade_status']!='Pass') {
				return $result['grade_name'];
			//}
			//else return false;
		}else{
			return false;
		}
	}
	
	public function checkregistered($idstud,$idsubject){
		$table = "tbl_studentregsubjects";
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from(array("a"=>$table))
				->where("a.Active = 1")
				->where('a.IdStudentRegistration = ?',$idstud)
				->where('IdSubject = ?',$idsubject);
		$result = $db->fetchRow($select); 

		if(is_array($result)){
			return true;
		}else{
			return false;
		}
		
	}	
	
	public function checkallowed($idstud,$idsubject){
		$table = "tbl_allowsubjectreg";
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
				->from(array("a"=>$table))
				->where('a.IdStudentRegistration = ?',$idstud)
				->where('IdSubject = ?',$idsubject);
		$result = $db->fetchRow($select); 
		//echo $select;
		if(is_array($result)){
			return true;
		}else{
			return false;
		}
		
	}		
	public function fetchStudentRegDetails($idstudent) {            
            $db = Zend_Db_Table::getDefaultAdapter();
	    $sql = $db->select()
                        ->from(array('a' => 'tbl_studentregistration'),array('a.IdStudentRegistration','a.registrationId','a.IdLandscape'
                                                                            ,'a.IdProgram','a.IdBranch','a.IdIntake','a.profileStatus','a.UpdUser',"CONCAT_WS(' ',IFNULL(a.FName,''),IFNULL(a.MName,''),IFNULL(a.LName,'')) AS name"))
                        ->joinLeft(array('f'=>'tbl_landscape'),'f.IdLandscape = a.IdLandscape',array('f.LandscapeType'))
                        ->joinLeft(array('c'=>'tbl_program'),'a.IdProgram = c.IdProgram',array('c.IdScheme')) 
                        ->where('a.IdStudentRegistration = ?',$idstudent);
            $result = $db->fetchRow($sql);
            return $result;            
        }
       
       /**
       * Function to INSERT STUDENT DETAILS INTO ACADEMIC PROGRESS TABLE
       * @author vipul
       */
        public function insertAcademicProgress($studentdetails) {  
            $db = Zend_Db_Table::getDefaultAdapter();
            
            if($studentdetails['LandscapeType']=='42') { $landscapeType = '2';  }
            if($studentdetails['LandscapeType']=='43') { $landscapeType = '3';  }
            if($studentdetails['LandscapeType']=='44') { $landscapeType = '1';  }            
            
	    $formData = array(
                                'IdStudent' => $studentdetails['IdStudentRegistration'],
                                'IdScheme'  => $studentdetails['IdScheme'],
                                'IdFaculty' => '',
                                'IdProgram' => $studentdetails['IdProgram'],
                                'IdIntake' => $studentdetails['IdIntake'],
                                'ProfileStatus' => $studentdetails['profileStatus'],
                                'LandscapeType' => $landscapeType,
                                'UpdUser' => $studentdetails['UpdUser'],
                                'UpdDate' => date('Y-m-d H:i:s'),
                            ); 
            $this->insert($formData);
            $getlID = $db->lastInsertId();
            return $getlID;
       }
       
       
       /**
       * Function to FETCH Courses and INSERT INTO tbl_academicprogress_subjects table
       * @author vipul
       */
       public function fetchCoursesInsert($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails) {
            $db = Zend_Db_Table::getDefaultAdapter();
            $Year_Level_Block = '';
	    // get the courses
            $sql = $db->select()
                        ->from(array('a' => 'tbl_landscapesubject'),array('a.IdSubject','a.SubjectType','a.IdSemester','a.CreditHours','a.UpdUser'))
                        ->joinLeft(array('f'=>'tbl_subjectmaster'),' f.IdSubject = a.IdSubject ',array('f.courseDescription'))                        
                        ->where('a.IdLandscape = ?',$landscapeID)
                        ->where('a.IdProgram = ?',$programID);
            $result = $db->fetchAll($sql);
            
            // INSERT the courses
            $tableNAme = 'tbl_academicprogress_subjects';
           
            foreach($result as $values) {
                
                // get the year id for idsemester for a landscape if the landscpae is block based.
                if($studentdetails['LandscapeType']=='44') { 
                $sqlBY = $db->select()
                        ->from(array('by' => 'tbl_blocksemesteryear'),array('by.Year'))                       
                        ->where('by.IdLandscape = ?',$landscapeID)
                        ->where("by.YearSemester = ?",$values['IdSemester']);
                $resultBy = $db->fetchRow($sqlBY);                
                if(count($resultBy)>0) {   $Year_Level_Block = $resultBy['Year']; }
                }
                
                // get the level id for idsemester for a landscape if the landscpae is level based.
                if($studentdetails['LandscapeType']=='42') { 
                $sqlBL = $db->select()
                        ->from(array('bl' => 'tbl_landscapeblocksemester'),array('bl.blockid'))                       
                        ->where('bl.IdLandscape = ?',$landscapeID)
                        ->where("bl.semesterid = ?",$values['IdSemester']);
                $resultBL = $db->fetchRow($sqlBL);
                if(count($resultBL)>0) {   $Year_Level_Block = $resultBL['blockid']; }
                }
                
                if($studentdetails['LandscapeType']=='43') {
                     $Year_Level_Block = '';
                }
                
                $paramData = array(
                     'IdStudent' => $lintidreg,
                     'IdAcademicProgress' => $academicprogressID,
                     'Year_Level_Block'  => $Year_Level_Block,
                     'Semester' => $values['IdSemester'],
                     'IdCourseType' => $values['SubjectType'],
                     'CourseID' => $values['IdSubject'],
                     'CourseDescription' => $values['courseDescription'],
                     'CreditHours' => $values['CreditHours'],
                     'Grade' => '',
                     'IsRegistered' => '0',
                     'UpdUser' => $values['UpdUser'],
                     'UpdDate' => date('Y-m-d H:i:s'),
                );
                $db->insert($tableNAme,$paramData);                
            }
           
       }
       
       
       /**
        * FUNCTION TO INSERT SUBJECTS TO ACADEMIC LANDSCPAE PROGRESS        
        */
       
       public function academicprogressCoursesInsert($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId) {
            $db = Zend_Db_Table::getDefaultAdapter();
            //$tableNAme = 'tbl_academicprogress_subjects';
            //$Year_Level_Block = '';             
            
            // check all DUMMY SEMESTERS and their subjects for the student. This will be listed in registered courses listing.
            $this->fninsertDummySubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId);            
          
            // COUNT the latest sem for the STUDENT
            $sqlTotalSem = $db->select()
                         ->from(array('totstudsem' => 'tbl_studentsemesterstatus'),array('totstudsem.*')) 
                         ->where('totstudsem.IdStudentRegistration = ?',$lintidreg); 
            $resultTotalSem = $db->fetchAll($sqlTotalSem);
            $finalTotal  = count($resultTotalSem);
            //die;
            
            //CHECK FOR ALL "CT" SUBJECTS and are not "DUMMY", will be listed in registered course listing.
            $this->fninsertCTSubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId); 
           
           
            
            // from semester status, order by date asc, get the semester list, the first semId will be treated as sem1, next id will be sem2, ...
            $this->fninsertSemesterSubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId); 
            
            
            
            
           
            // CHECK FOR THE LATEST SEM and check from the landscape, the semester.
            $this->fninsertLandscapeSubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId,$finalTotal);      
            //$currentDate = date('Y-m-d');            
            
            
            
           
           
       } 
       
       
       /**
       * Function to FETCH REGISTERED Courses of a student and update the  IsRegistered to '1'.
       * @author vipul
       */       
       public function fetchCoursesRegister($lintidreg) {
             $db = Zend_Db_Table::getDefaultAdapter();
	     // get the courses
             $sql = $db->select()
                        ->from(array('a' => 'tbl_studentregsubjects'),array('a.IdSubject'))                        
                        ->where('a.IdStudentRegistration = ?',$lintidreg);
             $result = $db->fetchAll($sql);            
             
             // UPDATE the tablw with set isRegistered to 1 
             $tableNAme = 'tbl_academicprogress_subjects';
             $lAppStudArr = array( 'IsRegistered'=> '1' );
             foreach($result as $values) {                            
			   $where = "IdStudent = '".$lintidreg."' AND  CourseID = '".$values['IdSubject']."' ";
			   $db->update($tableNAme,$lAppStudArr,$where);             
             }
            
        }
        
        /**
         * Function to delete all academic progress records based on studentID
         * @Author Vipul
         */        
         public function deleteRecords($lintidreg) {
            $db = Zend_Db_Table::getDefaultAdapter();
            $where_delete_records =  " IdStudent = '".$lintidreg."' " ;
            $db->delete('tbl_academicprogress_subjects', $where_delete_records);                    
            $db->delete('tbl_academic_progress', $where_delete_records);          
            
         }
        
       
       /**
       * Function to FETCH registered academic progress
       * @author vipul
       */       
       public function fetchRegisteredAcademicProgress($lintidreg) {
             $db = Zend_Db_Table::getDefaultAdapter();
	     // get the courses
             $sql = $db->select()
                        ->from(array('a' => 'tbl_academicprogress_subjects'),array('a.*'))
                        ->joinLeft(array('b' => 'tbl_definationms'),'a.IdCourseType =b.idDefinition',array('b.DefinitionDesc as SubjectType'))
                        ->joinLeft(array('d'=>'tbl_subjectmaster'),'a.CourseID = d.IdSubject',array('d.SubjectName','d.SubCode'))
                        ->where('a.IdStudent = ?',$lintidreg)
                        ->where('a.IsRegistered = ?','1')
                        ->order('a.Semester');
             $result = $db->fetchAll($sql);
             return $result;
        }
        
      /**
       * Function to FETCH unregistered academic progress
       * @author vipul
       */       
       public function fetchUnRegisteredAcademicProgress($lintidreg) {
             $db = Zend_Db_Table::getDefaultAdapter();
	     // get the courses
             $sql = $db->select()
                        ->from(array('a' => 'tbl_academicprogress_subjects'),array('a.*'))
                        ->joinLeft(array('b' => 'tbl_definationms'),'a.IdCourseType =b.idDefinition',array('b.DefinitionDesc as SubjectType'))
                        ->joinLeft(array('d'=>'tbl_subjectmaster'),'a.CourseID = d.IdSubject',array('d.SubjectName','d.SubCode'))
                        ->where('a.IdStudent = ?',$lintidreg)
                        ->where('a.IsRegistered = ?','0')
                        ->order('a.Semester');
             $result = $db->fetchAll($sql);
             return $result;
       } 
       
       
       /**
       * Function to FETCH dummy academic progress
       * @author vipul
       */       
       public function fetchDummyRegisteredAcademicProgress($lintidreg) {
             $db = Zend_Db_Table::getDefaultAdapter();
	     // get the courses
             $sql = $db->select()
                        ->from(array('a' => 'tbl_academicprogress_subjects'),array('a.*'))
                        ->joinLeft(array('b' => 'tbl_definationms'),'a.IdCourseType =b.idDefinition',array('b.DefinitionDesc as SubjectType'))
                        ->joinLeft(array('d'=>'tbl_subjectmaster'),'a.CourseID = d.IdSubject',array('d.SubjectName','d.SubCode'))
                        ->where('a.IdStudent = ?',$lintidreg)
                        ->where('a.IsRegistered = ?','2')
                        ->order('a.IdAcademicProgressSub DESC');
             $result = $db->fetchAll($sql);
             return $result;
       } 
       
       
       /**
	 *Function to get student details based on studentID
	 *@author Vipul
	 */
	public function fetchStudentDetails($lintidreg) {
	     	$db = Zend_Db_Table::getDefaultAdapter();
                $sql = $db->select()
                                ->from(array('a' => 'tbl_studentregistration'),array('a.FName','a.MName','a.LName','a.registrationId'))
                                ->joinLeft(array('b'=>'tbl_branchofficevenue'),'a.IdBranch  = b.IdBranch',array('b.BranchName'))
                                ->joinLeft(array('c'=>'tbl_program'),'a.IdProgram = c.IdProgram',array('c.ProgramName','c.IdScheme'))
                                ->joinLeft(array('d'=>'tbl_intake'),'a.IdIntake = d.IdIntake',array('d.IntakeId'))
                                ->joinLeft(array('e'=>'tbl_scheme'),'e.IdScheme = c.IdScheme',array('e.EnglishDescription'))
                                ->joinLeft(array('cm'=>'tbl_collegemaster'),'c.IdCollege = cm.IdCollege',array('cm.CollegeName'))
                                ->joinLeft(array('deftn'=>'tbl_definationms'),'deftn.idDefinition=a.profileStatus',array('deftn.DefinitionCode'))
                                ->where('a.IdStudentRegistration = ?',$lintidreg);
                $result = $db->fetchRow($sql);
                return $result;
        }
        
        
        /**
	 *Function to get get TotalHours based on landscapeID
	 *@author Vipul
	 */
	public function fetchTotalHours($landscapeID) {
	     	$db = Zend_Db_Table::getDefaultAdapter();
                $sql = $db->select()
                                ->from(array('a' => 'tbl_landscape'),array('a.TotalCreditHours'))                                
                                ->where('a.IdLandscape = ?',$landscapeID);
                $result = $db->fetchRow($sql);
                return $result;
        }
        
        
        
        /**
	 *Function to get get TotalHours Credit
	 *@author Vipul
	 */
	public function fetchTotalCreditHours($studentID,$type1,$type2) {
	     	$db = Zend_Db_Table::getDefaultAdapter();
                $sql1 = $db->select()
                                ->from(array('a' => 'tbl_academicprogress_subjects'),array('SUM(a.CreditHours) as totHrs1'))                                
                                ->where('a.IdStudent = ?',$studentID)
                                ->where('a.Grade = ?',$type1);
                //echo $sql1;
                $result1 = $db->fetchRow($sql1);
                //asd($result1);
                $sql2 = $db->select()
                                ->from(array('a' => 'tbl_academicprogress_subjects'),array('SUM(a.CreditHours) as totHrs2'))                                
                                ->where('a.IdStudent = ?',$studentID)
                                ->where('a.Grade = ?',$type2);
                $result2 = $db->fetchRow($sql2);
                
                $result = $result1['totHrs1']+$result2['totHrs2'];
                return $result;
        }
        
        
        
        /**
	 *Function to get get TotalCredit based on landscapeID
	 *@author Vipul
	 */
	public function fetchTotalCredit($lintidreg,$isreg) {
	     	$db = Zend_Db_Table::getDefaultAdapter();
                $sql = $db->select()
                                ->from(array('a' => 'tbl_academicprogress_subjects'),array('SUM(a.CreditHours) as totalcredit'))                                
                                ->where('a.IdStudent = ?',$lintidreg)
                                ->where('a.IsRegistered = ?',$isreg);
                $result = $db->fetchRow($sql);
                return $result;
        }
        
        /**
	 *Function to get get level landscape academic progress 
	 *@author Vipul
	 */
        public function fngetlandscapelevel($lintidreg,$landscapeID,$isreg) {
                $db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
                                ->from(array('a' => 'tbl_academicprogress_subjects'),array('a.*'))
                                ->joinLeft(array('b' => 'tbl_landscapeblock'), " b.IdLandscape = '".$landscapeID."' AND a.Year_Level_Block = b.block ",array('b.block','b.blockname'))
                                ->joinLeft(array('d'=>'tbl_subjectmaster'),'a.CourseID = d.IdSubject',array('d.SubjectName','d.SubCode'))
                                ->joinLeft(array('e' => 'tbl_definationms'),'a.IdCourseType =e.idDefinition',array('e.DefinitionDesc as SubjectType'))
                                ->where("a.IdStudent = ?",$lintidreg)
                                ->where('a.IsRegistered = ?',$isreg)
                                ->order('a.Year_Level_Block');
		$larrResult = $db->fetchAll($select);
		return $larrResult;
	}
        
        
        /**
	 *Function to get get block landscape academic progress 
	 *@author Vipul
	 */
        public function fngetlandscapeblock($lintidreg,$landscapeID,$isreg) {
                $db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
                                ->from(array('a' => 'tbl_academicprogress_subjects'),array('a.*'))
                                //->joinLeft(array('b' => 'tbl_blocksemesteryear'), " b.IdLandscape = '".$landscapeID."' AND a.Year_Level_Block = b.Year ",array('b.Year','b.YearSemester'))
                                ->joinLeft(array('d'=>'tbl_subjectmaster'),'a.CourseID = d.IdSubject',array('d.SubjectName','d.SubCode'))
                                ->joinLeft(array('e' => 'tbl_definationms'),'a.IdCourseType =e.idDefinition',array('e.DefinitionDesc as SubjectType'))
                                ->where("a.IdStudent = ?",$lintidreg)
                                ->where('a.IsRegistered = ?',$isreg)                                
                                ->order(array('a.Year_Level_Block','a.Semester'));
                //echo $select;
		$larrResult = $db->fetchAll($select);
		return $larrResult;
	}
        
        
        private function flatten_array($mArray) {
            $sArray = array();

            foreach ($mArray as $row) {
                if ( !(is_array($row)) ) {
                    if($sArray[] = $row){
                    }
                } else {
                    $sArray = array_merge($sArray,self::flatten_array($row));
                }
            }
            return $sArray;
}
        
       
         private function fninsertDummySubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId) {
             $db = Zend_Db_Table::getDefaultAdapter();
             $tableNAme = 'tbl_academicprogress_subjects';
             $Year_Level_Block = ''; 
             $sqlDUMMY = $db->select()
                        ->from(array('regsub' => 'tbl_studentregsubjects'),array('regsub.IdSubject','regsub.IdGrade','regsub.IdSemesterMain'))
                        ->joinLeft(array('semast'=>'tbl_semestermaster'),' semast.IdSemesterMaster = regsub.IdSemesterMain ',
                                                                           array(''))
                        ->join(array('fsm'=>'tbl_landscapesubject'),' fsm.IdSubject = regsub.IdSubject AND fsm.IdLandscape =  '.$landscapeID ,
                                                                           array('fsm.SubjectType'))
                        ->joinLeft(array('f'=>'tbl_subjectmaster'),' f.IdSubject = regsub.IdSubject ',array('f.courseDescription',
                                                                                                       'f.CreditHours'))                             
                        ->where('regsub.IdStudentRegistration = ?',$lintidreg)
                        ->where('semast.DummyStatus = ?','1')
                        ->group("regsub.IdSubject");
            //echo $sqlDUMMY; 
            $resultDUMMY = $db->fetchAll($sqlDUMMY); 
            //asd($resultDUMMY);
            if(count($resultDUMMY)>0) { 
            foreach ($resultDUMMY as $valuesDummy)
                {                
                    
                    // CHECK FOR course and subject type in academic subject table. no duplicacy should be there in APS.
                    $sqlchkDup = $db->select()
                                    ->from(array('aps' => 'tbl_academicprogress_subjects'),array('aps.IdCourseType','aps.CourseID'))                                                    
                                    ->where('aps.IdCourseType = ?',$valuesDummy['SubjectType'])
                                    ->where('aps.CourseID = ?',$valuesDummy['IdSubject'])
                                    ->where('aps.IdStudent = ?',$lintidreg);
                    //echo $sqlchkDup;
                    $resultchkdup = $db->fetchAll($sqlchkDup); 
                    //asd($resultchkdup);
                    if(count($resultchkdup)==0) { 
                    $paramDataDummy = array(
                                        'IdStudent' => $lintidreg,
                                        'IdAcademicProgress' => $academicprogressID,
                                        'Year_Level_Block'  => $Year_Level_Block,
                                        'Semester' => NULL,
                                        'IdCourseType' => $valuesDummy['SubjectType'],
                                        'CourseID' => $valuesDummy['IdSubject'],
                                        'CourseDescription' => $valuesDummy['courseDescription'],
                                        'CreditHours' => $valuesDummy['CreditHours'],
                                        'Grade' => $valuesDummy['IdGrade'],
                                        'IsRegistered' => '2',  // 2 is for dummy data
                                        'UpdUser' => $userId,
                                        'UpdDate' => date('Y-m-d H:i:s'),
                                        );
                    $db->insert($tableNAme,$paramDataDummy);
                    }
                }
            }
            //return 1;
         }
         
        private function fninsertCTSubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId){
            $db = Zend_Db_Table::getDefaultAdapter();
            $tableNAme = 'tbl_academicprogress_subjects';
            $Year_Level_Block = ''; 
            $sqlCT = $db->select()
                        ->from(array('regsub' => 'tbl_studentregsubjects'),array('regsub.IdSubject','regsub.IdGrade','regsub.IdSemesterMain','regsub.IdSemesterDetails'))
                        ->join(array('fsm'=>'tbl_landscapesubject'),' fsm.IdSubject = regsub.IdSubject AND fsm.IdLandscape =  '.$landscapeID ,
                                                                           array('fsm.SubjectType'))
                        ->joinLeft(array('f'=>'tbl_subjectmaster'),' f.IdSubject = regsub.IdSubject ',array('f.courseDescription',
                                                                                                       'f.CreditHours'))                             
                        ->where('regsub.IdStudentRegistration = ?',$lintidreg)
                        ->where('regsub.IdGrade = ?','CT')
                        ->group("regsub.IdSubject");
            //echo $sqlCT; die;
            $resultCT = $db->fetchAll($sqlCT);            
            if(count($resultCT)>0) { 
            foreach ($resultCT as $valuesCt)
                {
                        
                        if($studentdetails['LandscapeType']=='43') {
                             $Year_Level_Block = '';
                        } else if ($studentdetails['LandscapeType']=='44') { 
                            $Year_Level_Block = 1;
                        } else if($studentdetails['LandscapeType']=='42') {
                             $Year_Level_Block = 1;
                        }
                        
                        $idSemDetail = $valuesCt['IdSemesterDetails'];
                        $idSemMain   = $valuesCt['IdSemesterMain'];
                        
                        // CHECK FOR course and subject type in academic subject table. no duplicacy should be there in APS.
                        $sqlchkDup = $db->select()
                                        ->from(array('aps' => 'tbl_academicprogress_subjects'),array('aps.IdCourseType','aps.CourseID'))                                                    
                                        ->where('aps.IdCourseType = ?',$valuesCt['SubjectType'])
                                        ->where('aps.CourseID = ?',$valuesCt['IdSubject'])
                                        ->where('aps.IdStudent = ?',$lintidreg);
                        $resultchkdup = $db->fetchAll($sqlchkDup); 
                        
                        if($idSemMain!='' && $idSemDetail=='') { 
                            
                             // check all DUMMY SEMESTERS
                            $sqlchkDUMMY = $db->select()
                                                    ->from(array('semast' => 'tbl_semestermaster'),array('semast.*'))                                                    
                                                    ->where('semast.DummyStatus = ?','1')
                                                    ->where('semast.IdSemesterMaster = ?',$idSemMain);
                            $resultchkdummy = $db->fetchAll($sqlchkDUMMY);                            
                            if(count($resultchkdummy)==0 && count($resultchkdup)==0) { 
                                $paramDataDummy = array(
                                        'IdStudent' => $lintidreg,
                                        'IdAcademicProgress' => $academicprogressID,
                                        'Year_Level_Block'  => $Year_Level_Block,
                                        'Semester' => '1',
                                        'IdCourseType' => $valuesCt['SubjectType'],
                                        'CourseID' => $valuesCt['IdSubject'],
                                        'CourseDescription' => $valuesCt['courseDescription'],
                                        'CreditHours' => $valuesCt['CreditHours'],
                                        'Grade' => $valuesCt['IdGrade'],
                                        'IsRegistered' => '1',  // 1 is for registered
                                        'UpdUser' => $userId,
                                        'UpdDate' => date('Y-m-d H:i:s'),
                                        );
                                $db->insert($tableNAme,$paramDataDummy);
                            }
                            
                        }
                        else if ($idSemMain=='' && $idSemDetail!='') { 
                               if(count($resultchkdup)==0) {  
                               $paramDataDummy = array(
                                        'IdStudent' => $lintidreg,
                                        'IdAcademicProgress' => $academicprogressID,
                                        'Year_Level_Block'  => $Year_Level_Block,
                                        'Semester' => '1',
                                        'IdCourseType' => $valuesCt['SubjectType'],
                                        'CourseID' => $valuesCt['IdSubject'],
                                        'CourseDescription' => $valuesCt['courseDescription'],
                                        'CreditHours' => $valuesCt['CreditHours'],
                                        'Grade' => $valuesCt['IdGrade'],
                                        'IsRegistered' => '1',  // 1 is for registered
                                        'UpdUser' => $userId,
                                        'UpdDate' => date('Y-m-d H:i:s'),
                                        );
                               $db->insert($tableNAme,$paramDataDummy); 
                               } 
                        }
                }
            }
            
        }
        
        
        private function fninsertSemesterSubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId) {
            $db = Zend_Db_Table::getDefaultAdapter();
            $tableNAme = 'tbl_academicprogress_subjects';
            $Year_Level_Block = ''; 
            
            $sql = $db->select()
                        ->from(array('a' => 'tbl_studentsemesterstatus'),array('a.IdStudentRegistration','a.studentsemesterstatus',
                                                                               'a.idSemester','a.IdSemesterMain'))                                                
                        ->where('a.IdStudentRegistration = ?',$lintidreg)
                        ->order('a.idstudentsemsterstatus ASC');
            $result = $db->fetchAll($sql);
            $semInc = '1';
            if(count($result)>0) { 
            foreach ($result as $values)
            {                
                $idSemesterDetail = $values['idSemester'];
                $idSemesterMain   = $values['IdSemesterMain'];
                
                
                // get the year id for idsemester for a landscape if the landscpae is block based.
                if($studentdetails['LandscapeType']=='44') { 
                $sqlBY = $db->select()
                        ->from(array('by' => 'tbl_blocksemesteryear'),array('by.Year'))                       
                        ->where('by.IdLandscape = ?',$landscapeID)
                        ->where("by.YearSemester = ?",$semInc);
                $resultBy = $db->fetchRow($sqlBY);                
                if(count($resultBy)>0) {   $Year_Level_Block = $resultBy['Year']; }
                }
                
                // get the level id for idsemester for a landscape if the landscpae is level based.
                if($studentdetails['LandscapeType']=='42') { 
                $sqlBL = $db->select()
                        ->from(array('bl' => 'tbl_landscapeblocksemester'),array('bl.blockid'))                       
                        ->where('bl.IdLandscape = ?',$landscapeID)
                        ->where("bl.semesterid = ?",$semInc);
                $resultBL = $db->fetchRow($sqlBL);
                if(count($resultBL)>0) {   $Year_Level_Block = $resultBL['blockid']; }
                }
                
                if($studentdetails['LandscapeType']=='43') {
                     $Year_Level_Block = '';
                }
                
                
                
                
                if($idSemesterDetail!='' && $idSemesterMain=='')
                {
                    $sqlSD = $db->select()
                        ->from(array('regsub' => 'tbl_studentregsubjects'),array('regsub.IdSubject','regsub.IdGrade'))
                        ->joinLeft(array('f'=>'tbl_subjectmaster'),' f.IdSubject = regsub.IdSubject ',array('f.courseDescription',
                                                                                                       'f.CreditHours'))
                        ->joinLeft(array('fsm'=>'tbl_landscapesubject'),' fsm.IdSubject = regsub.IdSubject AND fsm.IdLandscape =  '.$landscapeID ,array('fsm.SubjectType'))      
                        ->where('regsub.IdStudentRegistration = ?',$lintidreg)
                        ->where("regsub.IdSemesterDetails = ?",$idSemesterDetail)
                        ->where('regsub.IdGrade IS NULL')
                        ->group("regsub.IdSubject");
                    $resultSD = $db->fetchAll($sqlSD);
                    if(count($resultSD)>0) { 
                    foreach($resultSD as $valueSD)
                    {
                        // CHECK FOR course and subject type in academic subject table. no duplicacy should be there in APS.
                        $sqlchkDup = $db->select()
                                        ->from(array('aps' => 'tbl_academicprogress_subjects'),array('aps.IdCourseType','aps.CourseID'))                                                    
                                        ->where('aps.IdCourseType = ?',$valueSD['SubjectType'])
                                        ->where('aps.CourseID = ?',$valueSD['IdSubject'])
                                        ->where('aps.IdStudent = ?',$lintidreg);
                        $resultchkdup = $db->fetchAll($sqlchkDup); 
                        if(count($resultchkdup)==0) {  
                        $paramDataSD = array(
                                            'IdStudent' => $lintidreg,
                                            'IdAcademicProgress' => $academicprogressID,
                                            'Year_Level_Block'  => $Year_Level_Block,
                                            'Semester' => $semInc,
                                            'IdCourseType' => $valueSD['SubjectType'],
                                            'CourseID' => $valueSD['IdSubject'],
                                            'CourseDescription' => $valueSD['courseDescription'],
                                            'CreditHours' => $valueSD['CreditHours'],
                                            'Grade' => $valueSD['IdGrade'],
                                            'IsRegistered' => '1',
                                            'UpdUser' => $userId,
                                            'UpdDate' => date('Y-m-d H:i:s'),
                                           );
                        $db->insert($tableNAme,$paramDataSD); 
                        }                         
                    } }                   
                }
                
                if($idSemesterDetail=='' && $idSemesterMain!='')
                {
                    $sqlSM = $db->select()
                        ->from(array('regsub' => 'tbl_studentregsubjects'),array('regsub.IdSubject','regsub.IdGrade'))
                        ->joinLeft(array('f'=>'tbl_subjectmaster'),' f.IdSubject = regsub.IdSubject ',array('f.courseDescription',
                                                                                                       'f.CreditHours','f.CourseType'))
                        ->join(array('fsm'=>'tbl_landscapesubject')," fsm.IdSubject = regsub.IdSubject AND fsm.IdLandscape =  $landscapeID ",array('fsm.SubjectType'))     
                        ->where('regsub.IdStudentRegistration = ?',$lintidreg)
                        ->where("regsub.IdSemesterMain = ?",$idSemesterMain)
                       // ->where("fsm.IdLandscape = ?",$landscapeID)
                            ->where('regsub.IdGrade IS NULL')
                        ->group("regsub.IdSubject");
                    //echo $sqlSM; die;
                    $resultSM = $db->fetchAll($sqlSM);
                    if(count($resultSM)>0) { 
                    foreach($resultSM as $valueSM) {
                            
                            // CHECK FOR course and subject type in academic subject table. no duplicacy should be there in APS.
                            $sqlchkDup = $db->select()
                                            ->from(array('aps' => 'tbl_academicprogress_subjects'),array('aps.IdCourseType','aps.CourseID'))                                                    
                                            ->where('aps.IdCourseType = ?',$valueSM['SubjectType'])
                                            ->where('aps.CourseID = ?',$valueSM['IdSubject'])
                                            ->where('aps.IdStudent = ?',$lintidreg);
                            $resultchkdup = $db->fetchAll($sqlchkDup);
                            if(count($resultchkdup)==0) { 
                            $paramDataSM = array(
                                                'IdStudent' => $lintidreg,
                                                'IdAcademicProgress' => $academicprogressID,
                                                'Year_Level_Block'  => $Year_Level_Block,
                                                'Semester' => $semInc,
                                                'IdCourseType' => $valueSM['SubjectType'],
                                                'CourseID' => $valueSM['IdSubject'],
                                                'CourseDescription' => $valueSM['courseDescription'],
                                                'CreditHours' => $valueSM['CreditHours'],
                                                'Grade' => $valueSM['IdGrade'],
                                                'IsRegistered' => '1',
                                                'UpdUser' => $userId,
                                                'UpdDate' => date('Y-m-d H:i:s'),
                                               );
                            $db->insert($tableNAme,$paramDataSM); 
                            }                        
                    } }                   
                }
                
                $semInc++;               
            } }            
            
            
        }
        
        
        private function fninsertLandscapeSubjects($lintidreg,$academicprogressID,$landscapeID,$programID,$studentdetails,$userId,$finalTotal){
            $db = Zend_Db_Table::getDefaultAdapter();
            $tableNAme = 'tbl_academicprogress_subjects';
            $Year_Level_Block = ''; 
            
            $sqlTotalSem2 = $db->select()
                         ->from(array('totstudsem' => 'tbl_studentsemesterstatus'),array('totstudsem.*')) 
                         ->where('totstudsem.IdStudentRegistration = ?',$lintidreg); 
            $resultTotalSem2 = $db->fetchAll($sqlTotalSem2);
            $currentSEM = 0; $loopTerms = array(); $c=1;
            if(count($resultTotalSem2)>0) { 
            foreach($resultTotalSem2 as $values) {
                array_push($loopTerms, $c++); 
            } }
            //asd($loopTerms,false);
            
            // check from the landscape, the semester.            
            $where_ls5 =  "  ( lsb.IdLandscape = '".$landscapeID."' )  ";    
            $sqlTotalSem5 = $db->select()
                               ->from(array('lsb' => 'tbl_landscapesubject'),array('lsb.IdSemester')) 
                               ->where($where_ls5)
                               ->group('lsb.IdSemester')
                               ->order('lsb.IdSemester');
            $resultTotalSem5 = $db->fetchAll($sqlTotalSem5); 
            //asd($resultTotalSem5);
            if(count($resultTotalSem5)>0) {
            $abcd = $this->flatten_array($resultTotalSem5);
            //asd($abcd);                        
            $semDiff = array_diff($abcd, $loopTerms);
            $finalsemDiff = array_merge($semDiff);
            //asd($finalsemDiff,false); 
            if(count($finalsemDiff)>0) { $currentSEM = $finalsemDiff['0']; } else { $currentSEM = $finalTotal;  }
            } else { $currentSEM = $finalTotal;  }
            
            //echo $currentSEM; die;
            // get courses from landscape
            $sqlCL = $db->select()
                        ->from(array('a' => 'tbl_landscapesubject'),array('a.IdSubject','a.IdSemester','a.SubjectType'))
                        ->joinLeft(array('f'=>'tbl_subjectmaster'),' f.IdSubject = a.IdSubject ',array('f.courseDescription',
                                                                                                     'f.CreditHours','f.CourseType'))                            
                        ->where('a.IdLandscape = ?',$landscapeID)
                        ->where('a.IdProgram = ?',$programID)
                        ->group("a.IdSubject");
            $resultCL = $db->fetchAll($sqlCL);
            if(count($resultCL)>0) {
            foreach($resultCL as $valueCL) { 
                
                $IdSubject  = $valueCL['IdSubject'];
                $IdSemester = $valueCL['IdSemester'];
                
                
                
                
                // get the year id for idsemester for a landscape if the landscpae is block based.
                if($studentdetails['LandscapeType']=='44') { 
                $sqlBY = $db->select()
                        ->from(array('by' => 'tbl_blocksemesteryear'),array('by.Year'))                       
                        ->where('by.IdLandscape = ?',$landscapeID)
                        ->where("by.YearSemester = ?",$IdSemester);
                $resultBy = $db->fetchRow($sqlBY);                
                if(count($resultBy)>0) {   $Year_Level_Block = $resultBy['Year']; }
                }
                
                // get the level id for idsemester for a landscape if the landscpae is level based.
                if($studentdetails['LandscapeType']=='42') { 
                $sqlBL = $db->select()
                        ->from(array('bl' => 'tbl_landscapeblocksemester'),array('bl.blockid'))                       
                        ->where('bl.IdLandscape = ?',$landscapeID)
                        ->where("bl.semesterid = ?",$IdSemester);
                $resultBL = $db->fetchRow($sqlBL);
                if(count($resultBL)>0) {   $Year_Level_Block = $resultBL['blockid']; }
                }
                
                if($studentdetails['LandscapeType']=='43') {
                     $Year_Level_Block = '';
                }
                
                
                
                
//                $sql2CL = $db->select()
//                                 ->from(array('aps' => 'tbl_academicprogress_subjects'),array('aps.Semester','aps.CourseID'))   
//                                 ->where('aps.IdStudent = ?',$lintidreg)
//                                 ->where("aps.CourseID = ?",$IdSubject)
//                                 ->where("aps.Semester = ?",$IdSemester);
                // CHECK FOR course and subject type in academic subject table. no duplicacy should be there in APS.
                $sql2CL = $db->select()
                                            ->from(array('aps' => 'tbl_academicprogress_subjects'),array('aps.IdCourseType','aps.CourseID'))                                                    
                                            ->where('aps.IdCourseType = ?',$valueCL['SubjectType'])
                                            ->where('aps.CourseID = ?',$valueCL['IdSubject'])
                                            ->where('aps.IdStudent = ?',$lintidreg);
                $result2CL = $db->fetchAll($sql2CL);
                if(count($result2CL)==0) {  
                            if($IdSemester=='1') { $IdSemester =  $currentSEM; }
                            $paramData2SM = array(
                                                'IdStudent' => $lintidreg,
                                                'IdAcademicProgress' => $academicprogressID,
                                                'Year_Level_Block'  => $Year_Level_Block,
                                                'Semester' => $IdSemester,
                                                'IdCourseType' => $valueCL['SubjectType'],
                                                'CourseID' => $valueCL['IdSubject'],
                                                'CourseDescription' => $valueCL['courseDescription'],
                                                'CreditHours' => $valueCL['CreditHours'],
                                                'Grade' => '',
                                                'IsRegistered' => '0',
                                                'UpdUser' => $userId,
                                                'UpdDate' => date('Y-m-d H:i:s'),
                                               );
                            $db->insert($tableNAme,$paramData2SM);
                }                
            } 
            }
            
            
        }
        
        
        
        
   
}
