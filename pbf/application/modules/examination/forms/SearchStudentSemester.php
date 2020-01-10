<?php 

class Examination_Form_SearchStudentSemester extends Zend_Form
{
		
		
	public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','form_student_semester');
						       
		
		
		//Program
		$this->addElement('select','IdProgram', array(
			'label'=>$this->getView()->translate('Program Name'),
		    'required'=>false
		));
		
		$programDb = new Registration_Model_DbTable_Program();
		
		$this->IdProgram->addMultiOption(null,"-- All --");		
		foreach($programDb->getData()  as $program){
			$this->IdProgram->addMultiOption($program["IdProgram"],$program["ProgramCode"].' - '.$program["ArabicName"]);
		}
				
		
		//Semester
		$this->addElement('select','semester', array(
			'label'=>$this->getView()->translate('Semester'),
		    'required'=>true
		));
		
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		$semesterList = $semesterDB->fnGetSemestermasterList();
		
		$this->semester->addMultiOption(null,"Please select");		
		foreach($semesterList as $semester){
			$this->semester->addMultiOption($semester["key"],$semester["value"]);
		}
		
		//exam type
		$this->addElement('select','exam_type', array(
				'label'=>$this->getView()->translate('Exam Type'),
				'required'=>true
		));
		
		$assessmentTypeDb = new Examination_Model_DbTable_Assessmenttype();
		$assessmentList = $assessmentTypeDb->getdropdownforasseementtype();
		
		$this->exam_type->addMultiOption(null,"Please select");
		foreach($assessmentList as $assessment){
			$this->exam_type->addMultiOption($assessment["key"],$assessment["value"]);
		}
		
				
	
		//Applicant Name
		$this->addElement('text','applicant_name', array(
			'label'=>$this->getView()->translate('Student Name')
		));
		
		//Student ID
		$this->addElement('text','student_id', array(
			'label'=>$this->getView()->translate('Student ID')
		));
				
		//button
		$this->addElement('submit', 'Search', array(
          'label'=>$this->getView()->translate('Search'),
          'decorators'=>array('ViewHelper')
        ));
        
        
        $this->addDisplayGroup(array('Search'),'buttons', array(
	      'decorators'=>array(
	        'FormElements',
	        array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
	        'DtDdWrapper'
	      )
	    ));
        	    
		
        		
	}
	
	
}
?>