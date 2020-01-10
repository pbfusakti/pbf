<?php
class Examination_Form_AnswerSheetGroupUpload extends Zend_Form {
	
	protected $idSemesterx;	
	protected $idSubjectx;
	protected $id;
	//protected $idexam;
	

	
	public function setIdSemesterx($idSemesterx){
		$this->idSemesterx = $idSemesterx;
	}	
	
	public function setIdSubjectx($idSubjectx){
		$this->idSubjectx = $idSubjectx;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	//public function setIdexam($idexam){
	//	$this->idexam = $idexam;
	//}
	
	
	public function init()
	{
        //parent::__construct($options);

        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('action', $this->getView()->url(array('module'=>'examination', 'controller'=>'answer-sheet-group','action'=>'upload-omr'),'default',true) );
          
			/*** hidden element ***/
		
		$Semester = new Zend_Form_Element_Hidden('idSemester');
		$Semester->setValue($this->idSemesterx);
		$this->addElement($Semester);	
		
		$Subject = new Zend_Form_Element_Hidden('idSubject');
		$Subject->setValue($this->idSubjectx);
		$this->addElement($Subject);
		
		$Group = new Zend_Form_Element_Hidden('idGroup');
		$Group->setValue($this->id);
		$this->addElement($Group);
		
		//$Group = new Zend_Form_Element_Hidden('idexam');
		//$Group->setValue($this->idexam);
		//$this->addElement($Group);
			
		$questionNumber = new Zend_Form_Element_Text('total_quest');
        $questionNumber->setLabel('Total Question Number (Default)')
                  ->setRequired(true)
                  ->addValidator('NotEmpty')
                  ->addValidator('Digits');
		$this->addElement($questionNumber);
		

		//mark distribution
		$this->addElement('select','IdMarksDistributionMaster', array(
				'label'=>$this->getView()->translate('Mark Component'),
				'required'=>true,
				'onchange'=>'javascript:getSemester();'
		));
		
		$programDb = new Examination_Model_DbTable_Marksdistributionmaster();
		
		$this->IdMarksDistributionMaster->addMultiOption(null,"-- Please Select --");
		foreach($programDb->fngetmarksdistrsubject($this->idSemesterx, $this->idSubjectx)  as $program){
			$this->IdMarksDistributionMaster->addMultiOption($program["IdMarksDistributionMaster"],$program['component_name'],$program["ProgramCode"].' - '.$program["ProgramName"]);
		}
		
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')
            ->setDestination(APPLICATION_PATH  . '/tmp')
            ->setRequired(true);
		$this->addElement($file);
		
		
		
	
    }
}