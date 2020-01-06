<?php
class Examination_Form_Subjectsmarksentry extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');

		$IdStudentRegistration  = new Zend_Form_Element_Hidden('IdStudentRegistration');
		$IdStudentRegistration->removeDecorator("DtDdWrapper");
		$IdStudentRegistration->setAttrib('dojoType',"dijit.form.TextBox");
		$IdStudentRegistration->removeDecorator("Label");
		$IdStudentRegistration->removeDecorator('HtmlTag');


		$IdStudentRegistration  = new Zend_Form_Element_Hidden('IdStudentApplication');
		$IdStudentRegistration->removeDecorator("DtDdWrapper");
		$IdStudentRegistration->setAttrib('dojoType',"dijit.form.TextBox");
		$IdStudentRegistration->removeDecorator("Label");
		$IdStudentRegistration->removeDecorator('HtmlTag');


		$IdLandscape = new Zend_Dojo_Form_Element_FilteringSelect('IdLandscape');
		$IdLandscape->removeDecorator("DtDdWrapper");
		$IdLandscape->setAttrib('required',"true") ;
		$IdLandscape->removeDecorator("Label");
		$IdLandscape->removeDecorator('HtmlTag');
		$IdLandscape->setRegisterInArrayValidator(false);
		$IdLandscape->setAttrib('OnChange', "fnGetSubjectList(this)");
		$IdLandscape->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdProgram  = new Zend_Form_Element_Hidden('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('dojoType',"dijit.form.TextBox");
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');

		$IdLandscapeType  = new Zend_Form_Element_Hidden('IdLandscapeType');
		$IdLandscapeType->removeDecorator("DtDdWrapper");
		$IdLandscapeType->setAttrib('dojoType',"dijit.form.TextBox");
		$IdLandscapeType->removeDecorator("Label");
		$IdLandscapeType->removeDecorator('HtmlTag');

		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester[]');
		$IdSemester->removeDecorator("DtDdWrapper");
		$IdSemester->setAttrib('required',"true") ;
		$IdSemester->removeDecorator("Label");
		$IdSemester->removeDecorator('HtmlTag');
		$IdSemester->setRegisterInArrayValidator(false);
		// $IdSemester->setAttrib('OnChange', "fnGetSubjectList(this)");
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");




		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->label = $gstrtranslate->_("Save");
		$Save->dojotype="dijit.form.Button";
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";
			
		$registrationId = new Zend_Form_Element_Text('registrationId');
		$registrationId->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$registrationId  //->setAttrib('required',"true")
		->setAttrib('maxlength','75')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		//form elements
		$this->addElements(array($IdStudentRegistration,$IdSemester,$UpdDate,$UpdUser,$Save,$IdProgram,$IdLandscape,$IdLandscapeType,$registrationId));

	}
}