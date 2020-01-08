<?php

class Examination_Form_ExamScriptVerify extends Zend_Form
{
	protected $subject_id;
	protected $semester_id;
	protected $program_id;
	protected $examtype;
	protected $path;
	protected $script_id;
	protected $distribution_id;
	protected $group_id;
	
	public function setSubject_id($subject_id){
		$this->subject_id = $subject_id;
	}
	public function setSemester_id($semester_id){
		$this->semester_id = $semester_id;
	}
	
	public function setPath($path){
		$this->path = $path;
	}
	
	public function setProgram_id ($program_id){
		$this->program_id = $program_id;
	}
	
	public function setExamType($examtype){
		$this->examtype = $examtype;
	}
	
	public function setScript_Id($script_id){
		$this->script_id = $script_id;
	}
	
	public function setDistribution_Id($distribution_id){
		$this->distribution_id = $distribution_id;
	}
	
	public function setGroup_Id($group_id){
		$this->group_id = $group_id;
	}
	
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id','form_exam_script');
		
		$this->addElement('hidden','IdSemester');
		$this->IdSemester->setValue($this->semester_id);
		
		$this->addElement('hidden','IdSubject' );
		$this->IdSubject->setValue($this->subject_id);
		
		$this->addElement('hidden','IdProgram');
		$this->IdProgram->setValue($this->program_id);
		
		$this->addElement('hidden','ExamType');
		$this->ExamType->setValue($this->examtype);
		
		$this->addElement('hidden','IdScript');
		$this->IdScript->setValue($this->script_id);
		
		$this->addElement('hidden','IdDistributionMaster');
		$this->IdDistributionMaster->setValue($this->distribution_id);
		
		$this->addElement('hidden','IdGroup');
		$this->IdGroup->setValue($this->group_id);
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
		 
		$assessmentTypeDb = new Examination_Model_DbTable_Assessmenttype();
		$assessment_list = $assessmentTypeDb->fnGetAssesmentTypeNamebyID($this->examtype);
		
		//examtype code
		$this->addElement('text','exam_type',
				array(
						'label'=>'Exam Type',
						'readonly' => 'true'
				)
		);
		
		$this->exam_type->setValue($assessment_list['ItemName']);
		 
		$this->addElement('textarea','ScriptName', 
			array(
				'label'=>'Script Name',
				'required'=>'true'
			)
		);
		
		$this->ScriptName->setValue('Soal '.$assessment_list['ItemName'].' '.$subject['SubCode'].'-'.$subject['BahasaIndonesia'].' '.$sem['SemesterMainDefaultLanguage']);
		
		$this->addElement('text','time_in_minute',
				array(
						'label'=>'Duration in minute',
						'required'=>'true'
				)
		);
		$this->time_in_minute->setValue("90");
		//capacity
		$this->addElement('button','url_upload',
				array(
						'label'=>'Upload Exam Script in pdf or jpg',
						'ignore' => true,
						'onclick'=>'onclickUpload('.$this->semester_id.','.$this->subject_id.','.$this->program_id.','.$this->examtype.')'
				 )
		);
		
		 
		$this->addElement('text','url',
				array(
						 
						'required'=>'true',
						'readonly'=>'true'  
						 
				)
		);
		$this->url->setValue($this->path);
		
		
		$this->addElement('text','nscript',
				array(
						'label'=>'N of Sheet',
						'required'=>'true'  
				
				)
		);
		$this->nscript->setValue("1");
		//list of contributor
		$dbContri = new Examination_Model_DbTable_ExamScriptContributor();
		$tappend='';
		if ($this->script_id!='') {
			$contri=$dbContri->getAllByScript($this->script_id);
			if ($contri) {
		
				foreach ($contri as $value) {
					$tappend .= '<tr>'.
							'<td>'.$value['FullName'] .
							'</td><td>'.$value['Position'] .
							'</td></tr>';
				}
		
			}
		}
		 
		$table = '<table id="contributor" class="table" width="500px"><thead><tr><th>Contributor Name</th><th>Contributor Position</th></tr></thead><tbody>'.$tappend.'</tbody> </table>';
		$this->addElement('hidden', 'contributor_list', array(
				'label' => 'Contributor',
				'ignore' => true,
				'description' =>$table
		));
		
		$this->contributor_list
		->setDecorators(array(
				'ViewHelper',
				array('Description', array('escape' => false, 'tag' => false)),
				array('HtmlTag', array('tag' => 'dd')),
				array('Label', array('tag' => 'dt')),
				'Errors',
		));
		//exam tagging
		
		$dbExam = new Examination_Model_DbTable_ExamScriptExamGroup();
		
		$tappend='';
		if ($this->script_id!='') {
			$exam=$dbExam->getAllByScript($this->script_id);
			if ($exam) {
				
				foreach ($exam as $value) {
					 $tappend .= '<tr>'.
		    		  '<td>'.
		    		  '<input type="hidden" name="examgroup[]" value="'.$value['IdScriptExam'].'" />'.
		    		  		$value['eg_group_code'] .'-'.$value['eg_group_name'].
		    		  '</td><td><a href="#" onclick="$(this).parent().parent().remove(); return false;"><img src="/images/icon/user_trash_full.png" title="Delete" />'.
		    		  '</td>'.
		    		  '</tr>';
				}
				
			}
		}
		//program
		$table = '<table id="examgroup" class="table" width="500px"><thead><tr><th>Exam Group</th><th width="30px">Action</th></tr></thead><tbody>'.$tappend.'</tbody><tfoot><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tfoot></table>';
		$this->addElement('hidden', 'examgroup_list', array(
		    'label' => 'For Examination',
		    'ignore' => true,
		    'description' => '<a href="#" class="btn" id="assign-examgroup">Tag Examination</a><br />'.$table
		));
		
		$this->examgroup_list
		->setDecorators(array(
		    'ViewHelper',
		    array('Description', array('escape' => false, 'tag' => false)),
		    array('HtmlTag', array('tag' => 'dd')),
		    array('Label', array('tag' => 'dt')),
		    'Errors',
		));
		
		$this->addElement('text','printedbysearch',
				array(
						'label'=>'Search Printed By',
						'required'=>'true',
						'onchange'=>'getprintedby()'
		
				)
		);
		
		$this->addElement('select','printedby',
				array(
						'label'=>'',
						'required'=>'true'
		
				)
		);
		
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