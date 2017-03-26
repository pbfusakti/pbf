<?php
class Examination_Form_AnswerSheetUpload extends Zend_Form {
	protected $idSemesterx;
	protected $idProgramx;
	protected $idSubjectx;
	protected $idMasterx;
	protected $idDetailx;

	public function setIdMasterx($idMasterx){
		$this->idMasterx = $idMasterx;
	}
	
	public function setIdDetail($idDetailx){
		$this->idDetailx = $idDetailx;
	}
	
	public function setIdSemesterx($idSemesterx){
		$this->idSemesterx = $idSemesterx;
	}
	
	public function setIdProgramx($idProgramx){
		$this->idProgramx = $idProgramx;
	}
	
	public function setIdSubjectx($idSubjectx){
		$this->idSubjectx = $idSubjectx;
	}
	
	public function init()
	{
        //parent::__construct($options);

        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('action', $this->getView()->url(array('module'=>'examination', 'controller'=>'answer-sheet','action'=>'upload-omr'),'default',true) );
          
			/*** hidden element ***/
		
		$Semester = new Zend_Form_Element_Hidden('idSemester');
		$Semester->setValue($this->idSemesterx);
		$this->addElement($Semester);
		
		$Program = new Zend_Form_Element_Hidden('idProgram');
		$Program->setValue($this->idProgramx);
		$this->addElement($Program);		
		
		$Subject = new Zend_Form_Element_Hidden('idSubject');
		$Subject->setValue($this->idSubjectx);
		$this->addElement($Subject);
		
		$element = new Zend_Form_Element_Hidden('idMaster');
		$element->setValue($this->idMasterx);
		$this->addElement($element);
		
		$elementDetail = new Zend_Form_Element_Hidden('idDetail');
		$elementDetail->setValue($this->idDetailx);
		$this->addElement($elementDetail);
		
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')
            ->setDestination(APPLICATION_PATH  . '/tmp')
            ->setRequired(true);
		$this->addElement($file);
		
		
		
	
    }
}