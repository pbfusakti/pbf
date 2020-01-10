<?php
class Examination_Form_AnswerSchemaUpload extends Zend_Form {

	protected $idSemesterx;
	protected $idProgramx;
	protected $idSubjectx;
	protected $idMaster;
	protected $idDetail;

	public function setIdMaster($idMaster){
		$this->idMaster = $idMaster;
	}
	
	public function setIdDetail($idDetail){
		$this->idDetail = $idDetail;
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
		$this->setAttrib('action', $this->getView()->url(array('module'=>'examination', 'controller'=>'answer-schema','action'=>'upload-schema'),'default',true) );

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
		
		$element = new Zend_Form_Element_Hidden('sas_IdMarksDistributionMaster');
		$element->setValue($this->idMaster);
		$this->addElement($element);
		
		$elementDetail = new Zend_Form_Element_Hidden('sas_IdMarksDistributionDetails');
		$elementDetail->setValue($this->idDetail);
		$this->addElement($elementDetail);

		$questionNumber = new Zend_Form_Element_Text('sas_total_quest');
        $questionNumber->setLabel('Total Question Number')
                  ->setRequired(true)
                  ->addValidator('NotEmpty')
                  ->addValidator('Digits');
		$this->addElement($questionNumber);
                  
        $file = new Zend_Form_Element_File('file');
        $file->setLabel('File')
            ->setDestination(APPLICATION_PATH  . '/tmp')
            ->setRequired(true);
		$this->addElement($file);

        //$this->addElements(array($hiddenElement, $questionNumber,$file));


    }

}