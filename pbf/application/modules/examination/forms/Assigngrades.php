<?php
class Examination_Form_Academicstatus extends Zend_Dojo_Form { //Formclass for the user module
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


		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required',"true") ;
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
		$IdSemester->removeDecorator("DtDdWrapper");
		$IdSemester->setAttrib('required',"true") ;
		$IdSemester->removeDecorator("Label");
		$IdSemester->removeDecorator('HtmlTag');
		$IdSemester->setRegisterInArrayValidator(false);
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$AcademicStatus = new Zend_Dojo_Form_Element_FilteringSelect('AcademicStatus');
		$AcademicStatus->removeDecorator("DtDdWrapper");
		$AcademicStatus->setAttrib('required',"true") ;
		$AcademicStatus->addMultiOptions(array('0' => 'GPA',
				'1' => 'CGPA'));
		$AcademicStatus->removeDecorator("Label");
		$AcademicStatus->removeDecorator('HtmlTag');
		$AcademicStatus->setRegisterInArrayValidator(false);
		$AcademicStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Minimum = new Zend_Form_Element_Text('Minimum',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		$Minimum->setAttrib('required',"true");
		$Minimum->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$Maximum = new Zend_Form_Element_Text('Maximum',array('regExp'=>"[0-9]*\.[0-9]+|[0-9]+",'invalidMessage'=>"Digits Only"));
		$Maximum->setAttrib('required',"true");
		$Maximum->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$StatusEnglishName = new Zend_Form_Element_Text('StatusEnglishName');
		$StatusEnglishName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$StatusEnglishName->setAttrib('required',"true")
		->setAttrib('maxlength','100')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		$StatusArabicName = new Zend_Form_Element_Text('StatusArabicName');
		$StatusArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$StatusArabicName->setAttrib('required',"true")
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
		$this->addElements(array($IdAcademicStatus,
				$IdProgram,
				$IdSemester,
				$AcademicStatus,
				$Minimum,
				$Maximum,
				$StatusEnglishName,
				$StatusArabicName,
				$UpdDate,
				$UpdUser,
				$Active,
				$Save,
				$Clear
		));

	}
}