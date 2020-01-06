<?php

class Examination_Form_ExamGradeSearchForm extends Zend_Form
{
		
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','form1');
		
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
			'label'=>$this->getView()->translate('Program'),
		    'required'=>true
		));
		
		$programDb = new Registration_Model_DbTable_Program();
		
		$this->IdProgram->addMultiOption(null,"-- Select Program --");		
		foreach($programDb->getData() as $program){
			$this->IdProgram->addMultiOption($program["IdProgram"],$program["ProgramCode"].' - '.$program["ArabicName"]);
		}		
		
		
		
	    //Semester
		$this->addElement('select','IdSemester', array(
			'label'=>$this->getView()->translate('Semester'),	
		    'required'=>true
		));
		
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemester->addMultiOption(null,"-- Select Semester --");		
		foreach($semesterDB->fnGetSemestermasterList() as $semester){
			$this->IdSemester->addMultiOption($semester["key"],$semester["value"]);
		}
		
		
		//GET Status
		$this->addElement('select','idProfileStatus', array(
			'label'=>$this->getView()->translate('Profile Status')
		));
		
		$profileStatusDB = new App_Model_General_DbTable_Definationms();
		$profilestatus = $profileStatusDB->getDataByType(20);
		
		$this->idProfileStatus->addMultiOption(null,"-- All --");		
		foreach($profilestatus as $status){
			$this->idProfileStatus->addMultiOption($status["idDefinition"],$status["DefinitionDesc"]);
		}	
				
		
		
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