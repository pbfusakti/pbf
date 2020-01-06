<?php 

class Examination_Form_CopyMark extends Zend_Form
{
		
	
public function init()
	{
						
		$this->setMethod('post');
		$this->setAttrib('id','myform');
		
		 //Type
		$this->addElement('select','type', array(
			'label'=>$this->getView()->translate('Type'),	
		    'required'=>true
		));
		
		$this->type->addMultiOption('1','Program & Subject');
		
	   
		
		//Program
		$this->addElement('select','IdProgram', array(
			'label'=>$this->getView()->translate('Program Name'),
		    'required'=>true
		));
		
		$programDb = new Registration_Model_DbTable_Program();
		
		$this->IdProgram->addMultiOption(null,"-- Please Select --");		
		foreach($programDb->getData() as $program){
			$this->IdProgram->addMultiOption($program["IdProgram"],$program["ProgramCode"].' - '.$program["ArabicName"]);
		}	
			
		//subject
		/*
		$this->addElement('select','IdSubject', array(
				'label'=>$this->getView()->translate('Subject Name'),
				'required'=>true
		));
		
		$programDb = new GeneralSetup_Model_DbTable_Subjectmaster();
		
		$this->IdSubject->addMultiOption(null,"-- Please Select --");
		foreach($programDb->fnGetSubjectMasterList() as $program){
			$this->IdSubject->addMultiOption($program["IdSubject"],$program["BahasaIndonesia"]);
		}
			*/
		//Semester Source
		$this->addElement('select','IdSemesterSource', array(
				'label'=>$this->getView()->translate('Semester Source'),
				'required'=>true
		));
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemesterSource->addMultiOption(null,"-- Please Select --");
		foreach($semesterDB->fnGetSemestermasterList() as $semester){
			$this->IdSemesterSource->addMultiOption($semester["key"],$semester["value"]);
		}
		
		//Semester Dest
		$this->addElement('select','IdSemesterDest', array(
				'label'=>$this->getView()->translate('Semester Destination'),
				'required'=>true
		));
		$semesterDB = new GeneralSetup_Model_DbTable_Semestermaster();
		
		$this->IdSemesterDest->addMultiOption(null,"-- Please Select --");
		foreach($semesterDB->getUnCountableSemester() as $semester){
			$this->IdSemesterDest->addMultiOption($semester["key"],$semester["value"]);
		}
		
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