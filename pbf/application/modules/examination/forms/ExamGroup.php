<?php

class Examination_Form_ExamGroup extends Zend_Form
{
	protected $subject_id;
	protected $semester_id;
	protected $program_id;
	
	
	public function setSubject_id($subject_id){
		$this->subject_id = $subject_id;
	}
	public function setSemester_id($semester_id){
		$this->semester_id = $semester_id;
	}
	
	public function setProgram_id ($program_id){
		$this->program_id = $program_id;
	}
	
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','form_exam_group');
		
		$this->addElement('hidden','eg_sem_id');
		$this->eg_sem_id->setValue($this->semester_id);
		
		$this->addElement('hidden','eg_sub_id' );
		$this->eg_sub_id->setValue($this->subject_id);
		
		$this->addElement('hidden','eg_prog_id');
		$this->eg_prog_id->setValue($this->program_id);
		
		
		//semester
		$this->addElement('text','semester', 
			array(
				'label'=>'Semester',
				'readonly' => 'true'
			)
		);
		$semesterDb = new GeneralSetup_Model_DbTable_Semestermaster();
		$sem = $semesterDb->fnGetSemestermaster($this->semester_id);
		$this->semester->setValue($sem['SemesterMainDefaultLanguage']);
		
		//subject
		$this->addElement('text','subject', 
			array(
				'label'=>'Subject',
				'readonly' => 'true'
			)
		);
		$subjectDb =  new GeneralSetup_Model_DbTable_Subjectmaster();
		$subject = $subjectDb->getData($this->subject_id);
		$this->subject->setValue($subject['BahasaIndonesia']);
		
		//subject code
		$this->addElement('text','subject_code',
				array(
						'label'=>'Subject Code',
						'readonly' => 'true'
				)
		);
		$this->subject_code->setValue($subject['SubCode']);
		
		//assessment type
		$this->addElement('select','eg_assessment_type', array(
				'label'=>'Assessment Type',
				'required'=>'true'
		));
		
		$assessmentTypeDb = new Examination_Model_DbTable_Assessmenttype();
		$assessment_list = $assessmentTypeDb->fngetassessment();
		
		$this->eg_assessment_type->addMultiOption(null,'Please select');
		foreach ($assessment_list as $assessment){
			$this->eg_assessment_type->addMultiOption($assessment['IdExaminationAssessmentType'],$assessment['DescriptionDefaultlang']);
		}
		
		//repeat or regular
		$this->addElement('select','eg_repeat_status', array(
				'label'=>'Examination Type',
				'required'=>'true'
		));
		
		$this->eg_repeat_status->addMultiOption(0,'Normal');
		$this->eg_repeat_status->addMultiOption(1,'Repeat Examination');
		
		
		
		$this->addElement('text','eg_group_name', 
			array(
				'label'=>'Group Name',
				'required'=>'true'
			)
		);
		
		$this->addElement('text','eg_group_code',
				array(
						'label'=>'Group Code',
						'required'=>'true'
				)
		);
		
		$this->addElement('text','eg_date',
				array(
						'label'=>'Exam Date',
						'required'=>'true',
						'placeholder'=>'dd-mm-yyyy'
				)
		);
		
		$this->addElement('text','eg_start_time',
				array(
						'label'=>'Start Time',
						'required'=>'true',
						'placeholder'=>'HH24:MM'
				)
		);
		
		$this->addElement('text','eg_end_time',
				array(
						'label'=>'End Time',
						'required'=>'true',
						'placeholder'=>'HH24:MM'
				)
		);
		
		
		//location
		$this->addElement('select','place', array(
				'label'=>'Exam Location',
				'onChange'=>'getRoom(this)'
		));
		$locationDb = new App_Model_Application_DbTable_PlacementTestLocation();
		$locations = $locationDb->getData();
		$this->place->addMultiOption(null,'Please select');
		foreach ($locations as $location){
			$this->place->addMultiOption($location['al_id'],$location['al_location_name']);
		}
		
		//room
		$this->addElement('select','eg_room_id', array(
			'label'=>'Exam Room',
		    'required'=>true,
			'onChange'=>'getCapacity(this)'
		));
		$roomDb = new App_Model_Application_DbTable_PlacementTestRoom();
		$rooms = $roomDb->getData();

		$this->eg_room_id->addMultiOption(null,'Please select');
		foreach ($rooms as $room){
			$this->eg_room_id->addMultiOption($room['av_id'],$room['av_room_name_short']." (".$room['av_room_code'].")");
		}
		
		//capacity
		$this->addElement('text','eg_capacity',
				array(
						'label'=>'Capacity',
						'required'=>'true'
				)
		);
		$this->eg_capacity->addValidator('Digits');
		
		
		//program
		$table = '<table id="program" class="table" width="500px"><thead><tr><th>Program</th><th width="30px">Action</th></tr></thead><tbody></tbody><tfoot><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tfoot></table>';
		$this->addElement('hidden', 'program_list', array(
		    'label' => 'Program',
		    'ignore' => true,
		    'description' => '<a href="#" class="btn" id="assign-program">Add Program</a><br />'.$table
		));
		
		$this->program_list
		->setDecorators(array(
		    'ViewHelper',
		    array('Description', array('escape' => false, 'tag' => false)),
		    array('HtmlTag', array('tag' => 'dd')),
		    array('Label', array('tag' => 'dt')),
		    'Errors',
		));
		
		
		
		//button
		$this->addElement('submit', 'save', array(
				'label'=>'Submit',
				'decorators'=>array('ViewHelper')
		));
		
		$this->addElement('submit', 'cancel', array(
				'label'=>'Cancel',
				'decorators'=>array('ViewHelper'),
				'onClick'=>"window.location ='" . $this->getView()->url(array('module'=>'examination', 'controller'=>'exam-grouping','action'=>'group','semid'=>$this->semester_id,'facid'=>$this->program_id,'subid'=>$this->subject_id),'default',true) . "'; return false;"
		));
		
		$this->addDisplayGroup(array('save','cancel'),'buttons', array(
				'decorators'=>array(
						'FormElements',
						array('HtmlTag', array('tag'=>'div', 'class'=>'buttons')),
						'DtDdWrapper'
				)
		));

		
	}
}
?>