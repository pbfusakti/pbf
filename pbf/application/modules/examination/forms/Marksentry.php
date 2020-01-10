<?php
class Examination_Form_Marksentry extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$dateofbirth = "{datePattern:'dd-MM-yyyy'}";


		$IdStudent = new Zend_Form_Element_Text('IdStudent');
		$IdStudent->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('required',"false")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect")
		->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$IdCourse = new Zend_Dojo_Form_Element_FilteringSelect('IdCourse');
		$IdCourse->setAttrib('dojoType',"dijit.form.FilteringSelect")
		->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$IdComponent = new Zend_Dojo_Form_Element_FilteringSelect('IdComponent');
		$IdComponent->setAttrib('dojoType',"dijit.form.FilteringSelect")
		->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect")
		->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$IdSubject = new Zend_Form_Element_Hidden('IdSubject');
		$IdSubject->removeDecorator("DtDdWrapper");
		$IdSubject->removeDecorator("Label");
		$IdSubject->removeDecorator('HtmlTag');

		$IdMarksEntrySetup = new Zend_Form_Element_Hidden('IdMarksEntrySetup');
		$IdMarksEntrySetup->removeDecorator("DtDdWrapper");
		$IdMarksEntrySetup->removeDecorator("Label");
		$IdMarksEntrySetup->removeDecorator('HtmlTag');

		$Verification = new Zend_Form_Element_Checkbox('Verification');
		$Verification->setAttrib('dojoType',"dijit.form.CheckBox")
		->setChecked(true)
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
			
		$EffectiveDate = new Zend_Dojo_Form_Element_DateTextBox('EffectiveDate');
		$EffectiveDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$EffectiveDate->setAttrib('required',"true");
		$EffectiveDate->setAttrib('constraints', "$dateofbirth");
		$EffectiveDate->removeDecorator("DtDdWrapper");
		$EffectiveDate->setAttrib('title',"dd-mm-yyyy");
		$EffectiveDate->removeDecorator("Label");
		$EffectiveDate->removeDecorator('HtmlTag');

		$IdStaff = new Zend_Dojo_Form_Element_FilteringSelect('IdStaff');
		$IdStaff->setAttrib('Onchange', 'validateStaff');
		$IdStaff->removeDecorator("DtDdWrapper");
		$IdStaff->setAttrib('required',"true") ;
		$IdStaff->removeDecorator("Label");
		$IdStaff->removeDecorator('HtmlTag');
		$IdStaff->setRegisterInArrayValidator(false);
		$IdStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Rank = new Zend_Form_Element_Text('Rank');
		$Rank->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Rank->setAttrib('required',"true")
		->setAttrib('maxlength','100')
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
		$Active->setAttrib('dojoType',"dijit.form.CheckBox")
		->setChecked(true)
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

			
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag')->class = "NormalBtn";


		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$this->addElements(array($IdMarksEntrySetup,
				$IdSubject,
				$IdStaff,
				$Verification,
				$EffectiveDate,
				$Rank,
				$UpdDate,
				$UpdUser,
				$Active,
				$Save,
				$Clear
		));
	}
}