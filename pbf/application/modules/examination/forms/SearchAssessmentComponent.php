<?php 

class Examination_Form_SearchAssessmentComponent extends Zend_Form
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
		
	    //Semester
		$this->addElement('select','IdSemester', array(
			'label'=>$this->getView()->translate('Semester'),	
		    'required'=>true
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
		
		$programDb = new Registration_Model_DbTable_Program();
		
		$this->IdProgram->addMultiOption(null,"-- Please Select --");		
		foreach($programDb->getData() as $program){
			$this->IdProgram->addMultiOption($program["IdProgram"],$program["ProgramCode"].' - '.$program["ArabicName"]);
		}	

		//IdBranch
		
		//Program
		$this->addElement('select','IdBranch', array(
				'label'=>$this->getView()->translate('Branch'),
				 
		));
		$this->IdBranch->addMultiOption(null,"-- Please Select --");
		$programDb = new GeneralSetup_Model_DbTable_Branchofficevenue();
		 
		foreach($programDb->fnGetAllBranchList() as $program){
			$this->IdBranch->addMultiOption($program["key"],$program["value"]);
		}
		//Subject Code
		$this->addElement('text','subject_code', array(
			'label'=>$this->getView()->translate('Subject Code')
		));	

		//Subject Name
		$this->addElement('text','subject_name', array(
			'label'=>$this->getView()->translate('Subject Name')
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