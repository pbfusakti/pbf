<?php
class Examination_Form_Academicstatus extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";
		$lobjdeftype = new App_Model_Definitiontype();
			
		//    	$IdAcademicStatus = new Zend_Form_Element_Hidden('IdAcademicStatus');
		//        $IdAcademicStatus->removeDecorator("DtDdWrapper");
		//        $IdAcademicStatus->removeDecorator("Label");
		//        $IdAcademicStatus->removeDecorator('HtmlTag');

		$IdAcademicStatus = new Zend_Dojo_Form_Element_FilteringSelect('IdAcademicStatus');
		$IdAcademicStatus->removeDecorator("DtDdWrapper");
		$IdAcademicStatus->setAttrib('required',"true") ;
		$IdAcademicStatus->removeDecorator("Label");
		$IdAcademicStatus->removeDecorator('HtmlTag');
		$IdAcademicStatus->setRegisterInArrayValidator(false);
		$IdAcademicStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$BasedOn  = new Zend_Form_Element_Radio('BasedOn');
		$BasedOn->setAttrib('dojoType',"dijit.form.RadioButton");
		$BasedOn->addMultiOptions(array('Scheme & Award' => 'Scheme & Award','Program' => 'Program'))
		->setvalue('Program')
		->setSeparator('&nbsp;')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->setAttrib('Onclick',"checksetupbasedon(this.value)")
		->removeDecorator('HtmlTag');

		$CopyBasedOn  = new Zend_Form_Element_Radio('CopyBasedOn');
		$CopyBasedOn->setAttrib('dojoType',"dijit.form.RadioButton");
		$CopyBasedOn->addMultiOptions(array('Scheme & Award' => 'Scheme & Award','Program' => 'Program'))
		->setvalue('Program')
		->setSeparator('&nbsp;')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->setAttrib('Onclick',"checksetupcopybasedon(this.value)")
		->removeDecorator('HtmlTag');



		$lobjschemesetupmodel = new GeneralSetup_Model_DbTable_Schemesetup();
		$schemeList = $lobjschemesetupmodel->fnGetSchemeDetails(); //function to display all schemesetup details in list
		$IdScheme = new Zend_Dojo_Form_Element_FilteringSelect('IdScheme');
		$IdScheme->removeDecorator("DtDdWrapper");
		$IdScheme->setAttrib('required',"true") ;
		$IdScheme->removeDecorator("Label");
		$IdScheme->removeDecorator('HtmlTag');
		$IdScheme->setRegisterInArrayValidator(false);
		$IdScheme->setAttrib('Onchange',"getprogramList(this.value)");
		foreach($schemeList as $larrschemearr) {
			$IdScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		$IdScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");

		
		$larrdefmsresultset = $lobjdeftype->fnGetDefinations('Award');
		$IdAward = new Zend_Dojo_Form_Element_FilteringSelect('IdAward');
		$IdAward->removeDecorator("DtDdWrapper");
		$IdAward->setAttrib('required',"true") ;
		$IdAward->removeDecorator("Label");
		$IdAward->setAttrib('Onchange',"getprogramListByAward(this.value)");
		$IdAward->removeDecorator('HtmlTag');
		$IdAward->setRegisterInArrayValidator(false);
		foreach($larrdefmsresultset as $larrdefmsresult) {
			$IdAward->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$IdAward->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$programDB = new GeneralSetup_Model_DbTable_Program();
		$ProgramList=$programDB->getAllProgram();
		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required',"false") ;
		$IdProgram->setAttrib('readOnly',"true") ;
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->addMultiOptions($ProgramList);
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$lobjsemesterModel = new GeneralSetup_Model_DbTable_Semester();
		//$lobjsemesterlist = $lobjsemesterModel->getAllsemesterListCode();
		$semesterlist = $lobjsemesterModel->getListSemester();
		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
		$IdSemester->removeDecorator("DtDdWrapper");
		$IdSemester->setAttrib('required',"true") ;
		$IdSemester->removeDecorator("Label");
		$IdSemester->removeDecorator('HtmlTag');
		$IdSemester->setRegisterInArrayValidator(false);
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$IdSemester->addMultiOptions($semesterlist);

		$lobjsemesterModel = new GeneralSetup_Model_DbTable_Semester();
		$lobjsemesterlist = $lobjsemesterModel->getAllsemesterListCode();
		$SemesterCode = new Zend_Dojo_Form_Element_FilteringSelect('SemesterCode');
		$SemesterCode->removeDecorator("DtDdWrapper");
		$SemesterCode->setAttrib('required',"true") ;
		$SemesterCode->removeDecorator("Label");
		$SemesterCode->removeDecorator('HtmlTag');
		$SemesterCode->setRegisterInArrayValidator(false);
		$SemesterCode->setAttrib('dojoType',"dijit.form.FilteringSelect");
		$SemesterCode->addMultiOptions($lobjsemesterlist);



		$CopyFromIdProgram = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdProgram');
		$CopyFromIdProgram->removeDecorator("DtDdWrapper");
		$CopyFromIdProgram->setAttrib('required',"false") ;
		$CopyFromIdProgram->removeDecorator("Label");
		$CopyFromIdProgram->removeDecorator('HtmlTag');
		$CopyFromIdProgram->setRegisterInArrayValidator(false);
		$CopyFromIdProgram->addMultiOptions($ProgramList);
		$CopyFromIdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyToIdProgram = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdProgram');
		$CopyToIdProgram->removeDecorator("DtDdWrapper");
		$CopyToIdProgram->setAttrib('required',"true") ;
		$CopyToIdProgram->removeDecorator("Label");
		$CopyToIdProgram->removeDecorator('HtmlTag');
		$CopyToIdProgram->setRegisterInArrayValidator(false);
		$CopyToIdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");



		$lobjacademicstatus = new Examination_Model_DbTable_Academicstatus();
		$list = $lobjacademicstatus->fnGetSemesterListInAcademicSetup();
		$CopyFromIdSemester = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdSemester');
		$CopyFromIdSemester->removeDecorator("DtDdWrapper");
		$CopyFromIdSemester->setAttrib('required',"true") ;
		$CopyFromIdSemester->removeDecorator("Label");
		$CopyFromIdSemester->removeDecorator('HtmlTag');
		$CopyFromIdSemester->setRegisterInArrayValidator(false);
		$CopyFromIdSemester->addMultiOptions($list);
		$CopyFromIdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyToIdSemester = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdSemester');
		$CopyToIdSemester->removeDecorator("DtDdWrapper");
		$CopyToIdSemester->setAttrib('required',"true") ;
		$CopyToIdSemester->removeDecorator("Label");
		$CopyToIdSemester->removeDecorator('HtmlTag');
		$CopyToIdSemester->setRegisterInArrayValidator(false);
		$CopyToIdSemester->addMultiOptions($semesterlist);
		$CopyToIdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyToIdScheme = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdScheme');
		$CopyToIdScheme->removeDecorator("DtDdWrapper");
		$CopyToIdScheme->setAttrib('required',"true") ;
		$CopyToIdScheme->removeDecorator("Label");
		$CopyToIdScheme->removeDecorator('HtmlTag');
		$CopyToIdScheme->setRegisterInArrayValidator(false);
		foreach($schemeList as $larrschemearr) {
			$CopyToIdScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		$CopyToIdScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyFromIdScheme = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdScheme');
		$CopyFromIdScheme->removeDecorator("DtDdWrapper");
		$CopyFromIdScheme->setAttrib('required',"true") ;
		$CopyFromIdScheme->removeDecorator("Label");
		$CopyFromIdScheme->removeDecorator('HtmlTag');
		$CopyFromIdScheme->setRegisterInArrayValidator(false);
		foreach($schemeList as $larrschemearr) {
			$CopyFromIdScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		$CopyFromIdScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$AcademicStatus = new Zend_Dojo_Form_Element_FilteringSelect('AcademicStatus');
		$AcademicStatus->removeDecorator("DtDdWrapper");
		$AcademicStatus->setAttrib('required',"true") ;
		$AcademicStatus->addMultiOptions(array('0' => 'GPA','1' => 'CGPA','2' => 'All'));
		$AcademicStatus->removeDecorator("Label");
		$AcademicStatus->removeDecorator('HtmlTag');
		$AcademicStatus->setRegisterInArrayValidator(false);
		$AcademicStatus->setAttrib('OnChange', 'fnGetMinimumValue');
		$AcademicStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$Minimum = new Zend_Form_Element_Text('Minimum');
		$Minimum->removeDecorator("DtDdWrapper");
		$Minimum->setAttrib('required',"false") ;
		$Minimum->setAttrib('constraints', '{min:0,max:999,pattern:"#.##"}');
		$Minimum->setAttrib('invalidMessage', 'Only numbers greater than/equal to 1 upto two decimal places is allowed');
		$Minimum->removeDecorator("Label");
		$Minimum->removeDecorator('HtmlTag');
		$Minimum->setAttrib('dojoType',"dijit.form.NumberTextBox");

		$Grade = new Zend_Form_Element_Text('Grade');
		$Grade->removeDecorator("DtDdWrapper");
		$Grade->setAttrib('required',"false") ;
		$Grade->setAttrib('constraints', '{min:1,max:999,pattern:"#.##"}');
		$Grade->setAttrib('invalidMessage', 'Only numbers greater than/equal to 1 upto two decimal places is allowed');
		$Grade->removeDecorator("Label");
		$Grade->removeDecorator('HtmlTag');
		$Grade->setAttrib('dojoType',"dijit.form.NumberTextBox");
		
		$Gradevalue = new Zend_Form_Element_Text('Gradevalue');
		$Gradevalue->removeDecorator("DtDdWrapper");
		$Gradevalue->setAttrib('required', "false");
		$Gradevalue->setAttrib('maxlength', '50');
		$Gradevalue->removeDecorator("Label");
		$Gradevalue->removeDecorator('HtmlTag');
		$Gradevalue->setAttrib('dojoType', "dijit.form.ValidationTextBox");		
		
		$Maximum = new Zend_Form_Element_Text('Maximum');
		$Maximum->removeDecorator("DtDdWrapper");
		$Maximum->setAttrib('required',"false") ;
		$Maximum->setAttrib('constraints', '{min:1,max:999,pattern:"#.##"}');
		$Maximum->setAttrib('invalidMessage', 'Only numbers greater than/equal to 1 upto two decimal places is allowed');
		$Maximum->removeDecorator("Label");
		$Maximum->removeDecorator('HtmlTag');
		$Maximum->setAttrib('dojoType',"dijit.form.NumberTextBox");
		
		

		$StatusEnglishName = new Zend_Form_Element_Text('StatusEnglishName');
		$StatusEnglishName->removeDecorator("DtDdWrapper");
		$StatusEnglishName->setAttrib('required',"false") ;
		$StatusEnglishName->removeDecorator("Label");
		$StatusEnglishName->removeDecorator('HtmlTag');
		//$StatusEnglishName->setRegisterInArrayValidator(false);
		$StatusEnglishName->setAttrib('dojoType',"dijit.form.ValidationTextBox")->setAttrib("propercase", "true");

		$StatusArabicName = new Zend_Form_Element_Text('StatusArabicName');
		$StatusArabicName->removeDecorator("DtDdWrapper");
		$StatusArabicName->setAttrib('required',"false") ;
		$StatusArabicName->removeDecorator("Label");
		$StatusArabicName->removeDecorator('HtmlTag');
		//$StatusArabicName->setRegisterInArrayValidator(false);
		$StatusArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox");

		$ClassHonorship = new Zend_Dojo_Form_Element_FilteringSelect('ClassHonorship');
		$ClassHonorship->removeDecorator("DtDdWrapper");
		$ClassHonorship->setAttrib('required',"true") ;
		$ClassHonorship->removeDecorator("Label");
		$ClassHonorship->removeDecorator('HtmlTag');
		$ClassHonorship->setRegisterInArrayValidator(false);
		$ClassHonorship->setAttrib('dojoType',"dijit.form.FilteringSelect");

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
			
		$CopySetup = new Zend_Form_Element_Submit('CopySetup');
		$CopySetup->dojotype="dijit.form.Button";
		$CopySetup->label = $gstrtranslate->_("CopySetup");
		$CopySetup->setAttrib('style','width:200px');
		$CopySetup->removeDecorator("DtDdWrapper");
		$CopySetup->removeDecorator("Label");
		$CopySetup->removeDecorator('HtmlTag')
		->class = "NormalBtnauto";
			
		$Clear = new Zend_Form_Element_Button('Clear');
		$Clear->setAttrib('class', 'NormalBtn');
		$Clear->setAttrib('dojoType',"dijit.form.Button");
		$Clear->label = $gstrtranslate->_("Clear");
		$Clear->setAttrib('OnClick', 'clearpageAdd()');
		$Clear->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'addAcademicdetails()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
		
		$MinimumTotalMarks = new Zend_Form_Element_Text('MinimumTotalMarks');
		$MinimumTotalMarks->removeDecorator("DtDdWrapper");
		$MinimumTotalMarks->setAttrib('required',"false") ;
		$MinimumTotalMarks->setAttrib('constraints', '{min:0,max:999,pattern:"#.##"}');
		$MinimumTotalMarks->setAttrib('invalidMessage', 'Only numbers greater than/equal to 1 upto two decimal places is allowed');
		$MinimumTotalMarks->removeDecorator("Label");
		$MinimumTotalMarks->removeDecorator('HtmlTag');
		$MinimumTotalMarks->setAttrib('dojoType',"dijit.form.NumberTextBox");
		
		$MaximumTotalMarks = new Zend_Form_Element_Text('MaximumTotalMarks');
		$MaximumTotalMarks->removeDecorator("DtDdWrapper");
		$MaximumTotalMarks->setAttrib('required',"false") ;
		$MaximumTotalMarks->setAttrib('constraints', '{min:0,max:999,pattern:"#.##"}');
		$MaximumTotalMarks->setAttrib('invalidMessage', 'Only numbers greater than/equal to 1 upto two decimal places is allowed');
		$MaximumTotalMarks->removeDecorator("Label");
		$MaximumTotalMarks->removeDecorator('HtmlTag');
		$MaximumTotalMarks->setAttrib('dojoType',"dijit.form.NumberTextBox");
		
		
		
		$larrlandscapeTyoe = $lobjdeftype->fnGetDefinations('Landscape');
		$LandscapeType = new Zend_Dojo_Form_Element_FilteringSelect('LandscapeType');
		$LandscapeType->removeDecorator("DtDdWrapper");
		$LandscapeType->setAttrib('required',"false") ;
		$LandscapeType->removeDecorator("Label");
		$LandscapeType->removeDecorator('HtmlTag');
		$LandscapeType->setRegisterInArrayValidator(false);
		$LandscapeType->setAttrib('dojoType',"dijit.form.FilteringSelect");
		foreach($larrlandscapeTyoe as $larrdefmsresult) {
			$LandscapeType->addMultiOption($larrdefmsresult['idDefinition'],$larrdefmsresult['DefinitionDesc']);
		}
		$LandscapeType->setAttrib('Onchange', 'checklevel(this)');
		
		$Level = new Zend_Form_Element_Text('Level');
		$Level->removeDecorator("DtDdWrapper");
		$Level->setAttrib('required',"false") ;
		$Level->removeDecorator("Label");
		$Level->removeDecorator('HtmlTag');				
		$Level->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		
		$Semester = new Zend_Form_Element_Text('Semester');
		$Semester->removeDecorator("DtDdWrapper");
		$Semester->setAttrib('required',"false") ;
		$Semester->removeDecorator("Label");
		$Semester->removeDecorator('HtmlTag');		
		$Semester->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		
		
		$AddCalMode = new Zend_Form_Element_Button('AddCalMode');
		$AddCalMode->setAttrib('class', 'NormalBtn');
		$AddCalMode->label = $gstrtranslate->_("Add");
		$AddCalMode->setAttrib('dojoType',"dijit.form.Button");
		$AddCalMode->setAttrib('OnClick', 'addcalmode()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');
		
		//form elements
		$this->addElements(array($IdAcademicStatus,
				$IdProgram,
				$IdSemester,
				$CopyFromIdProgram,
				$CopyToIdProgram,
				$CopyFromIdSemester,
				$CopyToIdSemester,
				$AcademicStatus,
				$Minimum,
				$Maximum,
				$StatusEnglishName,
				$StatusArabicName,
				$ClassHonorship,
				$UpdDate,
				$UpdUser,
				$Active,
				$Save,
				$CopySetup,
				$Clear,$Add, $BasedOn,
				$IdScheme,$IdAward,$SemesterCode,
				$CopyBasedOn,
				$CopyToIdScheme, $CopyFromIdScheme,
				$MinimumTotalMarks,$MaximumTotalMarks,
				$Grade,$LandscapeType,$Level,$Semester,$AddCalMode,
				$Gradevalue
		));

	}
}