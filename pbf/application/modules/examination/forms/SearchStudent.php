<?php 

class Examination_Form_SearchStudent extends Zend_Form
{
		
		
	public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','myform');
						       
		
		
		//Program
		$this->addElement('select','IdProgram', array(
			'label'=>$this->getView()->translate('Program Name'),
		    'required'=>true,
		    'onchange'=>'javascript:getSemester();'
		));
		
		$programDb = new Registration_Model_DbTable_Program();
		
		$this->IdProgram->addMultiOption(null,"-- Please Select --");		
		foreach($programDb->getData()  as $program){
			$this->IdProgram->addMultiOption($program["IdProgram"],$program["ProgramCode"].' - '.$program["ArabicName"]);
		}
				
		
		//Intake
		$this->addElement('select','IdIntake', array(
			'label'=>$this->getView()->translate('Intake'),
		    'required'=>true,
		    'onchange'=>'javascript:getSemester();'
		));
		
		$intakeDB = new App_Model_Record_DbTable_Intake();
		
		$this->IdIntake->addMultiOption(null,"-- Please Select --");		
		foreach($intakeDB->fngetlatestintake() as $intake){
			$this->IdIntake->addMultiOption($intake["key"],$intake["value"]);
		}

		
		
		//Semester
		$this->addElement('select','idSemester', array(
			'label'=>$this->getView()->translate('Semester'),
		    'required'=>true,
		    'registerInArrayValidator' => false,
		    'onchange'=>'javascript:getCourse();'
		));
		
		
		//Course
		$this->addElement('select','idSubject', array(
			'label'=>$this->getView()->translate('Subject'),
		    'required'=>true,
		    'registerInArrayValidator' => false
		));
		
		
		
				
	
		//Applicant Name
		$this->addElement('text','student_name', array(
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