<?php
class Examination_Form_Examscaling extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');


		$lobjschemesetupmodel = new GeneralSetup_Model_DbTable_Schemesetup();
		$schemeList = $lobjschemesetupmodel->fnGetSchemeDetails(); //function to display all schemesetup details in list
		$IdScheme = new Zend_Dojo_Form_Element_FilteringSelect('IdScheme');
		$IdScheme->removeDecorator("DtDdWrapper");
		$IdScheme->setAttrib('required',"false") ;
		$IdScheme->removeDecorator("Label");
		$IdScheme->removeDecorator('HtmlTag');
		$IdScheme->setRegisterInArrayValidator(false);
		$IdScheme->setAttrib('Onchange',"getprogramList(this.value)");
		foreach($schemeList as $larrschemearr) {
			$IdScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		$IdScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$lobjsemesterModel = new GeneralSetup_Model_DbTable_Semester();
		$lobjsemesterlist = $lobjsemesterModel->getAllsemesterListCode();
		$semestercode = new Zend_Dojo_Form_Element_FilteringSelect('semestercode');
		$semestercode->removeDecorator("DtDdWrapper");
		$semestercode->setAttrib('required',"true") ;
		$semestercode->removeDecorator("Label");
		$semestercode->removeDecorator('HtmlTag');
		$semestercode->setRegisterInArrayValidator(false);
		$semestercode->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$semestercode->addMultiOptions($lobjsemesterlist);


		$IdFaculty = new Zend_Dojo_Form_Element_FilteringSelect('IdFaculty');
		$IdFaculty->removeDecorator("DtDdWrapper");
		$IdFaculty->setAttrib('required',"false") ;
		$IdFaculty->removeDecorator("Label");
		$IdFaculty->removeDecorator('HtmlTag');
		$IdFaculty->setRegisterInArrayValidator(false);
		$IdFaculty->setAttrib('dojoType',"dijit.form.FilteringSelect");
		//$IdFaculty->setAttrib('Onchange',"getprogramList(this.value)");

		$IdComponent = new Zend_Dojo_Form_Element_FilteringSelect('IdComponent');
		$IdComponent->removeDecorator("DtDdWrapper");
		$IdComponent->setAttrib('required',"false") ;
		$IdComponent->removeDecorator("Label");
		$IdComponent->removeDecorator('HtmlTag');
		$IdComponent->setRegisterInArrayValidator(false);
		$IdComponent->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$IdComponent->setAttrib('Onchange',"getcourseList(this.value)");

		$IdSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdSubject');
		$IdSubject->removeDecorator("DtDdWrapper");
		$IdSubject->setAttrib('required',"false") ;
		$IdSubject->removeDecorator("Label");
		$IdSubject->removeDecorator('HtmlTag');
		$IdSubject->setRegisterInArrayValidator(false);
		$IdSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");




		$Marks = new Zend_Form_Element_Text('Marks');
		$Marks->removeDecorator("DtDdWrapper");
		$Marks->setAttrib('required',"false") ;
		$Marks->setAttrib('constraints', '{min:-100,max:100,pattern:"#.##"}');
		$Marks->setAttrib('invalidMessage', 'marks should be in between -100 to 100');
		$Marks->removeDecorator("Label");
		$Marks->removeDecorator('HtmlTag');
		$Marks->setAttrib('dojoType',"dijit.form.NumberTextBox");

		$IdExamScaling = new Zend_Form_Element_Hidden('IdExamScaling');
		$IdExamScaling->removeDecorator("DtDdWrapper");
		$IdExamScaling->removeDecorator("Label");
		$IdExamScaling->removeDecorator('HtmlTag');


		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

		$CreatedDate = new Zend_Form_Element_Hidden('CreatedDate');
		$CreatedDate->removeDecorator("DtDdWrapper");
		$CreatedDate->removeDecorator("Label");
		$CreatedDate->removeDecorator('HtmlTag');

		$CreatedBy  = new Zend_Form_Element_Hidden('CreatedBy');
		$CreatedBy->removeDecorator("DtDdWrapper");
		$CreatedBy->removeDecorator("Label");
		$CreatedBy->removeDecorator('HtmlTag');


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
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$Add = new Zend_Form_Element_Button('Add');
		$Add->dojotype="dijit.form.Button";
		$Add->label = $gstrtranslate->_("Add");
		$Add->removeDecorator("DtDdWrapper");
		$Add->removeDecorator("Label");
		$Add->removeDecorator('HtmlTag');
		$Add->class = "NormalBtn";
		$Add->setAttrib('Onclick',"addfields()");


		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->dojotype="dijit.form.Button";
		$Clear->label = $gstrtranslate->_("Clear");
		$Clear->removeDecorator("DtDdWrapper");
		$Clear->removeDecorator("Label");
		$Clear->removeDecorator('HtmlTag');
		$Clear->class = "NormalBtn";
		$Clear->setAttrib('Onclick',"clearfields");

		//form elements
		$this->addElements(array($IdScheme,$semestercode,$Marks,
				$UpdDate,$UpdUser,$Active,$Save,$Clear,$Add,$IdFaculty,
				$IdComponent,$IdSubject,$CreatedDate,$CreatedBy,$IdExamScaling
		));

	}
}