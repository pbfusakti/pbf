<?php
class Examination_Form_Gpastudents extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";
			
		$IdGpaStudents = new Zend_Form_Element_Hidden('IdGpaStudents');
		$IdGpaStudents->removeDecorator("DtDdWrapper");
		$IdGpaStudents->removeDecorator("Label");
		$IdGpaStudents->removeDecorator('HtmlTag');


		$IdStudentRegistration = new Zend_Dojo_Form_Element_FilteringSelect('IdStudentRegistration');
		$IdStudentRegistration->removeDecorator("DtDdWrapper");
		$IdStudentRegistration->setAttrib('required',"true") ;
		$IdStudentRegistration->removeDecorator("Label");
		$IdStudentRegistration->removeDecorator('HtmlTag');
		$IdStudentRegistration->setRegisterInArrayValidator(false);
		$IdStudentRegistration->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Intake = new Zend_Dojo_Form_Element_FilteringSelect('Intake');
		$Intake->removeDecorator("DtDdWrapper");
		$Intake->setAttrib('required',"true") ;
		$Intake->removeDecorator("Label");
		$Intake->removeDecorator('HtmlTag');
		$Intake->setRegisterInArrayValidator(false);
		$Intake->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdSemester = new Zend_Form_Element_Text('IdSemester',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		$IdSemester->setAttrib('required',"true");
		$IdSemester->setAttrib ('readonly','true');
		$IdSemester->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$AcademicStatus = new Zend_Dojo_Form_Element_FilteringSelect('AcademicStatus');
		$AcademicStatus->removeDecorator("DtDdWrapper");
		$AcademicStatus->setAttrib('required',"true") ;
		$AcademicStatus->addMultiOptions(array('0' => 'GPA'));
		$AcademicStatus->removeDecorator("Label");
		$AcademicStatus->removeDecorator('HtmlTag');
		$AcademicStatus->setRegisterInArrayValidator(false);
		$AcademicStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Marks = new Zend_Form_Element_Text('Marks',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		$Marks->setAttrib('required',"true");
		$Marks->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');




		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

			
		$Active = new Zend_Form_Element_Checkbox('Active');
		$Active->  setAttrib('dojoType',"dijit.form.CheckBox")
		->setChecked(true)
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";
			
		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		//form elements
		$this->addElements(array($IdGpaStudents,
				$IdStudentRegistration,
				$Intake,
				$IdSemester,
				$AcademicStatus,
				$Marks,
				$UpdDate,
				$UpdUser,
				$Active,
				$Save,
				$Clear
		));

	}
}