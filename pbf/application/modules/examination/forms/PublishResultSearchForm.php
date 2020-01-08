<?php

class Examination_Form_PublishResultSearchForm extends Zend_Form
{
		
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','formSearch');
		
		 //Semester
		$this->addElement('select','IdSemester', array(
			'label'=>$this->getView()->translate('Semester'),	
		    'required'=>true
		));
		
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemester->addMultiOption(null,"-- Select Semester --");		
		foreach($semesterDB->fnGetSemestermasterListNoCheck() as $semester){
			$this->IdSemester->addMultiOption($semester["key"],$semester["value"]);
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