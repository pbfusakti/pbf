<?php

class Examination_Form_Marksdistributionmaster extends Zend_Dojo_Form { //Formclass for the user module

	public function init() {
		$lobjcollegemaster = new GeneralSetup_Model_DbTable_Collegemaster();
		$facultyList = $lobjcollegemaster->fnGetListofCollege();
		$temp = array('key' => '0', 'value' => 'All');
		$facultyList[] = $temp;

		$gstrtranslate = Zend_Registry::get('Zend_Translate');

		$month = date("m"); // Month value
		$day = date("d"); //today's date
		$year = date("Y"); // Year value
		$yesterdaydate = date('Y-m-d', mktime(0, 0, 0, $month, ($day), $year));
		$dateofbirth = "{min:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";

		$IdMarksDistributionMaster = new Zend_Form_Element_Hidden('IdMarksDistributionMaster');
		$IdMarksDistributionMaster->removeDecorator("DtDdWrapper");
		$IdMarksDistributionMaster->removeDecorator("Label");
		$IdMarksDistributionMaster->removeDecorator('HtmlTag');


		$IdFaculty = new Zend_Dojo_Form_Element_FilteringSelect('IdFaculty');
		$IdFaculty->removeDecorator("DtDdWrapper");
		$IdFaculty->setAttrib('required', "false");
		$IdFaculty->removeDecorator("Label");
		$IdFaculty->removeDecorator('HtmlTag');
		$IdFaculty->setAttrib('OnChange', 'fnGetSchmeList');
		$IdFaculty->addmultioptions($facultyList);
		//$IdFaculty->setRegisterInArrayValidator(false);
		$IdFaculty->setAttrib('dojoType', "dijit.form.FilteringSelect");


		$IdScheme = new Zend_Dojo_Form_Element_FilteringSelect('IdScheme');
		$IdScheme->removeDecorator("DtDdWrapper");
		$IdScheme->setAttrib('required', "false");
		$IdScheme->removeDecorator("Label");
		$IdScheme->removeDecorator('HtmlTag');
		$IdScheme->setAttrib('OnChange', 'fnGetProgramList');
		//$IdScheme->setRegisterInArrayValidator(false);
		$IdScheme->setAttrib('dojoType', "dijit.form.FilteringSelect");


		$lobjsemesterModel = new GeneralSetup_Model_DbTable_Semester();
		$semList = $lobjsemesterModel->getAllsemesterListCode();
		$semestercode = new Zend_Dojo_Form_Element_FilteringSelect('semestercode');
		$semestercode->removeDecorator("DtDdWrapper");
		$semestercode->setAttrib('required', "false");
		$semestercode->removeDecorator("Label");
		$semestercode->removeDecorator('HtmlTag');
		$semestercode->addmultioptions($semList);
		$semestercode->setAttrib('dojoType', "dijit.form.FilteringSelect");


		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required', "false");
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setAttrib('OnChange', 'fnGetCourseList');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$IdCourse = new Zend_Dojo_Form_Element_FilteringSelect('IdCourse');
		$IdCourse->removeDecorator("DtDdWrapper");
		$IdCourse->setAttrib('required', "false");
		$IdCourse->removeDecorator("Label");
		$IdCourse->removeDecorator('HtmlTag');
		$IdCourse->setRegisterInArrayValidator(false);
		$IdCourse->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$EffectiveDate = new Zend_Dojo_Form_Element_DateTextBox('EffectiveDate');
		$EffectiveDate->setAttrib('dojoType', "dijit.form.DateTextBox");
		$EffectiveDate->setAttrib('required', "true");
		$EffectiveDate->setAttrib('constraints', "$dateofbirth");
		$EffectiveDate->removeDecorator("DtDdWrapper");
		$EffectiveDate->setAttrib('title', "dd-mm-yyyy");
		$EffectiveDate->removeDecorator("Label");
		$EffectiveDate->removeDecorator('HtmlTag');

		$Active = new Zend_Form_Element_Checkbox('Active');
		$Active->setAttrib('dojoType', "dijit.form.CheckBox")
		->setChecked(true)
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');



		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype = "dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->setAttrib('OnClick', 'return validateform()');
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$Approve = new Zend_Form_Element_Submit('Approve');
		$Approve->dojotype = "dijit.form.Button";
		$Approve->label = $gstrtranslate->_("Approve");
		$Approve->removeDecorator("DtDdWrapper");
		$Approve->removeDecorator("Label");
		$Approve->setAttrib('OnClick', 'return validateform()');
		$Approve->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$Reject = new Zend_Form_Element_Submit('Reject');
		$Reject->dojotype = "dijit.form.Button";
		$Reject->label = $gstrtranslate->_("Reject");
		$Reject->removeDecorator("DtDdWrapper");
		$Reject->removeDecorator("Label");
		//$Approve->setAttrib('OnClick', 'return validateform()');
		$Reject->removeDecorator('HtmlTag')
		->class = "NormalBtn";


		$ToProgram = new Zend_Dojo_Form_Element_FilteringSelect('ToProgram');
		$ToProgram->removeDecorator("DtDdWrapper");
		$ToProgram->setAttrib('required', "false");
		$ToProgram->removeDecorator("Label");
		$ToProgram->removeDecorator('HtmlTag');
		$ToProgram->setAttrib('OnChange', 'fnGettoCourseList');
		$ToProgram->setRegisterInArrayValidator(false);
		$ToProgram->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$FromProgram = new Zend_Dojo_Form_Element_FilteringSelect('FromProgram');
		$FromProgram->removeDecorator("DtDdWrapper");
		$FromProgram->setAttrib('required', "false");
		$FromProgram->removeDecorator("Label");
		$FromProgram->removeDecorator('HtmlTag');
		$FromProgram->setAttrib('OnChange', 'fnGetFromCourseList');
		$FromProgram->setRegisterInArrayValidator(false);
		$FromProgram->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$FromCourse = new Zend_Dojo_Form_Element_FilteringSelect('FromCourse');
		$FromCourse->removeDecorator("DtDdWrapper");
		$FromCourse->setAttrib('required', "false");
		$FromCourse->removeDecorator("Label");
		$FromCourse->removeDecorator('HtmlTag');
		//$FromProgram->setAttrib('OnChange', 'fnGetCourseList');
		$FromCourse->setRegisterInArrayValidator(false);
		$FromCourse->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$ToCourse = new Zend_Dojo_Form_Element_FilteringSelect('ToCourse');
		$ToCourse->removeDecorator("DtDdWrapper");
		$ToCourse->setAttrib('required', "false");
		$ToCourse->removeDecorator("Label");
		$ToCourse->removeDecorator('HtmlTag');
		//$FromProgram->setAttrib('OnChange', 'fnGetCourseList');
		$ToCourse->setRegisterInArrayValidator(false);
		$ToCourse->setAttrib('dojoType', "dijit.form.FilteringSelect");


		$FromSemester = new Zend_Dojo_Form_Element_FilteringSelect('FromSemester');
		$FromSemester->removeDecorator("DtDdWrapper");
		$FromSemester->setAttrib('required', "false");
		$FromSemester->removeDecorator("Label");
		$FromSemester->removeDecorator('HtmlTag');
		//$FromProgram->setAttrib('OnChange', 'fnGetCourseList');
		$FromSemester->setRegisterInArrayValidator(false);
		$FromSemester->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$ToSemester = new Zend_Dojo_Form_Element_FilteringSelect('ToSemester');
		$ToSemester->removeDecorator("DtDdWrapper");
		$ToSemester->setAttrib('required', "false");
		$ToSemester->removeDecorator("Label");
		$ToSemester->removeDecorator('HtmlTag');
		//$FromProgram->setAttrib('OnChange', 'fnGetCourseList');
		$ToSemester->setRegisterInArrayValidator(false);
		$ToSemester->setAttrib('dojoType', "dijit.form.FilteringSelect");

		$Copy = new Zend_Form_Element_Submit('Copy');
		$Copy->dojotype = "dijit.form.Button";
		$Copy->label = $gstrtranslate->_("Copy");
		$Copy->removeDecorator("DtDdWrapper");
		$Copy->removeDecorator("Label");
		$Copy->setAttrib('OnClick', 'return validateCopyForm()');
		$Copy->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->dojotype = "dijit.form.Button";
		$Clear->label = $gstrtranslate->_("Clear");
		$Clear->removeDecorator("DtDdWrapper");
		$Clear->removeDecorator("Label");
		$Clear->removeDecorator('HtmlTag')
		->class = "NormalBtn";







		$this->addElements(array($IdMarksDistributionMaster, $IdFaculty,
				$IdProgram, $IdCourse, $IdScheme, $semestercode,
				$EffectiveDate,
				$UpdDate,
				$UpdUser,
				$Active,
				$Save, $Approve, $Reject,$ToProgram,$FromProgram,$ToProgram,
				$FromSemester,$ToSemester,$Copy,$Clear,$FromCourse,$ToCourse

		));
	}

}