<?php
class Examination_Form_Generateexamscaling extends Zend_Dojo_Form { //Formclass for the user module
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
		$semestercode->setAttrib('required',"false") ;
		$semestercode->removeDecorator("Label");
		$semestercode->removeDecorator('HtmlTag');
		$semestercode->setRegisterInArrayValidator(false);
		$semestercode->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$semestercode->addMultiOptions($lobjsemesterlist);

		//$lobjdeftype = new App_Model_Definitiontype();
		//$larrComponents = $lobjdeftype->fnGetDefinationMs('Subject Components');

		$lobjassessmenttypeModel = new Examination_Model_DbTable_Assessmenttype();
		$larrcomponentlist = $lobjassessmenttypeModel->getdropdownforasseementtype();
		$IdComponent = new Zend_Dojo_Form_Element_FilteringSelect('IdComponent');
		$IdComponent->removeDecorator("DtDdWrapper");
		$IdComponent->setAttrib('required',"false") ;
		$IdComponent->removeDecorator("Label");
		$IdComponent->removeDecorator('HtmlTag');
		$IdComponent->setRegisterInArrayValidator(false);
		$IdComponent->setAttrib('dojoType',"dijit.form.FilteringSelect");
		//$IdComponent->setAttrib('Onchange',"getcourseList(this.value)");
		foreach($larrcomponentlist as $larrvalues) {
			$IdComponent->addMultiOption($larrvalues['key'],$larrvalues['value']);
		}




		$IdCourse = new Zend_Dojo_Form_Element_FilteringSelect('IdCourse');
		$IdCourse->removeDecorator("DtDdWrapper");
		$IdCourse->setAttrib('required',"false") ;
		$IdCourse->removeDecorator("Label");
		$IdCourse->removeDecorator('HtmlTag');
		$IdCourse->setRegisterInArrayValidator(false);
		$IdCourse->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$lobjsubjectmasterModel = new GeneralSetup_Model_DbTable_Subjectmaster();
		$courseList = $lobjsubjectmasterModel->fnGetSubjectList();
		foreach($courseList as $larrvalues) {
			$IdCourse->addMultiOption($larrvalues['key'],$larrvalues['value']);
		}
		//        if(!empty($courseList)){
		//            $IdCourse->addMultiOption($courseList);
		//        }




		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required',"false") ;
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");
		//$IdProgram->setAttrib('Onchange',"getcourseList(this.value)");


		$IdFaculty = new Zend_Dojo_Form_Element_FilteringSelect('IdFaculty');
		$IdFaculty->removeDecorator("DtDdWrapper");
		$IdFaculty->setAttrib('required',"false") ;
		$IdFaculty->removeDecorator("Label");
		$IdFaculty->removeDecorator('HtmlTag');
		$IdFaculty->setRegisterInArrayValidator(false);
		$IdFaculty->setAttrib('dojoType',"dijit.form.FilteringSelect");






		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Process Scaling");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag');
		//->class = "NormalBtn";

		$Search = new Zend_Form_Element_Submit('Search');
		$Search->dojotype="dijit.form.Button";
		$Search->label = $gstrtranslate->_("Search Student");
		$Search->removeDecorator("DtDdWrapper");
		$Search->removeDecorator("Label");
		$Search->removeDecorator('HtmlTag');
		//$Search->class = "NormalBtn";
		//$Search->setAttrib('Onclick',"addfields()");
			

		//form elements
		$this->addElements(array($IdScheme,$semestercode,
				$UpdDate,$UpdUser,$Save,$Search,$IdComponent,
				$IdProgram,$IdCourse,$IdFaculty
		));

	}
}