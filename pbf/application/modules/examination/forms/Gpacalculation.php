<?php
class Examination_Form_Gpacalculation extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";

		$IdApplication = new Zend_Dojo_Form_Element_FilteringSelect('IdApplication');
		$IdApplication->removeDecorator("DtDdWrapper");
		$IdApplication->setAttrib('required',"true") ;
		$IdApplication->removeDecorator("Label");
		$IdApplication->removeDecorator('HtmlTag');
		$IdApplication->setRegisterInArrayValidator(false);
		$IdApplication->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$Gpa = new Zend_Form_Element_Hidden('Gpa');
		$Gpa->removeDecorator("DtDdWrapper");
		$Gpa->removeDecorator("Label");
		$Gpa->removeDecorator('HtmlTag');

		$IdStudentRegistration = new Zend_Form_Element_Hidden('IdStudentRegistration');
		$IdStudentRegistration->removeDecorator("DtDdWrapper");
		$IdStudentRegistration->removeDecorator("Label");
		$IdStudentRegistration->removeDecorator('HtmlTag');

		$Idgpacalculation = new Zend_Form_Element_Hidden('Idgpacalculation');
		$Idgpacalculation->removeDecorator("DtDdWrapper");
		$Idgpacalculation->removeDecorator("Label");
		$Idgpacalculation->removeDecorator('HtmlTag');


		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'addCourseTypeDetails()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";
			
			
		$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType',"dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'clearpageAdd()');
		$clear->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		//form elements
		$this->addElements(array(
				$IdApplication,
				$UpdDate,
				$UpdUser,
				$Add,
				$Save,$Gpa,$IdStudentRegistration,
				$clear,$Idgpacalculation
		));

	}
}