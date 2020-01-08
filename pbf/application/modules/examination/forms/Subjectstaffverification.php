<?php
class Examination_Form_Subjectstaffverification extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$dateofbirth = "{datePattern:'dd-MM-yyyy'}";

		$IdSubject = new Zend_Form_Element_Hidden('IdSubject');
		$IdSubject->removeDecorator("DtDdWrapper");
		$IdSubject->removeDecorator("Label");
		$IdSubject->removeDecorator('HtmlTag');

		$IdSubjectStaffVerification = new Zend_Form_Element_Hidden('IdSubjectStaffVerification');
		$IdSubjectStaffVerification->removeDecorator("DtDdWrapper");
		$IdSubjectStaffVerification->removeDecorator("Label");
		$IdSubjectStaffVerification->removeDecorator('HtmlTag');


		$SameLecturer = new Zend_Form_Element_Checkbox('SameLecturer');
		$SameLecturer->  setAttrib('dojoType',"dijit.form.CheckBox")
		->setChecked(false)
		->setAttrib('onClick','blockstafflist(this.checked)')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
		$IdSemester->removeDecorator("DtDdWrapper");
		$IdSemester->setAttrib('required',"true") ;
		$IdSemester->removeDecorator("Label");
		$IdSemester->removeDecorator('HtmlTag');
		$IdSemester->setRegisterInArrayValidator(false);
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$IdStaff = new Zend_Dojo_Form_Element_FilteringSelect('IdStaff');
		$IdStaff->removeDecorator("DtDdWrapper");
		$IdStaff->setAttrib('required',"false") ;
		$IdStaff->removeDecorator("Label");
		$IdStaff->removeDecorator('HtmlTag');
		$IdStaff->setRegisterInArrayValidator(false);
		$IdStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");



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

		$this->addElements(array($IdSubject,$IdSemester,
				$IdStaff,$IdSubjectStaffVerification,
				$SameLecturer,
				$UpdDate,
				$UpdUser,
				$Active,
				$Save,
				$Clear
		));
	}
}