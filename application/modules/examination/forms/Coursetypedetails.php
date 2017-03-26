<?php
class Examination_Form_Coursetypedetails extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";


			
		$IdCourseTypeDetails = new Zend_Form_Element_Hidden('IdCourseTypeDetails');
		$IdCourseTypeDetails->removeDecorator("DtDdWrapper");
		$IdCourseTypeDetails->removeDecorator("Label");
		$IdCourseTypeDetails->removeDecorator('HtmlTag');

		$IdCourseType = new Zend_Dojo_Form_Element_FilteringSelect('IdCourseType');
		$IdCourseType->removeDecorator("DtDdWrapper");
		$IdCourseType->setAttrib('required',"true") ;
		$IdCourseType->removeDecorator("Label");
		$IdCourseType->removeDecorator('HtmlTag');
		$IdCourseType->setRegisterInArrayValidator(false);
		$IdCourseType->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$ComponentName = new Zend_Form_Element_Text('ComponentName');
		$ComponentName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$ComponentName->setAttrib('required',"true")
		->setAttrib('maxlength','50')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$Description = new Zend_Form_Element_Text('Description');
		$Description->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Description->setAttrib('required',"true")
		->setAttrib('maxlength','200')
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
				$IdCourseTypeDetails,
				$IdCourseType,
				$ComponentName,
				$Description,
				$UpdDate,
				$UpdUser,
				$Active,
				$Add,
				$Save,
				$clear
		));

	}
}