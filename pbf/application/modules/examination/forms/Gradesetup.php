<?php
class Examination_Form_Gradesetup extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";

			
		$IdGradeSetUp = new Zend_Form_Element_Hidden('IdGradeSetUp');
		$IdGradeSetUp->removeDecorator("DtDdWrapper");
		$IdGradeSetUp->removeDecorator("Label");
		$IdGradeSetUp->removeDecorator('HtmlTag');

		$BasedOn  = new Zend_Form_Element_Radio('BasedOn');
		$BasedOn->setAttrib('dojoType',"dijit.form.RadioButton");
		$BasedOn->addMultiOptions(array('0' => 'Scheme & Award','1' => 'Program & Subject','2' => 'Program', '3'=>'University'))
		->setvalue('1')
		->setSeparator('&nbsp;')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('onclick', 'fnToggleProgramSubjectDetails(this.value)');

		$IdSemester = new Zend_Dojo_Form_Element_FilteringSelect('IdSemester');
		$IdSemester->removeDecorator("DtDdWrapper");
		$IdSemester->setAttrib('required',"true") ;
		$IdSemester->removeDecorator("Label");
		$IdSemester->removeDecorator('HtmlTag');
		$IdSemester->setRegisterInArrayValidator(false);
		$IdSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$IdScheme = new Zend_Dojo_Form_Element_FilteringSelect('IdScheme');
		$IdScheme->removeDecorator("DtDdWrapper");
		$IdScheme->setAttrib('required',"false") ;
		$IdScheme->removeDecorator("Label");
		$IdScheme->removeDecorator('HtmlTag');
		$IdScheme->setRegisterInArrayValidator(false);
		//$IdScheme->setAttrib('onchange', "fnToggleProgram()");
		$IdScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$IdAward = new Zend_Dojo_Form_Element_FilteringSelect('IdAward');
		$IdAward->removeDecorator("DtDdWrapper");
		$IdAward->setAttrib('required',"false") ;
		$IdAward->removeDecorator("Label");
		$IdAward->removeDecorator('HtmlTag');
		// $IdAward->setAttrib('onchange', "fnToggleProgram()");
		$IdAward->setRegisterInArrayValidator(false);
		$IdAward->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required',"false") ;
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->setAttrib('onchange', "fnToggleSubject(this.value)");
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$IdSubject = new Zend_Dojo_Form_Element_FilteringSelect('IdSubject');
		$IdSubject->removeDecorator("DtDdWrapper");
		$IdSubject->setAttrib('required',"false") ;
		$IdSubject->removeDecorator("Label");
		$IdSubject->removeDecorator('HtmlTag');
		$IdSubject->setRegisterInArrayValidator(false);
		$IdSubject->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$idlandscape = new Zend_Dojo_Form_Element_FilteringSelect('IdLandscape');
		$idlandscape->removeDecorator("DtDdWrapper");
		$idlandscape->setAttrib('required',"false") ;
		$idlandscape->removeDecorator("Label");
		$idlandscape->removeDecorator('HtmlTag');
		$idlandscape->setRegisterInArrayValidator(false);
		$idlandscape->setAttrib('dojoType',"dijit.form.FilteringSelect");
		
		

		$EffectiveDate = new Zend_Dojo_Form_Element_DateTextBox('EffectiveDate');
		$EffectiveDate->setAttrib('dojoType',"dijit.form.DateTextBox");
		$EffectiveDate->setAttrib('constraints', "$joiningdate");
		$EffectiveDate->setAttrib('required',"true");
		$EffectiveDate->removeDecorator("DtDdWrapper");
		$EffectiveDate->setAttrib('title',"dd-mm-yyyy");
		$EffectiveDate->removeDecorator("Label");
		$EffectiveDate->removeDecorator('HtmlTag');

			

		$GradeDesc = new Zend_Form_Element_Text('GradeDesc');
		$GradeDesc->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$GradeDesc->setAttrib('required',"false")
		->setAttrib('maxlength','20')->setAttrib('trim',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')->setAttrib("propercase", "true");

		$GradePoint = new Zend_Form_Element_Text('GradePoint',array('invalidMessage'=>"Digits Only, Range should be 0-999, upto 2 decimals allowed"));
		$GradePoint->setAttrib('required',"false");
		$GradePoint->setAttrib('dojoType',"dijit.form.NumberTextBox")
		->setAttrib('constraints',"{min:0,max:999,pattern:'#.##'}")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$MinPoint = new Zend_Form_Element_Text('MinPoint',array('invalidMessage'=>"Digits Only, Range should be 0-999, upto 2 decimals allowed"));
		$MinPoint->setAttrib('required',"false");
		$MinPoint->setAttrib('dojoType',"dijit.form.NumberTextBox")
		->setAttrib('constraints',"{min:0,max:999,pattern:'#.##'}")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$MaxPoint = new Zend_Form_Element_Text('MaxPoint',array('invalidMessage'=>"Digits Only, Range should be 0-999, upto 2 decimals allowed"));
		$MaxPoint->setAttrib('required',"false");
		$MaxPoint->setAttrib('dojoType',"dijit.form.NumberTextBox")
		->setAttrib('constraints',"{min:0,max:999,pattern:'#.##'}")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$Grade = new Zend_Dojo_Form_Element_FilteringSelect('Grade');
		$Grade->removeDecorator("DtDdWrapper");
		$Grade->setAttrib('required',"false") ;
		$Grade->removeDecorator("Label");
		$Grade->removeDecorator('HtmlTag');
		$Grade->setRegisterInArrayValidator(false);
		$Grade->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$Rank = new Zend_Form_Element_Text('Rank',array('invalidMessage'=>"Digits Only, Range should be 0-9, no decimals allowed"));
		$Rank->setAttrib('required',"false");
		$Rank->setAttrib('dojoType',"dijit.form.NumberTextBox")
		->setAttrib('constraints',"{min:0,max:9,pattern:'#'}")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		/*$Pass = new Zend_Form_Element_Text('Pass');
		 $Pass->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Pass->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');*/
		$Pass  = new Zend_Form_Element_Radio('Pass');
		$Pass->setAttrib('dojoType',"dijit.form.RadioButton");
		$Pass->addMultiOptions(array('0' => 'No','1' => 'Yes'))
		->setvalue('0')
		->setSeparator('&nbsp;')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		
		$Countable  = new Zend_Form_Element_Radio('Countable');
		$Countable->setAttrib('dojoType',"dijit.form.RadioButton");
		$Countable->addMultiOptions(array('0' => 'Countable','1' => 'Not Countable'))
		->setvalue('0')
		->setSeparator('&nbsp;')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		//$DescEnglishName = new Zend_Form_Element_Text('DescEnglishName');
		//$DescEnglishName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		//$DescEnglishName->setAttrib('required',"true")
		//->setAttrib('maxlength','100')
		//->removeDecorator("DtDdWrapper")
		//->removeDecorator("Label")
		//->removeDecorator('HtmlTag');
		$DescEnglishName = new Zend_Dojo_Form_Element_FilteringSelect('DescEnglishName');
		$DescEnglishName->removeDecorator("DtDdWrapper");
		$DescEnglishName->setAttrib('required',"false") ;
		$DescEnglishName->removeDecorator("Label");
		$DescEnglishName->removeDecorator('HtmlTag');
		$DescEnglishName->setRegisterInArrayValidator(false);
		$DescEnglishName->setAttrib('dojoType',"dijit.form.FilteringSelect");





		//$DescArabicName = new Zend_Form_Element_Text('DescArabicName');
		//$DescArabicName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		//$DescArabicName->setAttrib('required',"true")
		//->setAttrib('maxlength','100')
		//->removeDecorator("DtDdWrapper")
		//->removeDecorator("Label")
		//->removeDecorator('HtmlTag');
		$DescArabicName = new Zend_Dojo_Form_Element_FilteringSelect('DescArabicName');
		$DescArabicName->removeDecorator("DtDdWrapper");
		$DescArabicName->setAttrib('required',"false") ;
		$DescArabicName->removeDecorator("Label");
		$DescArabicName->removeDecorator('HtmlTag');
		$DescArabicName->setRegisterInArrayValidator(false);
		$DescArabicName->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$DefaultLanguage = new Zend_Form_Element_Text('DefaultLanguage');
		$DefaultLanguage->removeDecorator("DtDdWrapper");
		$DefaultLanguage->setAttrib('required',"false")->setAttrib('trim',"true") ;
		$DefaultLanguage->removeDecorator("Label");
		$DefaultLanguage->removeDecorator('HtmlTag');
		$DefaultLanguage->setAttrib('dojoType',"dijit.form.ValidationTextBox");


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
			
		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'addGradedetails()')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag')->class = "NormalBtn";

		$SaveAdd = new Zend_Form_Element_Submit('SaveAdd');
		$SaveAdd->dojotype="dijit.form.Button";
		$SaveAdd->label = $gstrtranslate->_("Save");
		$SaveAdd->removeDecorator("DtDdWrapper");
		$SaveAdd->removeDecorator("Label")->setAttrib('OnClick', 'return validationAdd()');
		$SaveAdd->removeDecorator('HtmlTag')->class = "NormalBtn";

			
		$CopySetup = new Zend_Form_Element_Submit('CopySetup');
		$CopySetup->dojotype="dijit.form.Button";
		$CopySetup->label = $gstrtranslate->_("Copy Setup");
		$CopySetup->setAttrib('style','width:200px');
		$CopySetup->removeDecorator("DtDdWrapper");
		$CopySetup->removeDecorator("Label");
		$CopySetup->removeDecorator('HtmlTag')
		->class = "NormalBtnauto";
			
		$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType',"dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'clearpageAdd()');
		$clear->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');


		// copy from
		$CopyBasedOn  = new Zend_Form_Element_Radio('CopyBasedOn');
		$CopyBasedOn->setAttrib('dojoType',"dijit.form.RadioButton");
		$CopyBasedOn->addMultiOptions(array('0' => 'Scheme & Award','1' => 'Program & Subject','2' => 'Program'))
		->setvalue('1')
		->setSeparator('&nbsp;')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('onclick', 'fnToggleProgramSubjectDetailsCopy(this.value)');

		$CopyFromIdSemester = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdSemester');
		$CopyFromIdSemester->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyToIdSemester = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdSemester');
		$CopyToIdSemester->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyFromIdScheme = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdScheme');
		$CopyFromIdScheme->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('onchange', "fnToggleProgramCopy()")
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyToIdScheme = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdScheme');
		$CopyToIdScheme->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyFromIdAward = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdAward');
		$CopyFromIdAward->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('onchange', "fnToggleProgramCopy()")
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyToIdAward = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdAward');
		$CopyToIdAward->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");



		$CopyFromIdProgram = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdProgram');
		$CopyFromIdProgram->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('onchange', "fnToggleSubjectCopy(this.value)")
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$CopyToIdProgram = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdProgram');
		$CopyToIdProgram->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('onchange', "fnToggleSubjectCopyTo(this.value)")
		->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyFromIdSubject = new Zend_Dojo_Form_Element_FilteringSelect('CopyFromIdSubject');
		$CopyFromIdSubject->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$CopyToIdSubject = new Zend_Dojo_Form_Element_FilteringSelect('CopyToIdSubject');
		$CopyToIdSubject->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setRegisterInArrayValidator(false)
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		//form elements
		$this->addElements(array($IdGradeSetUp,
				$BasedOn,
				$IdProgram,
				$IdSubject,
				$IdSemester,
				$IdScheme,
				$IdAward,
				$EffectiveDate,
				$CopyFromIdProgram,
				$CopyFromIdSemester,
				$CopyToIdProgram,
				$CopyToIdSemester,
				$GradeDesc,
				$GradePoint,
				$MinPoint,
				$MaxPoint,
				$Grade,
				$Rank,
				$Pass,
				$Countable,
				$DescEnglishName,
				$DescArabicName,
				$DefaultLanguage,
				$UpdDate,
				$UpdUser,
				$Active,
				$Add,
				$Save,$SaveAdd,
				$CopySetup,
				$clear,
				$idlandscape,
				$CopyBasedOn, $CopyFromIdProgram,$CopyFromIdSubject, $CopyFromIdSemester,$CopyFromIdScheme, $CopyFromIdAward,
				$CopyToIdProgram,$CopyToIdSubject, $CopyToIdSemester,$CopyToIdScheme, $CopyToIdAward
		));

	}
}