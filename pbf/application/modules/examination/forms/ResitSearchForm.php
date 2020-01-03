<?php

class Examination_Form_ResitSearchForm extends Zend_Form
{
		
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','SearchForm');
		
		//intake
		$this->addElement('select','idIntake', array(
			'label'=>'Intake'
		));
		
		$intakeDb = new App_Model_Record_DbTable_Intake();
		$intakeList = $intakeDb->getData();		
    			
		$this->idIntake->addMultiOption(null,"-- All --");
		foreach ($intakeList as $list){
			$this->idIntake->addMultiOption($list['IdIntake'],$list['IntakeDefaultLanguage']);
		}
		
	  	//Program
		$this->addElement('select','IdProgram', array(
			'label'=>$this->getView()->translate('Program')
		));
		
		$programDb = new Registration_Model_DbTable_Program();
		
		$this->IdProgram->addMultiOption(null,"-- All --");		
		foreach($programDb->getData() as $program){
			$this->IdProgram->addMultiOption($program["IdProgram"],$program["ProgramCode"].' - '.$program["ArabicName"]);
		}		
		
	    //Semester
		$this->addElement('select','IdSemester', array(
			'label'=>$this->getView()->translate('Semester')
		));
		
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemester->addMultiOption(null,"-- All --");		
		foreach($semesterDB->fnGetSemestermasterList() as $semester){
			$this->IdSemester->addMultiOption($semester["key"],$semester["value"]);
		}					
		
		 //Status
		$this->addElement('select','status', array(
			'label'=>$this->getView()->translate('Status')
		));
		
		$this->status->addMultiOption(null,"-- All --");
		$this->status->addMultiOption(1,"Apply");
		$this->status->addMultiOption(2,"Approved");
		$this->status->addMultiOption(3,"Rejected");
		
		//Student Name/NIM
		$this->addElement('text','Student', array(
			'label'=>$this->getView()->translate('Student Name/NIM')
		));	
		
		
		
		//button
		$this->addElement('submit', 'save', array(
				'label'=>'Search',
				'decorators'=>array('ViewHelper')
		));
		
	
		
		$this->addDisplayGroup(array('save'),'buttons', array(
				'decorators'=>array(
						'FormElements',
						array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
						'DtDdWrapper'
				)
		));

		
	}
}
?>