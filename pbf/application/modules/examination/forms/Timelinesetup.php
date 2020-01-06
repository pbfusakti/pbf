<?php
class Examination_Form_Timelinesetup extends Zend_Dojo_Form { //Formclass for the user module
	
	private $lobjexamassementtypeModel;
	private $lobjdeftype;
	
	public function init() {
		
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
		$this->lobjexamassementtypeModel = new Examination_Model_DbTable_Assessmenttype();
		$this->lobjdeftype = new App_Model_Definitiontype();
		$dateformat = "{datePattern:'dd-MM-yyyy'}";

		
		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		
		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		$IdUniversity = new Zend_Form_Element_Hidden('IdUniversity');
		$IdUniversity->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		
		$StartDate = new Zend_Form_Element_Text('StartDate');
		$StartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$StartDate->setAttrib('required',"false");
		//$StartDate->setAttrib('onChange',"dijit.byId('EndDate').constraints.min = arguments[0];");
		$StartDate->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
			
		$EndDate = new Zend_Form_Element_Text('EndDate');
		$EndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		//$EndDate->setAttrib('onChange',"dijit.byId('StartDate').constraints.max = arguments[0];") ;
		$EndDate->setAttrib('required',"false");
		$EndDate->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		
		$SemesterStartDate = new Zend_Form_Element_Text('SemesterStartDate');
		$SemesterStartDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterStartDate->setAttrib('required',"false");
		//$StartDate->setAttrib('onChange',"dijit.byId('EndDate').constraints.min = arguments[0];");
		$SemesterStartDate->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		$SemesterEndDate = new Zend_Form_Element_Text('SemesterEndDate');
		$SemesterEndDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$SemesterEndDate->setAttrib('required',"false");
		//$StartDate->setAttrib('onChange',"dijit.byId('EndDate').constraints.min = arguments[0];");
		$SemesterEndDate->setAttrib('constraints', "$dateformat")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		
		$Semester = new Zend_Dojo_Form_Element_FilteringSelect('Semester');
		$Semester->removeDecorator("DtDdWrapper");
		$Semester->setAttrib('required',"true") ;
		$Semester->removeDecorator("Label");
		$Semester->removeDecorator('HtmlTag');
		$Semester->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$larrsemester = $this->lobjexamassementtypeModel->fnSemester();
		foreach($larrsemester as $values) {
			$Semester->addMultiOption($values['key'],$values['value']);
		}
		
		
		$Activity = new Zend_Dojo_Form_Element_FilteringSelect('Activity');
		$Activity->removeDecorator("DtDdWrapper");
		$Activity->setAttrib('required',"false") ;
		$Activity->removeDecorator("Label");
		$Activity->setAttrib('OnChange', 'checkactivity(this.value)');
		$Activity->removeDecorator('HtmlTag');
		$Activity->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$larrActivity = $this->lobjdeftype->fnGetDefinationMs('Exam Activity');
		foreach($larrActivity as $values) {
			$Activity->addMultiOption($values['key'],$values['value']);
		}
		
		$AssessmentType = new Zend_Dojo_Form_Element_FilteringSelect('AssessmentType');
		$AssessmentType->removeDecorator("DtDdWrapper");
		$AssessmentType->setAttrib('required',"false") ;
		$AssessmentType->removeDecorator("Label");
		$AssessmentType->removeDecorator('HtmlTag');
		$AssessmentType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		$larrassessment = $this->lobjexamassementtypeModel->fngetassessment();
		foreach($larrassessment as $values) {
			$AssessmentType->addMultiOption($values['IdExaminationAssessmentType'],$values['Description']);
		}
		
		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'remarkValidation()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');


		$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType',"dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'clearpageAdd()');
		$clear->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

		$IdUniversity  = new Zend_Form_Element_Hidden('IdUniversity');
		$IdUniversity->removeDecorator("DtDdWrapper");
		$IdUniversity->removeDecorator("Label");
		$IdUniversity->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->label = $gstrtranslate->_("Save");
		$Save->dojotype="dijit.form.Button";
		$Save->removeDecorator("DtDdWrapper");
		$Save->setAttrib('OnClick', 'return saveValidation()');
		$Save->removeDecorator('HtmlTag')->class = "NormalBtn";

		$this->addElements(array($StartDate,$EndDate,$AssessmentType,
				$Activity,$SemesterEndDate,$SemesterStartDate,
				$Add,$clear,$UpdDate,$UpdUser,$IdUniversity,$Save,$Semester
		));



	}
}

?>
