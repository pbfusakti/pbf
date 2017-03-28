<?php
 
class Examination_ExamResultController extends Zend_Controller_Action {

	public function getSemesterKhsAction(){
		 
	
		$stdid = $this->_getParam('stdid',null);
		 
	
		$this->_helper->layout->disableLayout();
		 
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
	
		$dbStd=new App_Model_General_DbTable_Studentsemesterstatus();
		$semester=$dbStd->getRegisteredSemester($stdid);
		foreach ($semester as $key=>$value) {
			$semesters[$key]=array("key"=>$value['IdSemesterMaster'],"value"=>$value['SemesterMainName']);
		}
		 
		$ajaxContext->addActionContext('view', 'html')
		->addActionContext('form', 'html')
		->addActionContext('process', 'json')
		->initContext();
	
		$json = Zend_Json::encode($semesters);
	
		echo $json;
		exit();
	}
	
	public function getKrsAction(){
			
	
		$stdid = $this->_getParam('stdid',null);
		$semesterid = $this->_getParam('semesterid',null);
	
		$this->_helper->layout->disableLayout();
			
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
	
		$dbCoursGroup= new App_Model_General_DbTable_CourseGroup();
		
		$course=$dbCoursGroup->getCourseRegisterByStudent($semesterid, $stdid);
		$dbStaff=new App_Model_General_DbTable_Staffmaster();
		 //echo var_dump($course);exit;
		$staffname='';
		$TableCourses=array();
		foreach ($course as $index=>$value) {
			$TableCourses[$index]['SubCode']=$value['subject_code'];
			$TableCourses[$index]['SubjectName']=$value['subject_name'];
			$TableCourses[$index]['CreditHours']=$value['sks'];
			$TableCourses[$index]['kelas']=$value['GroupName'];
			$TableCourses[$index]['day']=$value['sc_day'];
			$TableCourses[$index]['started']=$value['sc_start_time'];
			$TableCourses[$index]['ended']=$value['sc_end_time'];
			$TableCourses[$index]['venue']=$value['sc_venue'];
			//get lecturer name
			
			if ($value['coordinator']!='') {
				$staff=$dbStaff->getData($value['IdStaff']);
				$staffname=$staff['FullName'];
				if ($staff['BS']!='') $staffname=$staffname.', '.$staff['BS'];
				if ($staff['FS']!='') $staffname=$staff['FS'].' '.$staffname;
			} else $staffname="";
			$TableCourses[$index]['Lecturer']=$staffname;
				  				 
		}
			
		$ajaxContext->addActionContext('view', 'html')
		->addActionContext('form', 'html')
		->addActionContext('process', 'json')
		->initContext();
	
		$json = Zend_Json::encode(array("krs"=>$TableCourses));
	
		echo $json;
		exit();
	}
	 
	
public function getKhsAction(){
    	
    	 
		$mysession = new Zend_Session_Namespace('authtoken');
		
    	$dbPublish=new Examination_Model_DbTable_PublishMark();
    	 
    	$IdStudentRegistration = $this->_getParam('stdid',null);   
    	$idSemester  = $this->_getParam('semesterid',null);
    	
    	$dbSem=new App_Model_General_DbTable_Semestermaster();
    	$semesterStudi = $dbSem->fnGetSemestermaster($idSemester);
    	$token = $this->_getParam('token',null);
	    if ($token==$mysession->authtoken) {
	    		 
	    	 //To get Student Academic Info        
	        $studentRegDB = new Examination_Model_DbTable_StudentRegistration();
	        $student = $studentRegDB->getStudentInfo($IdStudentRegistration);
	        
	        
	        //get info college
	    	$collegedB = new App_Model_General_DbTable_Collegemaster();
	        $college = $collegedB->getData($student["IdCollege"]);
	        
	        //get info program
	        $programDb = new App_Model_General_DbTable_Program();
	        $program = $programDb->getCollegeDean($student["IdProgram"]);
	         
	        //brach
	        $branchDb=new App_Model_General_DbTable_Branchofficevenue();
	        $branch=$branchDb->fnviewBranchofficevenueDtls($student['IdBranch']);
	        
	        //get info majoring
	        $majorDb = new App_Model_General_DbTable_ProgramMajoring();
	        $major = $majorDb->getInfo($student['IdProgramMajoring']);
	        if ($major['Address1']=='') {
	        	$addphone=$branch["Phone"];
	        	$addemail=$branch["Email"];
	        	$add=ucwords(strtolower($branch["Addr1"])).' '.ucwords(strtolower($branch["Addr2"])).' '.ucwords(strtolower($branch["StateName"])).' '.ucwords(strtolower($branch["CountryName"]));
	        } else {
	        	$addphone=$major["Phone"];
	        	$addemail=$major["Email"];
	        	$add=ucwords(strtolower($major["Addr1"])).' '.ucwords(strtolower($major["Addr2"])).' '.ucwords(strtolower($major["StateName"])).' '.ucwords(strtolower($major["CountryName"]));
	        }
	        //get salutation
	    	$defDB = new App_Model_General_DbTable_Definationms();
	    	$academic_front_salutation = $defDB->getData($student["FrontSalutation"]);
	    	$academic_back_salutation  = $defDB->getData($student["BackSalutation"]);
	    	    	 
	        //get photo student
	    	$uploadFileDb = new App_Model_General_DbTable_UploadFile();
	    	$file = $uploadFileDb->getFile($student["transaction_id"],51);
	    	    	
			if(isset($file["pathupload"])){
	    		if (file_exists($file["pathupload"])) {
	    			$photo_url = $file["pathupload"];	    		
	    		}else{
	    			$photo_url = "/var/www/html/triapp/public/images/no_image.gif";
	    		}
	    	}else{
	    		$photo_url = "/var/www/html/triapp/public/images/no_image.gif";
	    	}    	
	    	
	    	//get semester regsiter info
		    $courseRegisterDb = new Examination_Model_DbTable_StudentRegistrationSubject(); 	   	
	       
	        $regularSem = $courseRegisterDb->getSemesterRegular($idSemester,$IdStudentRegistration);
	    	 	
	    	 //To get Registered Courses   
	         $landscapeDb = new App_Model_General_DbTable_Landscape();
	         $landscape = $landscapeDb->getData($student["IdLandscape"]);
	         
	        	if($landscape["LandscapeType"]==43) {//Semester Based 
			         		
	         			//get course registered  per semester
			  			$courseRegisterDb = new Examination_Model_DbTable_StudentRegistration();
			  			$courses = $courseRegisterDb->getCourseRegisteredBySemester($IdStudentRegistration,$idSemester);
			  			foreach ($courses as $index=>$value) {
			  				if ($dbPublish->isAllMarkPublished($idSemester, $value['IdProgram'], $value['IdSubject'], $value['IdCourseTaggingGroup'])) {
			  					$TableCourses[$index]['publish']="1";
			  				} else $TableCourses[$index]['publish']="0";
			  				
			  				$TableCourses[$index]['SubCode']=$value['SubCode'];
			  				$TableCourses[$index]['SubjectName']=$value['SubjectName'];
			  				$TableCourses[$index]['CreditHours']=$value['CreditHours'];
			  				$TableCourses[$index]['grade_name']=$value['grade_name'];
			  				$TableCourses[$index]['exam_status']=$value['exam_status'];
			  				$TableCourses[$index]['final_course_mark']=$value['final_course_mark'];
			  				$TableCourses[$index]['grade_status']=$value['grade_status'];
			  				
			  			}
			  			
			  			//get gpa and cgpa
			  			$studentGradeDb = new Examination_Model_DbTable_StudentGrade();
			  			//$grade = $studentGradeDb->getGradebySemester($IdStudentRegistration,$idSemesterStatus);
			  			$grade = $studentGradeDb->getStudentGrade($IdStudentRegistration,$regularSem['IdSemesterMaster']);
			  			$gpa=$grade["sg_univ_gpa"];
					  			
	           		
	         	}elseif($landscape["LandscapeType"]==44){
	         	
			         	//get registered blocks
			         	$studentSemesterDB = new App_Model_General_DbTable_Studentsemesterstatus();
			         	$semester_blocks = $studentSemesterDB->getRegisteredSemesterBlock($IdStudentRegistration,$idSemester);         	
			         	
			         	foreach($semester_blocks as $key=>$block){
					
				         	//get course registered  by block
				  			$courseRegisterDb = new Examination_Model_DbTable_StudentRegistration();
				  			$courses = $courseRegisterDb->getCourseRegisteredBySemesterBlock($IdStudentRegistration,$idSemester,$block["IdBlock"]);
				  			foreach ($courses as $index=>$value) {
				  				if ($dbPublish->isAllMarkPublished($idSemester, $value['IdProgram'], $value['IdSubject'], $value['IdCourseTaggingGroup'])) {
				  					$TableCourses[$index]['publish']="1";
				  				} else $TableCourses[$index]['publish']="0";
				  				$TableCourses[$index]['SubCode']=$value['SubCode'];
				  				$TableCourses[$index]['SubjectName']=$value['SubjectName'];
				  				$TableCourses[$index]['CreditHours']=$value['CreditHours'];
				  				$TableCourses[$index]['grade_name']=$value['grade_name'];
				  				$TableCourses[$index]['exam_status']=$value['exam_status'];
				  				$TableCourses[$index]['final_course_mark']=$value['final_course_mark'];
				  				$TableCourses[$index]['grade_status']=$value['grade_status'];
				  				 
				  				
				  			}
				  			 
				  			//$semester_blocks[$key]["courses"]=$courses;
				  			
				  			//get gpa and cgpa
				  			$studentGradeDb = new Examination_Model_DbTable_StudentGrade();
				  			//$grade = $studentGradeDb->getGradebySemester($IdStudentRegistration,$block["idstudentsemsterstatus"]);
				  			$grade = $studentGradeDb->getStudentGrade($IdStudentRegistration,$regularSem['IdSemesterMaster']);
				  			$gpa=$grade["sg_univ_gpa"];
				  			
			         	}	//end foreach
			         		         	 
	         	}//end if
	         	
	         	$chlimitDB = new App_Model_General_DbTable_Chlimit();
	         	$limit=$chlimitDB->getLimit($student['IdProgram'], $student['IdIntake'], $gpa);
	         	 
	        
	         $fieldValues = array(
	    	  'PROGRAM'=>$student["ArabicName"],
	    	  'FACULTY'=>'FAKULTAS '.$student["NamaKolej"],
	    	  'NIM'=>$student["registrationId"],
	    	  'NAME'=>$student["appl_fname"].' '.$student["appl_mname"].' '.$student["appl_lname"],    	 
	    	  'ACADEMIC_ADVISOR'=>$academic_front_salutation["BahasaIndonesia"].' '.$student["AcademicAdvisor"].' '.$academic_back_salutation["BahasaIndonesia"],    	 
	    	  'PHOTO'=>$photo_url,    	 
			 
	          'SEMESTER'=>$semesterStudi["SemesterMainName"],
	    	  'SKS_LULUS'=>$grade["sg_sem_sks_lulus"],
	    	  'SKS_GAGAL'=>$grade["sg_sem_sks_gagal"],
	    	  'TOTAL_SKS'=>$grade["sg_univ_sem_credithour"],
	    	  'SKS_KUMULATIF'=>$grade["sg_cum_credithour"],
	    	  'STRATA'=>$student["strata"],
	    	  'IPS'=>$grade["sg_univ_gpa"],
	    	  'IPK'=>$grade["sg_cgpa"],
			  'limit'=>$limit,
	    	  'KONSENTRASI'=>$student["majoring"],
	    	  'MAJORING'=>$student["majoring_english"],
	          'ADDRESS'=>ucwords(strtolower($program["Address1"])).' '.ucwords(strtolower($program["Address2"])).' '.ucwords(strtolower($program["CityName"])).' '.ucwords(strtolower($program["StateName"])),
	          'PHONE'=>$program["Phone1"],
	          'EMAIL'=>$program["Email"],
	          'B_ADDRESS'=>$add,
	          'B_PHONE'=>$addphone,
	          'B_EMAIL'=>$addemail
	    	);
	         
	         
	         
	    	
	    	$result=array('header'=>$fieldValues,'courses'=>$TableCourses);
	    }
	       
    	else{
    		 
    		$result=array();
    	}
    	
    	$this->_helper->layout->disableLayout();
    	
    	$ajaxContext = $this->_helper->getHelper('AjaxContext');
    	
    	 
    	$ajaxContext->addActionContext('view', 'html')
    	->addActionContext('form', 'html')
    	->addActionContext('process', 'json')
    	->initContext();
    	
    	$json = Zend_Json::encode($result);
    	
    	echo $json;
    	exit();
    	
     }
     
     public function transcriptAction(){
     
     	$this->view->title = "Daftar Prestasi Akademik";
     		
     	$form = new Examination_Form_ExamResultSearchForm();
     
     	//intake
     	$intakeDb = new App_Model_Record_DbTable_Intake();
     	$this->view->intake_list = $intakeDb->getData();
     
     
     	//program
     	$programDb = new Registration_Model_DbTable_Program();
     	$this->view->program_list = $programDb->getData();
     
     	//semester
     	$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
     	$semesterList = $semesterDB->fnGetSemestermasterList();
     	$this->view->semester_list = $semesterList;
     
     
     	if ($this->getRequest()->isPost()) {
     			
     		$formData = $this->getRequest()->getPost();
     
     		$form->populate($formData);
     		//get Student
     		$studentRegDB = new Examination_Model_DbTable_StudentRegistration();
     		$student_list = $studentRegDB->getStudentRegistration($formData);
     		$this->view->student_list = $student_list;
     			
     	}
     	$this->view->form = $form;
     
     }
	public function tempTranscriptAction(){
		
		$this->view->title = "Daftar Prestasi Akademik";
		 
		$form = new Examination_Form_ExamResultSearchForm();		
		
		//intake
		$intakeDb = new App_Model_Record_DbTable_Intake();
		$this->view->intake_list = $intakeDb->getData();	

		
		//program
		$programDb = new Registration_Model_DbTable_Program();
		$this->view->program_list = $programDb->getData();	
		
		//semester
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		$semesterList = $semesterDB->fnGetSemestermasterList();
		$this->view->semester_list = $semesterList;
		
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();	

			$form->populate($formData);
			//get Student
			$studentRegDB = new Examination_Model_DbTable_StudentRegistration();
			$student_list = $studentRegDB->getStudentRegistration($formData);
			$this->view->student_list = $student_list;
			
		}
		$this->view->form = $form;
		
	}
	
	public function getTranscriptAction(){
		
	 
		$IdStudentRegistration = $this->_getParam('stdid',null);  
		$this->view->id= $IdStudentRegistration;
		$idProfile = $this->_getParam('idProfile',null); 
		$this->view->idProfile =$idProfile;
		 //To get Student Academic Info   
		$dbGrade = new Examination_Model_DbTable_Grade();     
        $studentRegDB = new Graduation_Model_DbTable_Graduation();
        $student = $studentRegDB->getGraduatesNoWis(null,null,$IdStudentRegistration);
        //echo var_dump($student);
        //exit;
        if ($student!=array())   {
        	if($student["majoring"]==""|| $student["majoring"]=="common" || $student["majoring"]=="bersama"){
        		$student["majoring"]="-";
        		$student["majoring_english"]="-";
        	}
        $this->view->student = $student;

        if ($student["Dept_Bahasa"]=='-') $jurusan=$student["program_name"];
        else $jurusan=$student["Dept_Bahasa"].' / '.$student["program_name"];
        if ($student["Departement"]=='-') $jurusanEng=$student["programEng"];
        else $jurusanEng=$student["Departement"].' / '.$student["programEng"];
        $this->view->jurusan=$jurusan;
        $this->view->jurusanEng=$jurusanEng;
         //get photo student
    	$uploadFileDb = new App_Model_Application_DbTable_UploadFile();
    	$file = $uploadFileDb->getFile($student["transaction_id"],51);
    	    	
		if(isset($file["pathupload"])){
    		if (file_exists($file["pathupload"])) {
    			
    			$fnImage = new icampus_Function_General_Image();
    			$photo_url = $fnImage->getImagePath($file['pathupload'],100,123);
    			//$photo_url = str_replace("/var/www/html/triapp", "", $file["pathupload"]);
    				
    			$this->view->photo_url  = $photo_url;
    		}else{
    			$this->view->photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
    		}
    	}else{
    		$this->view->photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
    	}
    	

    	$studentGradeDB = new Examination_Model_DbTable_StudentGrade();
    	$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
    	
    	$student_grade = $studentGradeDB->getStudentGradeInfo($IdStudentRegistration);
    	$this->view->student_grade = $student_grade;
    	
    	$grade=$dbGrade->getGradeProgram( $student['IdProgram'], $student['IdSemesterMain']);
    	$this->view->gradeset=$grade;
    	//echo var_dump($grade);exit;
    	//get cgpa info
    	$gradeDb = new Examination_Model_DbTable_Academicstatus();
    	//echo $student_grade['sg_semesterId']."xx".$student['IdProgram'];exit;
    	$this->view->grade = $gradeDb->getListAcademicStatus($student_grade['sg_semesterId'],$student['IdProgram'],$type=1,$basedon='Program');
    	//echo var_dump($this->view->grade);
    	//exit;
    	//get dean info
    	$deanDB = new GeneralSetup_Model_DbTable_Deanmaster();
    	$dean = $deanDB->getCollegeDean($student['idCol']);
    	
    	//get salutatuion
    	$definationsDb = new App_Model_General_DbTable_Definationms();
    	$FrontSalutation = $definationsDb->getData($dean['FrontSalutation']);
    	$BackSalutation  = $definationsDb->getData($dean['BackSalutation']);
    	
    	$deanName=$dean['Fullname'];
    	if ($FrontSalutation['DefinitionDesc']!='') {
    		$deanName=$FrontSalutation['DefinitionDesc'].' '.$deanName;
    	}
    	if (isset($BackSalutation['DefinitionDesc'])) {
    		$deanName=$deanName.', '.$BackSalutation['DefinitionDesc'];
    	}
    	$this->view->deanName = $deanName;
    	//transcript profile
    	$DbProfile = new GeneralSetup_Model_DbTable_TranscriptProfile();
    	$DbProfileDetail = new GeneralSetup_Model_DbTable_TranscriptProfileDetail();
    	//get category and course list
    	//echo $idProfile;exit;
    	
    	if ($idProfile=='*') {
    		$dbLands = new GeneralSetup_Model_DbTable_Landscapesubject();
    		$dbBlock= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
    		$dbProgReq = new GeneralSetup_Model_DbTable_Programrequirement();
    		$subject_category = $dbProgReq->getlandscapecoursetype($student['IdProgram'], $student['IdLandscape']);    	
    		foreach($subject_category as $index=>$category){
    			$subject_list = $dbLands->getlandscapesubjectsPerCategory($student['IdLandscape'],$category["SubjectType"]);
    			if ($subject_list==array()) $subject_list = $dbBlock->getLandscapeCoursePerCategory($student['IdLandscape'],$category["SubjectType"]);
    			unset($subjecthighest);
    			foreach ($subject_list as $key=>$subject) {
    				$subject=$regSubjectDB->getHighestMarkofAllSemester($IdStudentRegistration, $subject['IdSubject']);
    				if (!is_bool($subject)) $subjecthighest[$key] = $subject;
    			}
    			if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
    			else unset($subject_category[$index]);
    			
    		}  
    		   	
    	}
    	else 
    	{
    		
    		$subject_category = $DbProfileDetail->fnGetTranscriptProfileName($idProfile);
    		foreach($subject_category as $index=>$category){
    			$subjecthighest=array();
    			$subject_list = $DbProfileDetail->fnGetTranscriptProfileSubject($idProfile,$category['idGroup']);
    			unset($subjecthighest);
    			foreach($subject_list as $key=>$subject) :
    				$subject=$regSubjectDB->getHighestMarkofAllSemesterProfile($IdStudentRegistration, $subject['idSubject'],$idProfile);
    				if (!is_bool($subject)) $subjecthighest[$key] = $subject;
    			endforeach;
    			if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
    			else unset($subject_category[$index]);
    			
    		}
    	}
    	$db = new Finalassignment_Model_DbTable_FinalAssignment();
    	$ta = $db->fnGetFinalAssigmentStd($IdStudentRegistration);
    	//exit;
    	if (isset($ta)) {
    		$this->view->TaTitle=$ta['Title'];
    		$this->view->TaTitleBahasa=$ta['TitleBahasa'];
    	}else{
    		$this->view->TaTitle=null;
    		$this->view->TaTitleBahasa=null;
    	}
    	$this->view->subject_category = $subject_category;
    	
        }
    	
    	/*echo '<pre>';
        print_r($subject_category);
        echo '</pre>';*/
	}
	
	public function searchStudentAction(){
		
		$this->view->title = "Search Student";
		 
		$form = new Examination_Form_ExamResultSearchStudentForm();		
		
		//intake
		$intakeDb = new App_Model_Record_DbTable_Intake();
		$this->view->intake_list = $intakeDb->getData();
		
		//program
		$programDb = new Registration_Model_DbTable_Program();
		$this->view->program_list = $programDb->getData();	
		
		//semester
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		$semesterList = $semesterDB->fnGetSemestermasterList();
		$this->view->semester_list = $semesterList;		
		
		if ($this->getRequest()->isPost()) {
			
			$formData = $this->getRequest()->getPost();	

			if($form->isValid($formData)){
          
				$form->populate($formData);
				
				$this->view->IdSemester = $formData['IdSemester'];
				
				//get Student
				$studentRegDB = new Examination_Model_DbTable_StudentRegistration();
				$student_list = $studentRegDB->SearchRegisterStudent($formData);
				$this->view->student_list = $student_list;
			}
			
		}
		$this->view->form = $form;
		
	}
	
	public function getTempTranscriptAction(){
	 
		$IdStudentRegistration = $this->_getParam('stdid',null);
		 
		//To get Student Academic Info
		$studentRegDB = new Examination_Model_DbTable_StudentRegistration();
		$student = $studentRegDB->getStudentInfo($IdStudentRegistration);
		if($student["majoring"]=="common"||$student["majoring"]=="bersama"){
			$student["majoring"]="-";
			$student["majoring_english"]="-";
		}
		$idProfile=$student['idTranscriptProfile'];
		 
		$studentGradeDB = new Examination_Model_DbTable_StudentGrade();
		$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
		 
		$student_grade = $studentGradeDB->getStudentGradeInfo($IdStudentRegistration);
		 
		 
		//get cgpa info
		$gradeDb = new Examination_Model_DbTable_Academicstatus();
		//echo $student_grade['sg_semesterId']."xx".$student['IdProgram'];exit;
		$grade = $gradeDb->getListAcademicStatus($student_grade['sg_semesterId'],$student['IdProgram'],$type=1,$basedon='Program');
	
		//get dean info
		$deanDB = new App_Model_General_DbTable_Deanmaster();
		$dean = $deanDB->getCollegeDean($student['IdCollege']);
		
		 
		//get salutatuion
		$definationsDb = new App_Model_General_DbTable_Definationms();
		$FrontSalutation = $definationsDb->getData($dean['FrontSalutation']);
		$BackSalutation  = $definationsDb->getData($dean['BackSalutation']);
		
		$deanName=$dean['Fullname'];
		if (isset($FrontSalutation['DefinitionDesc'])) {
			$deanName=$FrontSalutation['DefinitionDesc'].' '.$deanName;
		}
		if (isset($BackSalutation['DefinitionDesc'])) {
			$deanName=$deanName.', '.$BackSalutation['DefinitionDesc'];
		}
		 
		//transcript profile
		$DbProfile = new App_Model_General_DbTable_TranscriptProfile();
		$DbProfileDetail = new App_Model_General_DbTable_TranscriptProfileDetail();
		$subject_category =$this->getTranscriptList($IdStudentRegistration,$idProfile);
		 
		$result=array('Item'=>array('dean'=>$deanName,
									'major'=>$student['majoring'],
									'ipk'=>$student_grade['sg_cgpa'],
									'skstotal'=>$student_grade['sg_cum_credithour']
									),
				      'course'=>$subject_category);
	
	
		$this->_helper->layout->disableLayout();
    	
    	$ajaxContext = $this->_helper->getHelper('AjaxContext');
    	
    	 
    	$ajaxContext->addActionContext('view', 'html')
    	->addActionContext('form', 'html')
    	->addActionContext('process', 'json')
    	->initContext();
    	
    	$json = Zend_Json::encode($result);
    	
    	echo $json;
    	exit();
	}
	
	
	 
	
	
	public function cetakTranscriptAction(){
    	
		
		$IdStudentRegistration = $this->_getParam('id',null);   
		$idProfile = $this->_getParam('idProfile',null);
		
		 //To get Student Academic Info        
        $studentRegDB = new Graduation_Model_DbTable_Graduation();
        $student = $studentRegDB->getGraduatesNoWis(null,null,$IdStudentRegistration);
		$id = $student['id'];
        if($student["majoring"]=="common" || $student["majoring"]=="bersama"){
        	$student["majoring"]="-";
        	$student["majoring_english"]="-";
        }
        if ($student["Dept_Bahasa"]=='-') $jurusan=$student["program_name"];
        else $jurusan=$student["Dept_Bahasa"].' / '.$student["program_name"];
        if ($student["Departement"]=='-') $jurusanEng=$student["programEng"];
        else $jurusanEng=$student["Departement"].' / '.$student["programEng"];

        //grade setup
        global $gradeset;
        $dbGrade=new Examination_Model_DbTable_Grade();
        $gradeset=$dbGrade->getGradeProgram( $student['IdProgram'], $student['IdSemesterMain']);

        global $majoring;
        $majoring=$student["majoring"];
         //get photo student
    	$uploadFileDb = new App_Model_Application_DbTable_UploadFile();
    	$file = $uploadFileDb->getFile($student["transaction_id"],51);
    	    	
		if(isset($file["pathupload"])){
    		if (file_exists($file["pathupload"])) {
    			$fnImage = new icampus_Function_General_Image();
    			$photo_url = "http://".ONNAPP_HOSTNAME.$fnImage->getImagePath($file['pathupload'],100,123);	
    			//$photo_url = str_replace("/var/www/html/triapp","http://".ONNAPP_HOSTNAME."/", $file["pathupload"]);
    		}else{
    			$photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
    		}
    	}else{
    		$photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
    	}
    	

    	//get info college
    	$collegedB = new GeneralSetup_Model_DbTable_Collegemaster();
        $college = $collegedB->getFullInfoCollege($student["idCol"]);
        
				        
    	$studentGradeDB = new Examination_Model_DbTable_StudentGrade();
    	$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
    	
    	$student_grade = $studentGradeDB->getStudentGradeInfo($IdStudentRegistration);
    	$DbProfile = new GeneralSetup_Model_DbTable_TranscriptProfile();
    	$DbProfileDetail = new GeneralSetup_Model_DbTable_TranscriptProfileDetail();
    	//get cgpa info
    	global $grade;
    	$gradeDb = new Examination_Model_DbTable_Academicstatus();
    	$grade = $gradeDb->getListAcademicStatus($student_grade['sg_semesterId'],$student['IdProgram'],$type=1,$basedon='Program');
    	//echo var_dump($grade);
    	//exit;  	
    	//get dean info
    	$deanDB = new GeneralSetup_Model_DbTable_Deanmaster();
    	$dean = $deanDB->getCollegeDean($student['idCol']);
    	
    	//get salutatuion
    	$definationsDb = new App_Model_General_DbTable_Definationms();
    	$FrontSalutation = $definationsDb->getData($dean['FrontSalutation']);
    	$BackSalutation  = $definationsDb->getData($dean['BackSalutation']);
    	
    	$deanName=$dean['Fullname'];
    	if (isset($FrontSalutation['DefinitionDesc'])) {
    		$deanName=$FrontSalutation['DefinitionDesc'].' '.$deanName;
    	}
    	if (isset($BackSalutation['DefinitionDesc'])) {
    		$deanName=$deanName.', '.$BackSalutation['DefinitionDesc'];
    	}
    	 
    	//get category and course list
    	global $subject_category;
		$subject_category = $this->getTranscriptList($IdStudentRegistration,$idProfile);
	/*if ($idProfile=='*') {
    		
	$dbLands = new GeneralSetup_Model_DbTable_Landscapesubject();
    		$dbBlock= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
    		$dbProgReq = new GeneralSetup_Model_DbTable_Programrequirement();
    		$subject_category = $dbProgReq->getlandscapecoursetype($student['IdProgram'], $student['IdLandscape']);    	
    		foreach($subject_category as $index=>$category){
    			$subject_list = $dbLands->getlandscapesubjectsPerCategory($student['IdLandscape'],$category["SubjectType"]);
    			if ($subject_list==array()) $subject_list = $dbBlock->getLandscapeCoursePerCategory($student['IdLandscape'],$category["SubjectType"]);
    			unset($subjecthighest);
    			foreach ($subject_list as $key=>$subject) {
    				$subject=$regSubjectDB->getHighestMarkofAllSemester($IdStudentRegistration, $subject['IdSubject']);
    				if (!is_bool($subject)) $subjecthighest[$key] = $subject;
    			}
    			if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
    			else unset($subject_category[$index]);
    			
    		}  
    		  	
    	}
    	else 
    	{
    		
    		$subject_category = $DbProfileDetail->fnGetTranscriptProfileName($idProfile);
    		//echo var_dump($subject_category);
    		//exit;
    		foreach($subject_category as $index=>$category){
    			$subjecthighest=array();
    			$subject_list = $DbProfileDetail->fnGetTranscriptProfileSubject($idProfile,$category['idGroup']);
    			foreach($subject_list as $key=>$subject) :
    				$subject=$regSubjectDB->getHighestMarkofAllSemesterProfile($IdStudentRegistration, $subject['idSubject'],$idProfile);
    				if (!is_bool($subject)) $subjecthighest[$key] = $subject;
    			endforeach;
    			$subject_category[$index]["subjects"] = $subjecthighest;
    		}
    	}
    	*/
		$dbCommon=new App_Model_Common();
    	$dtYDS = $student['date_of_Yudisium'];
    	//$dtSKR = $student['date_of_skr'];
    	$dtlhr = $student['appl_dob'];
    	$dtYDSLokal = $dbCommon->fnCovertDateToLocalFormat($dtYDS);
    	//$dtSKRLokal = $dbCommon->fnCovertDateToLocalFormat($dtSKR);
    	$dtDobLokal = $dbCommon->fnCovertDateToLocalFormat($dtlhr);
    	$dtYDSEng = $dbCommon->fnCovertDateToEnglishFormat($dtYDS);
    	//$dtSKREng = $dbCommon->fnCovertDateToEnglishFormat($dtSKR);
    	$dtDobEng = $dbCommon->fnCovertDateToEnglishFormat($dtlhr);
    	//Final Assignment
    	
    	$db = new Finalassignment_Model_DbTable_FinalAssignment();
    	$ta = $db->fnGetFinalAssigmentStd($IdStudentRegistration);
    	//exit;
    	if (isset($ta)) {
    		$title=$ta['Title'];
    		$bahasa=$ta['TitleBahasa'];
    	}else{
    		$title='-';
    		$bahasa='-';
    	}
    	$fieldValues = array(
			    	  '$[JURUSAN]'=>$jurusan,
    	 			  '$[PROGRAMSTUDI]'=>$student["program_name"],
    				  '$[DEPARTMENT]'=>$jurusanEng,
    	 			  '$[STUDYPROGRAM]'=>$student["programEng"],
			    	  '$[FAKULTAS]'=>$student["CollegeBahasa"],
    	 			  '$[FACULTY]'=> $student["CollegeName"],			    	 
			    	  '$[ADDRESS]'=>ucwords(strtolower($college["Add1"])).' '.ucwords(strtolower($college["Add2"])).' '.ucwords(strtolower($college["CityName"])).' '.ucwords(strtolower($college["StateName"])),
					  '$[PHONE]'=>$college["Phone1"],
					  '$[EMAIL]'=>$college["Email"],
    				  '$[KONSENTRASI]'=>$student["majoring"],
					  '$[MAJORING]'=>$student["majoring_english"],
    				  '$[PROGRAMPENDIDIKAN]'=>$student['program_pendidikan'],
    				  '$[SCHEME]'=>$student["strata"],
					  '$[PROGRAM]'=>$student["program_eng"],
    				  '$[STUDENTNAME]'=>$student["appl_fname"].' '.$student["appl_mname"].' '.$student["appl_lname"],
					  '$[BIRTHDATE]'=>$student["appl_birth_place"].', '.$dtDobLokal,
    				  '$[NIM]'=>$student["registrationId"],
    				  '$[PHOTO]'=>$photo_url,
    				  '$[DEAN]'=>$deanName,
    				   '$[NIKDEAN]'=>$dean['StaffId'].'/USAKTI',
    	 			  '$[TOTALCREDITHOUR]'=>$student_grade['sg_cum_credithour'],	
    	 			  '$[TOTALPOINT]'=>number_format($student_grade['sg_cum_totalpoint'], 2, '.', ''),
			    	  '$[GPA]'=>number_format($student_grade['sg_cgpa'], 2, '.', ''),
			    	  '$[CGPA_STATUS]'=>$student_grade['sg_cgpa_status'],
    				  '$[CGPA_STATUS_ENG]'=>$student_grade['sg_cgpa_status_eng'],
    				   '$[TGLYDSM]'=>$dtYDSLokal,
    				   '$[TITLE]'=>$title,
    				   '$[TITLEBAHASA]'=>$bahasa
		    	   );
		
	    require_once 'dompdf_config.inc.php';
	
		$autoloader = Zend_Loader_Autoloader::getInstance(); // assuming we're in a controller
		$autoloader->pushAutoloader('DOMPDF_autoload');
		
		//template path	 
		$html_template_path = DOCUMENT_PATH."/template/transcript.html";
		
		$html = file_get_contents($html_template_path);			
    		
		//replace variable
		foreach ($fieldValues as $key=>$value){
			$html = str_replace($key,$value,$html);	
		}
			
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'potrait');
		$dompdf->render();

		//echo $html;exit;
		$output_directory_path = DOCUMENT_PATH."/student/transcript";
		
		//create directory to locate file
		if (!is_dir($output_directory_path)) {
			mkdir($output_directory_path, 0775,true);
		}
		//output filename 
		$output_filename = "transcript_".$student['registrationId'].".pdf";
				
		$dompdf = $dompdf->output();
		//$dompdf->stream($output_filename);						
							
		//to rename output file						
	    $output_file_path = $output_directory_path.'/'.$output_filename;
		
		file_put_contents($output_file_path, $dompdf);
		
		$this->view->file_path = $output_file_path;
		//save file address
		$db = new Graduation_Model_DbTable_Graduation();
		$db->updateGraduate(array('transcript'=>'/documents/student/transcript/'.$output_filename),$id);
		$this->_redirect($this->view->url(array('module'=>'reports','controller'=>'graduation-report', 'action'=>'ijazah-index', 'program'=>$student['IdProgram'],'semester'=>$student['IdSemesterMain']),'default',true));
		//exit;
		
		//exit;
		
	}
	public function cetakTempTranscriptAction(){
		 
	
		$IdStudentRegistration = $this->_getParam('id',null);
		$idProfile = $this->_getParam('idProfile',null);
	
		//To get Student Academic Info
		$studentRegDB = new Examination_Model_DbTable_StudentRegistration();
		$student = $studentRegDB->getStudentInfo($IdStudentRegistration);
		if($student["majoring"]=="common"|$student["majoring"]=="Bersama"){
			$student["majoring"]="-";
			$student["majoring_english"]="-";
		}
		//echo var_dump($student);exit;
		//grade setup
		global $gradeset;
		$dbGrade=new Examination_Model_DbTable_Grade();
		$gradeset=$dbGrade->getGradeProgram( $student['IdProgram'], $student['IdSemesterMain']);
		
		global $majoring;
		global $printmajoring;
		$printmajoring=$student['print_majoring'];
		$transcripttemplate=$student['transcript_template'];
		$majoring=$student["majoring"];
		//get photo student
		$uploadFileDb = new App_Model_Application_DbTable_UploadFile();
		$file = $uploadFileDb->getFile($student["transaction_id"],51);
	
		if(isset($file["pathupload"])){
			if (file_exists($file["pathupload"])) {
				$fnImage = new icampus_Function_General_Image();
				$photo_url = "http://".ONNAPP_HOSTNAME.$fnImage->getImagePath($file['pathupload'],100,123);
				//$photo_url = str_replace("/var/www/html/triapp","http://".ONNAPP_HOSTNAME."/", $file["pathupload"]);
			}else{
				$photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
			}
		}else{
			$photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
		}
		 
	
		//get info college
		$collegedB = new GeneralSetup_Model_DbTable_Collegemaster();
		$college = $collegedB->getFullInfoCollege($student["IdCollege"]);
	
	
		$studentGradeDB = new Examination_Model_DbTable_StudentGrade();
		$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
		 
		$student_grade = $studentGradeDB->getStudentGradeInfo($IdStudentRegistration);
		$DbProfile = new GeneralSetup_Model_DbTable_TranscriptProfile();
		$DbProfileDetail = new GeneralSetup_Model_DbTable_TranscriptProfileDetail();
		//get cgpa info
		global $grade;
		$gradeDb = new Examination_Model_DbTable_Academicstatus();
		$grade = $gradeDb->getListAcademicStatus($student_grade['sg_semesterId'],$student['IdProgram'],$type=1,$basedon='Program');
	
		
		//get dean info
		$deanDB = new GeneralSetup_Model_DbTable_Deanmaster();
		$dean = $deanDB->getCollegeDean($student['IdCollege']);
		
		 
		//get salutatuion
		$definationsDb = new App_Model_General_DbTable_Definationms();
		$FrontSalutation = $definationsDb->getData($dean['FrontSalutation']);
		$BackSalutation  = $definationsDb->getData($dean['BackSalutation']);
		 
		$deanName=$dean['Fullname'];
		if (isset($FrontSalutation['DefinitionDesc'])) {
			$deanName=$FrontSalutation['DefinitionDesc'].' '.$deanName;
		}
		if (isset($BackSalutation['DefinitionDesc'])) {
			$deanName=$deanName.', '.$BackSalutation['DefinitionDesc'];
		}
		//get category and course list
		global $subject_category;
		if ($idProfile=='*') {
	
			$dbLands = new GeneralSetup_Model_DbTable_Landscapesubject();
    		$dbBlock= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
    		$dbProgReq = new GeneralSetup_Model_DbTable_Programrequirement();
    		$subject_category = $dbProgReq->getlandscapecoursetype($student['IdProgram'], $student['IdLandscape']);    	
    		foreach($subject_category as $index=>$category){
    			$subject_list = $dbLands->getlandscapesubjectsPerCategory($student['IdLandscape'],$category["SubjectType"]);
    			if ($subject_list==array()) $subject_list = $dbBlock->getLandscapeCoursePerCategory($student['IdLandscape'],$category["SubjectType"]);
    			unset($subjecthighest);
    			foreach ($subject_list as $key=>$subject) {
    				$subject=$regSubjectDB->getHighestMarkofAllSemester($IdStudentRegistration, $subject['IdSubject']);
    				if (!is_bool($subject)) $subjecthighest[$key] = $subject;
    			}
    			if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
    			else unset($subject_category[$index]);
    			
    		}  
				
		}
		else
		{
	
			$subject_category = $DbProfileDetail->fnGetTranscriptProfileName($idProfile);
			//echo var_dump($subject_category);
			//exit;
			foreach($subject_category as $index=>$category){
				$subjecthighest=array();
				$subject_list = $DbProfileDetail->fnGetTranscriptProfileSubject($idProfile,$category['idGroup']);
				foreach($subject_list as $key=>$subject) :
					$subject=$regSubjectDB->getHighestMarkofAllSemesterProfile($IdStudentRegistration, $subject['idSubject'],$idProfile);
					if (!is_bool($subject)) $subjecthighest[$key] = $subject;
				endforeach;
				if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
    			else unset($subject_category[$index]);
    			
			}
		}
	
		$fieldValues = array(
				'$[JURUSAN]'=>$student["Dept_Bahasa"],
				'$[PROGRAMSTUDI]'=>$student["ArabicName"],
				'$[DEPARTMENT]'=>$student["Departement"],
				'$[STUDYPROGRAM]'=>$student["ProgramName"],
				'$[FAKULTAS]'=>'FAKULTAS '.$college["ArabicName"],
				'$[FACULTY]'=> $college["CollegeName"],
				'$[ADDRESS]'=>ucwords(strtolower($college["Add1"])).' '.$college["Add2"].' '.ucwords(strtolower($college["CityName"])).' '.ucwords(strtolower($college["StateName"])),
				'$[PHONE]'=>$college["Phone1"],
				'$[EMAIL]'=>$college["Email"],
				'$[KONSENTRASI]'=>$student["majoring"],
				'$[MAJORING]'=>$student["majoring_english"],
				'$[PROGRAMPENDIDIKAN]'=>$student["program_pendidikan"],
				'$[SCHEME]'=>$student["strata"],
				'$[PROGRAM]'=>$student["program_eng"],
				'$[STUDENTNAME]'=>$student["appl_fname"].' '.$student["appl_mname"].' '.$student["appl_lname"],
				'$[BIRTHDATE]'=>$student["appl_birth_place"].', '.strftime("%e %B, %Y", strtotime($student["appl_dob"])),
				'$[NIM]'=>$student["registrationId"],
				'$[PHOTO]'=>$photo_url,
				'$[DEAN]'=>$deanName,
				'$[TOTALCREDITHOUR]'=>$student_grade['sg_cum_credithour'],
				'$[TOTALPOINT]'=>number_format($student_grade['sg_cum_totalpoint'], 2, '.', ''),
				'$[GPA]'=>number_format($student_grade['sg_cgpa'], 2, '.', ''),
				'$[CGPA_STATUS]'=>$student_grade['sg_cgpa_status']
		);
	
	//echo var_dump($student);
	//exit;
		require_once 'dompdf_config.inc.php';
	
		$autoloader = Zend_Loader_Autoloader::getInstance(); // assuming we're in a controller
		$autoloader->pushAutoloader('DOMPDF_autoload');
	
		//template path
		$html_template_path = DOCUMENT_PATH."/template/transcript_temp.html";
		//echo $html_template_path;exit;
		$html = file_get_contents($html_template_path);
	
		//replace variable
		foreach ($fieldValues as $key=>$value){
			$html = str_replace($key,$value,$html);
		}
			
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'potrait');
		$dompdf->render();
	
		//echo $html;exit;
	
		//echo $html;exit;
		$output_directory_path = DOCUMENT_PATH."/student/transcript";
		
		//create directory to locate file
		if (!is_dir($output_directory_path)) {
			mkdir($output_directory_path, 0775,true);
		}
		//output filename 
		$output_filename = "transcript_temp_".$student['registrationId'].".pdf";
				
		//$dompdf = $dompdf->output();
		$dompdf->stream($output_filename);						
							
		//to rename output file						
	    $output_file_path = $output_directory_path.'/'.$output_filename;
		
		file_put_contents($output_file_path, $dompdf);
		
		$this->view->file_path = $output_file_path;
	
		exit;
	
	}
	public function cetakTranscriptOfficialAction(){
		 
	
		$IdStudentRegistration = $this->_getParam('id',null);   
		$idProfile = $this->_getParam('idProfile',null);
		
		 //To get Student Academic Info        
        $studentRegDB = new Graduation_Model_DbTable_Graduation();
        $student = $studentRegDB->getGraduatesNoWis(null,null,$IdStudentRegistration);
		$id = $student['id'];
		
        if($student["majoring"]=="common"||$student["majoring"]=="bersama"){
        	$student["majoring"]="-";
        	$student["majoring_english"]="-";
        }
 		//grade setup
        global $gradeset;
        $dbGrade=new Examination_Model_DbTable_Grade();
        $gradeset=$dbGrade->getGradeProgram( $student['IdProgram'], $student['IdSemesterMain']);

        global $majoring; 
        global $printmajoring;
        $printmajoring=$student['print_majoring'];
        $transcripttemplate=$student['transcript_template'];
        $majoring=$student["majoring"];
         //get photo student
    	$uploadFileDb = new App_Model_Application_DbTable_UploadFile();
    	$file = $uploadFileDb->getFile($student["transaction_id"],51);
    	    	
		if(isset($file["pathupload"])){
    		if (file_exists($file["pathupload"])) {
    			$fnImage = new icampus_Function_General_Image();
    			$photo_url = "http://".ONNAPP_HOSTNAME.$fnImage->getImagePath($file['pathupload'],100,123);	
    			//$photo_url = str_replace("/var/www/html/triapp","http://".ONNAPP_HOSTNAME."/", $file["pathupload"]);
    		}else{
    			$photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
    		}
    	}else{
    		$photo_url = "http://".ONNAPP_HOSTNAME."/images/no-photo.jpg";
    	}
    	

    	//get info college
    	$collegedB = new GeneralSetup_Model_DbTable_Collegemaster();
        $college = $collegedB->getFullInfoCollege($student["idCol"]);
        
				        
    	$studentGradeDB = new Examination_Model_DbTable_StudentGrade();
    	$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
    	
    	$student_grade = $studentGradeDB->getStudentGradeInfo($IdStudentRegistration);
    	$DbProfile = new GeneralSetup_Model_DbTable_TranscriptProfile();
    	$DbProfileDetail = new GeneralSetup_Model_DbTable_TranscriptProfileDetail();
    	//get cgpa info
    	global $grade;
    	$gradeDb = new Examination_Model_DbTable_Academicstatus();
    	$grade = $gradeDb->getListAcademicStatus($student_grade['sg_semesterId'],$student['IdProgram'],$type=1,$basedon='Program');
    	//echo var_dump($grade);
    	//exit;  	
    	//get dean info
    	$deanDB = new GeneralSetup_Model_DbTable_Deanmaster();
    	$dean = $deanDB->getCollegeDean($student['idCol']);
    	
    	
    	//get salutatuion
    	$definationsDb = new App_Model_General_DbTable_Definationms();
    	$FrontSalutation = $definationsDb->getData($dean['FrontSalutation']);
    	$BackSalutation  = $definationsDb->getData($dean['BackSalutation']);
    	
    	//get category and course list
    	global $subject_category;
    	$subject_category=$this->getTranscriptList($IdStudentRegistration,$idProfile);
	
		$dbCommon=new App_Model_Common();
    	$dtYDS = $student['date_of_Yudisium'];
    	//$dtSKR = $student['date_of_skr'];
    	$dtlhr = $student['appl_dob'];
    	$dtYDSLokal = $dbCommon->fnCovertDateToLocalFormat($dtYDS);
    	//$dtSKRLokal = $dbCommon->fnCovertDateToLocalFormat($dtSKR);
    	$dtDobLokal = $dbCommon->fnCovertDateToLocalFormat($dtlhr);
    	$dtYDSEng = $dbCommon->fnCovertDateToEnglishFormat($dtYDS);
    	//$dtSKREng = $dbCommon->fnCovertDateToEnglishFormat($dtSKR);
    	$dtDobEng = $dbCommon->fnCovertDateToEnglishFormat($dtlhr);
    	//Final Assignment
    	 
    	$db = new Finalassignment_Model_DbTable_FinalAssignment();
    	$ta = $db->fnGetFinalAssigmentStd($IdStudentRegistration);
    	//exit;
    	if (isset($ta)) {
    		$title=$ta['Title'];
    		$bahasa=$ta['TitleBahasa'];
    	}else{
    		$title='-';
    		$bahasa='-';
    	}
    	$deanName=$dean['Fullname'];
    	if (isset($FrontSalutation['DefinitionDesc'])) {
    		$deanName=$FrontSalutation['DefinitionDesc'].' '.$deanName;
    	 }
    	if (isset($BackSalutation['DefinitionDesc'])) {
    		$deanName=$deanName.', '.$BackSalutation['DefinitionDesc'];
    	}
    	
    	if ($student["Dept_Bahasa"]=='-') $jurusan=$student["program_name"];
    	else $jurusan=$student["Dept_Bahasa"].' / '.$student["program_name"];
    	if ($student["Departement"]=='-') $jurusanEng=$student["programEng"];
    	else $jurusanEng=$student["Departement"].' / '.$student["programEng"];
    			
    	$fieldValues = array(
			    	  '$[JURUSAN]'=>$jurusan,
    	 			  '$[PROGRAMSTUDI]'=>$student["program_name"],
    				  '$[DEPARTMENT]'=>$jurusanEng,
    	 			  '$[STUDYPROGRAM]'=>$student["programEng"],
			    	  '$[FAKULTAS]'=>$student["CollegeBahasa"],
    	 			  '$[FACULTY]'=> $student["CollegeName"],			    	 
			    	  '$[ADDRESS]'=>ucwords(strtolower($college["Add1"])).' '.ucwords(strtolower($college["Add2"])).' '.ucwords(strtolower($college["CityName"])).' '.ucwords(strtolower($college["StateName"])),
					  '$[PHONE]'=>$college["Phone1"],
					  '$[EMAIL]'=>$college["Email"],
    				  '$[KONSENTRASI]'=>$student["majoring"],
					  '$[MAJORING]'=>$student["majoring_english"],
    				  '$[PROGRAMPENDIDIKAN]'=>$student['program_pendidikan'],
    				  '$[SCHEME]'=>$student["strata"],
					  '$[PROGRAM]'=>$student["program_eng"],
    				  '$[STUDENTNAME]'=>$student["appl_fname"].' '.$student["appl_mname"].' '.$student["appl_lname"],
					  '$[BIRTHDATE]'=>$student["appl_birth_place"].', '.$dtDobLokal,
    				  '$[NIM]'=>$student["registrationId"],
    				  '$[PHOTO]'=>$photo_url,
    				  '$[DEAN]'=>$deanName,
    				  '$[NIKDEAN]'=>$dean['StaffId'].'/USAKTI',
    	 			  '$[TOTALCREDITHOUR]'=>$student_grade['sg_cum_credithour'],	
    	 			  '$[TOTALPOINT]'=>number_format($student_grade['sg_cum_totalpoint'], 2, '.', ''),
			    	  '$[GPA]'=>number_format($student_grade['sg_cgpa'], 2, '.', ''),
			    	  '$[CGPA_STATUS]'=>$student_grade['sg_cgpa_status'],
    				  '$[CGPA_STATUS_ENG]'=>$student_grade['sg_cgpa_status_eng'],
    					'$[TGLYDSM]'=>$dtYDSLokal,
    					'$[TITLE]'=>$title,
    					'$[TITLEBAHASA]'=>$bahasa
		    	   );
		
	    require_once 'dompdf_config.inc.php';
	
		$autoloader = Zend_Loader_Autoloader::getInstance(); // assuming we're in a controller
		$autoloader->pushAutoloader('DOMPDF_autoload');
		
		//template path	 
		if ($student['ProgramCode']=='1220') $html_template_path = DOCUMENT_PATH."/template/transcript_official_s2.html";
		else
		$html_template_path = DOCUMENT_PATH."/template/".$transcripttemplate;
		
		$html = file_get_contents($html_template_path);			
    		
		//replace variable
		foreach ($fieldValues as $key=>$value){
			$html = str_replace($key,$value,$html);	
		}
			
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'potrait');
		$dompdf->render();

		//echo $html;exit;
		$output_directory_path = DOCUMENT_PATH."/student/transcript";
		
		//create directory to locate file
		if (!is_dir($output_directory_path)) {
			mkdir($output_directory_path, 0775,true);
		}
		//output filename 
		$output_filename = "transcript_".$student['registrationId'].".pdf";
				
		$dompdf = $dompdf->output();
		//$dompdf->stream($output_filename);						
							
		//to rename output file						
	    $output_file_path = $output_directory_path.'/'.$output_filename;
		
		file_put_contents($output_file_path, $dompdf);
		
		$this->view->file_path = $output_file_path;
		//save file address
		$db = new Graduation_Model_DbTable_Graduation();
		$db->updateGraduate(array('transcript'=>'/documents/student/transcript/'.$output_filename),$id);
		$this->_redirect($this->view->url(array('module'=>'reports','controller'=>'graduation-report', 'action'=>'ijazah-index', 'program'=>$student['IdProgram'],'semester'=>$student['IdSemesterMain']),'default',true));
		//exit;
		
		//exit;
		
	
	}
	public function transcriptProfileAction(){
		$std = $this->getRequest()->getParam('id');
		$temp=$this->getRequest()->getParam('temp');
		if ($this->getRequest()->isPost()) {
				
			$formData = $this->getRequest()->getPost();
			 
				$profileid = $formData['profile_id'];
				 
				if ($temp=='1') {
					$this->_redirect($this->view->url(array('module'=>'examination','controller'=>'exam-result', 'action'=>'view-temp-transcript', 'id'=>$std,'idProfile'=>$profileid),'default',true));
				}else{
					$this->_redirect($this->view->url(array('module'=>'examination','controller'=>'exam-result', 'action'=>'view-transcript', 'id'=>$std,'idProfile'=>$profileid),'default',true));	
				}
		}
		$Db = new Examination_Model_DbTable_StudentRegistration();
		$stdinfo = $Db->getStudentInfo($std);
		 
		$idProgram = $stdinfo["IdProgram"];
		$idMajor = $stdinfo["IdProgramMajoring"];
		if ($idMajor==null) $idMajor=0;
		$idLandscape = $stdinfo["IdLandscape"];
		$Db = new GeneralSetup_Model_DbTable_TranscriptProfile();
		$profile = $Db->getStdTranscriptProfile($idProgram, $idMajor, $idLandscape);
		 
		$this->view->profile_list = $profile;
	}
	
	public function indexDropGradeAction() {
		
		$form = new Examination_Form_SearchStudent();
		$this->view->title='Search Student';
		$this->view->form=$form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();	
			if (isset($formData['idSemester'])) $formData['IdSemester']=$formData['idSemester'];
			if (isset($formData['student_id'])) $formData['IdStudent']=$formData['student_id'];
			$db = new Registration_Model_DbTable_Studentregistration();
			$student = $db->SearchStudentRegistration($formData);
			$this->view->students =$student;
			
		}
	}
	
	public function dropGradeAction() {
	
		
		$this->view->title = 'Subjects for Official Transcript';
		$idStudentRegistration=$this->getRequest()->getParam('id');
		
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			$dropsubjects=$formData['IdStudentRegistration'];
			//$subjects=$formData['subject'];
			$db = new Examination_Model_DbTable_StudentRegistrationSubject();
			//get all regsiter subject
			$regsubjects=$db->getAllCourseRegistered($idStudentRegistration);
		 
			foreach ($regsubjects as $key=>$subject) {
				$inarray=false;
				foreach ($dropsubjects as $idsubject) {
					if ($idsubject==$subject['IdSubject']) $inarray=true;
				}
				if ($inarray) {
					$db->dropGrade(array('exam_status'=>'C'), $idStudentRegistration, $subject['IdSubject']);
					unset($regsubjects[$key]);
				}
			}
			//echo var_dump($regsubjects);exit;
			foreach ($regsubjects as $subject) {
				//drop Course
				
				$db->dropGrade(array('exam_status'=>'DR'), $idStudentRegistration, $subject['IdSubject']);
			}
			//exit;
			//$db = new Registration_Model_DbTable_Studentregistration();
			//$student = $db->SearchStudent($formData);
			//$this->view->students =$student;
			
		}
		$this->view->id=$idStudentRegistration;
		
		$this->view->subjects = $this->getTranscriptListAll($idStudentRegistration);
	  
	    
	}
	public function getTranscriptList($idStudentRegistration,$idProfile=null) {
		//get student profile
		$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
		$DbProfileDetail = new App_Model_General_DbTable_TranscriptProfileDetail();
		$dbStudent = new App_Model_General_DbTable_Studentregistration();
		$student = $dbStudent->SearchStudentRegistration(array('IdStudentRegistration'=>$idStudentRegistration));
		 
		 if ($idProfile==0 ) {
			
			$idLandscape = $student['IdLandscape'];
			$idProgram = $student['IdProgram'];
			$idMajor = $student['IdProgramMajoring'];
			//echo var_dump($student);
			//exit;
			//transcript profile
			$DbProfile = new App_Model_General_DbTable_TranscriptProfile();
			$DbProfileDetail = new App_Model_General_DbTable_TranscriptProfileDetail();
			$idProfile = $DbProfile->getStdTranscriptProfile($idProgram, $idMajor, $idLandscape);
			//echo var_dump($idProfile);exit;
			if ($idProfile==array()) $idProfile='*'; else $idProfile=$idProfile[0]['IdProfile'];
		}
		//get category and course list
		//echo var_dump($idProfile);exit;
		//}
		if ($idProfile=='*') {
		
			$dbLands = new App_Model_General_DbTable_Landscapesubject();
			$dbBlock= new  App_Model_General_DbTable_LandscapeBlockSubject();
			$dbProgReq = new App_Model_General_DbTable_Programrequirement();
			$subject_category = $dbProgReq->getlandscapecoursetype($student['IdProgram'], $student['IdLandscape']);
		
			foreach($subject_category as $index=>$category){
				$subject_list = $dbLands->getlandscapesubjectsPerCategory($student['IdLandscape'],$category["SubjectType"]);
				if ($subject_list==array()) $subject_list = $dbBlock->getLandscapeCoursePerCategory($student['IdLandscape'],$category["SubjectType"]);
				unset($subjecthighest);
				foreach ($subject_list as $key=>$subject) {
					$subject=$regSubjectDB->getHighestMarkofAllSemester($idStudentRegistration, $subject['IdSubject']);
					if (!is_bool($subject)) $subjecthighest[$key] = $subject;
				}
				if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
				else unset($subject_category[$index]);
				//echo var_dump($subject_category);
				//exit;
			}
		
		}
		else
		{
		
			$subject_category = $DbProfileDetail->fnGetTranscriptProfileName($idProfile);
			//echo var_dump($subject_category);echo '--'.$idProfile;exit;
			foreach($subject_category as $index=>$category){
				$subjecthighest=array();
				$subject_list = $DbProfileDetail->fnGetTranscriptProfileSubject($idProfile,$category['idGroup']);
				//echo var_dump($subject_list);exit;
				unset($subjecthighest);
				foreach($subject_list as $key=>$subject) :
					$subject=$regSubjectDB->getHighestMarkofAllSemesterProfile($idStudentRegistration, $subject['idSubject'],$idProfile);
					if (!is_bool($subject)) $subjecthighest[$key] = $subject;
				endforeach;
				if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
				else unset($subject_category[$index]);
				 
			}
		}
		//echo var_dump($subject_category);
		//exit;
		return $subject_category;
	}
	public function getTranscriptListAll($idStudentRegistration,$idProfile=null) {
		//get student profile
		$regSubjectDB = new Examination_Model_DbTable_StudentRegistrationSubject();
		$DbProfileDetail = new GeneralSetup_Model_DbTable_TranscriptProfileDetail();
		$dbStudent = new Registration_Model_DbTable_Studentregistration();
		$student = $dbStudent->SearchStudentRegistration(array('IdStudentRegistration'=>$idStudentRegistration));
		 //echo var_dump($student);exit;
		//$student=$student[0];
		$idProfile=$student['idTranscriptProfile'];
		if ($idProfile==0) {
			if ($idProfile==null) {
					
				$idLandscape = $student['IdLandscape'];
				$idProgram = $student['IdProgram'];
				$idMajor = $student['IdProgramMajoring'];
				//echo var_dump($student);
				//exit;
				//transcript profile
				$DbProfile = new GeneralSetup_Model_DbTable_TranscriptProfile();
				$DbProfileDetail = new GeneralSetup_Model_DbTable_TranscriptProfileDetail();
				$idProfile = $DbProfile->getStdTranscriptProfile($idProgram, $idMajor, $idLandscape);
				//echo var_dump($idProfile);exit;
				if ($idProfile==array()) $idProfile='*'; else $idProfile=$idProfile[0]['IdProfile'];
			}
		}
		//get category and course list
		//echo var_dump($idProfile);exit;
	
		if ($idProfile=='*') {
	
			$dbLands = new GeneralSetup_Model_DbTable_Landscapesubject();
			$dbBlock= new GeneralSetup_Model_DbTable_LandscapeBlockSubject();
			$dbProgReq = new GeneralSetup_Model_DbTable_Programrequirement();
			$subject_category = $dbProgReq->getlandscapecoursetype($student['IdProgram'], $student['IdLandscape']);
	
			foreach($subject_category as $index=>$category){
				$subject_list = $dbLands->getlandscapesubjectsPerCategory($student['IdLandscape'],$category["SubjectType"]);
				if ($subject_list==array()) $subject_list = $dbBlock->getLandscapeCoursePerCategory($student['IdLandscape'],$category["SubjectType"]);
				unset($subjecthighest);
				foreach ($subject_list as $key=>$subject) {
					$subject=$regSubjectDB->getHighestMarkofAllSemesterWithDrop($idStudentRegistration, $subject['IdSubject']);
					if (!is_bool($subject)) $subjecthighest[$key] = $subject;
				}
				if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
				else unset($subject_category[$index]);
				 
			}
		}
		else
		{
			$subject_category = $DbProfileDetail->fnGetTranscriptProfileName($idProfile);
			//echo var_dump($subject_category);echo '--'.$idProfile;exit;
			foreach($subject_category as $index=>$category){
				$subjecthighest=array();
				$subject_list = $DbProfileDetail->fnGetTranscriptProfileSubject($idProfile,$category['idGroup']);
				//echo var_dump($subject_list);exit;
				unset($subjecthighest);
				foreach($subject_list as $key=>$subject) :
					$subject=$regSubjectDB->getHighestMarkofAllSemesterProfileWithDrop($idStudentRegistration, $subject['idSubject'],$idProfile);
					if (!is_bool($subject)) $subjecthighest[$key] = $subject;
				endforeach;
				if (isset($subjecthighest)) $subject_category[$index]["subjects"] = $subjecthighest;
				else unset($subject_category[$index]);
					
			}
		}
		 
		return $subject_category;
	}
	public function generateCGPA($idStudentRegistration,$idProfile=null) {
	
		$subject_category = $this->getTranscriptList($idStudentRegistration,$idProfile);
		
		$cgpa=0;
		$TotalCH=0;
		$TotalCPoint=0;
		foreach ($subject_category as $subjectcat) {
			$subjects = $subjectcat['subjects'];
			foreach ($subjects as $subject) {
				$TotalCH=$TotalCH+$subject['CreditHours'];
				$TotalCPoint = $TotalCPoint +  $subject['CreditHours']*$subject['grade_point'];
			}
		}
		if ($TotalCH>0) $cgpa = $TotalCPoint/$TotalCH; else $cgpa =0;
		$result = array('cgpa'=>$cgpa,'TotalCH'=>$TotalCH,'TotalCPoint'=>$TotalCPoint);
		return $result;
	}
	
	public function generateCgpaAction(){
		$idStudentRegistration = $this->getRequest()->getParam('id');
		$idProfile = $this->getRequest()->getParam('idProfile');
		$cgpas=$this->generateCGPA($idStudentRegistration,$idProfile);
		//get semester cgpa
		$db = new Graduation_Model_DbTable_Graduation();
		$student = $db->getGraduatesNoWis(null,null,$idStudentRegistration);
		//echo var_dump($student); exit;
		$semester = $student['IdSemesterMain'];
		//update cgpa graduate
		$db = new Registration_Model_DbTable_Studentsemesterstatus();
		$sem = $db->isRegisteredSemesterStatus($idStudentRegistration, $semester);
		$dbGrade = new Examination_Model_DbTable_StudentGrade();
		 
		if (!$sem) {
			//isenrt semester status
			$levels =$db->getIdPrevSemesterStatus($idStudentRegistration,null);
			$level=$levels['Level'];
			 
			$level++;
			$idsemesterstatus=$db->addData(array('IdStudentRegistration'=>$idStudentRegistration,'IdSemesterMain'=>$semester,'Level'=>$level,'idSemester'=>0));
			$dbGrade->addData(array('sg_idstudentsemsterstatus'=>$idsemesterstatus,'sg_IdStudentRegistration'=>$idStudentRegistration,'sg_semesterid'=>$semester));
		} else 	$idsemesterstatus= $sem['idstudentsemsterstatus'];
		
		$filters = array('IdStudentRegistration'=>$idStudentRegistration,'IdSemester'=>$semester,'idsemesterstatus'=>$idsemesterstatus);
		$data =array('sg_cum_credithour'=>$cgpas['TotalCH'],'sg_cum_totalpoint'=>$cgpas['TotalCPoint'],'sg_cgpa'=>$cgpas['cgpa']);
		 
		$dbGrade->updateStudentGrade($data, $filters);
		$this->_redirect($this->view->url(array('module'=>'examination','controller'=>'exam-result', 'action'=>'view-transcript','id'=>$idStudentRegistration,'idProfile'=>$idProfile),'default',true));
	}
}
?>