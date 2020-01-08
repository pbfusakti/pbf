<?php

class Examination_Form_PublishSlipSearchForm extends Zend_Form
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
		$dbtype=new Examination_Model_DbTable_Assessmenttype();
		$assess=$dbtype->getdropdownforasseementtype();
		
		$this->addElement('select','idAssess', array(
				'label'=>$this->getView()->translate('Assessment')
				 
		));
		$this->idAssess->addMultiOption(null,"-- All --");
		foreach($assess as $program){
			$this->idAssess->addMultiOption($program["key"],$program["value"]);
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