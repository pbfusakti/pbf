<?php
class Examination_Form_Subjectregistration extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";
			
		$IdAcademicStatus = new Zend_Form_Element_Hidden('IdAcademicStatus');
		$IdAcademicStatus->removeDecorator("DtDdWrapper");
		$IdAcademicStatus->removeDecorator("Label");
		$IdAcademicStatus->removeDecorator('HtmlTag');

		$EffectiveDate = new Zend_Dojo_Form_Element_DateTextBox('EffectiveDate');
		$EffectiveDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$EffectiveDate->setAttrib('constraints', "$joiningdate");
		$EffectiveDate->setAttrib('required',"true");
		$EffectiveDate->removeDecorator("DtDdWrapper");
		$EffectiveDate->setAttrib('title',"dd-mm-yyyy");
		$EffectiveDate->removeDecorator("Label");
		$EffectiveDate->removeDecorator('HtmlTag');

		/*  $IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		 $IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required',"true") ;
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");*/

		$TerminateStatus = new Zend_Dojo_Form_Element_FilteringSelect('TerminateStatus');
		$TerminateStatus->removeDecorator("DtDdWrapper");
		$TerminateStatus->setAttrib('required',"true") ;
		$TerminateStatus->removeDecorator("Label");
		$TerminateStatus->removeDecorator('HtmlTag');
		$TerminateStatus->setRegisterInArrayValidator(false);
		$TerminateStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$AcademicStatus = new Zend_Dojo_Form_Element_FilteringSelect('AcademicStatus');
		$AcademicStatus->removeDecorator("DtDdWrapper");
		$AcademicStatus->setAttrib('required',"true") ;
		$AcademicStatus->addMultiOptions(array('0' => 'GPA',
				'1' => 'CGPA'));
		$AcademicStatus->removeDecorator("Label");
		$AcademicStatus->removeDecorator('HtmlTag');
		$AcademicStatus->setRegisterInArrayValidator(false);
		$AcademicStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdProgram = new Zend_Form_Element_Hidden('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');

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
			
			
		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('OnClick', 'addSubjectregistrationDetails()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
		$Add->dojotype="dijit.form.Button";
		$Add->setAttrib('class','NormalBtn');
		$Add->label = $gstrtranslate->_("Add");
			
		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');







		$Semester = new Zend_Form_Element_Text('Semester');
		$Semester  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('maxlength','3')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
			
		$Subjects = new Zend_Form_Element_Text('Subjects');
		$Subjects  ->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('maxlength','3')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
			
		$VirtualSemester = new Zend_Form_Element_Text('VirtualSemester');
		$VirtualSemester->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('maxlength','4')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');



		$CreditHours = new Zend_Form_Element_Text('CreditHours');
		$CreditHours->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('maxlength','4')
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
			
		$Min = new Zend_Form_Element_Text('Min',array('regExp'=>"^[1-9]\d*(\.\d+)?$",'invalidMessage'=>"Only digits"));
		$Min->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Min->setAttrib('maxlength','4');
		$Min->setAttrib('style','width:50px');
		$Min->setAttrib('readonly','true');
		$Min->setValue('0');
		$Min->removeDecorator("DtDdWrapper");
		$Min->removeDecorator("Label");
		$Min->removeDecorator('HtmlTag');

		$Max = new Zend_Form_Element_Text('Max',array('regExp'=>"^[1-9]\d*(\.\d+)?$",'invalidMessage'=>"Only digits"));
		$Max->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Max->setAttrib('maxlength','4');
		$Max->setAttrib('style','width:50px');
		$Max->removeDecorator("DtDdWrapper");
		$Max->removeDecorator("Label");
		$Max->removeDecorator('HtmlTag');

		//form elements
		$this->addElements(array($IdAcademicStatus,
				$IdProgram,
				$AcademicStatus,
				$UpdDate,
				$UpdUser,
				$Active,$Semester,
				$Save,
				$Clear,$Add,$EffectiveDate,$TerminateStatus,$VirtualSemester,$CreditHours,$Min,$Max,$Subjects
		));

	}
}