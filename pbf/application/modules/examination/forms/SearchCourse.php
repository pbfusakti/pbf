<?php 

class Examination_Form_SearchCourse extends Zend_Form
{
		
		
	public function init()
	{
		$session = new Zend_Session_Namespace('sis');
		$this->setMethod('post');
		$this->setAttrib('id','myform');
						       
		//Intake
		$this->addElement('select','IdSemester', array(
			'label'=>$this->getView()->translate('Semester'),
		    'required'=>true,
		    'onchange'=>'checkOpenRegister(this.value)'
		));
		
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemester->addMultiOption(null,"-- Please Select --");		
		foreach($semesterDB->fnGetSemestermasterList() as $semester){
			$this->IdSemester->addMultiOption($semester["key"],$semester["value"]);
		}
		//Program
			$this->addElement('select','IdProgram', array(
				'label'=>$this->getView()->translate('Program Name'), 
				'required'=>true
			));
			
			$collegeDb = new GeneralSetup_Model_DbTable_Program();
			
			$this->IdProgram->addMultiOption(null,"-- Please Select --");		
			foreach($collegeDb->getData()  as $college){
				$this->IdProgram->addMultiOption($college["IdProgram"],$college["ProgramName"].' - '.$college["ArabicName"]);
			}		
		

		$this->addElement('text','SubjectName', array(
			'label'=>'Subject Name'
		));
		
		//name
		$this->addElement('text','SubjectCode', array(
			'label'=>'Subject Code'
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