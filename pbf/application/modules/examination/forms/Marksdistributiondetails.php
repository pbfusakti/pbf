<?php
class Examination_Form_Marksdistributiondetails extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";

		$lobjassessmenttype = new Examination_Model_DbTable_Assessmenttype();
		$typeList = $lobjassessmenttype->fnGettypeListList();


		$componenttype = new Zend_Dojo_Form_Element_FilteringSelect('componenttype');
		$componenttype->removeDecorator("DtDdWrapper");
		$componenttype->setAttrib('required',"false") ;
		$componenttype->removeDecorator("Label");
		$componenttype->removeDecorator('HtmlTag');
		//$componenttype->setAttrib('OnChange', 'fnGetSchmeList');
		$componenttype->addmultioptions($typeList);
		//$IdFaculty->setRegisterInArrayValidator(false);
		$componenttype->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$lobjassessmentItem = new Examination_Model_DbTable_Assessmentitem();
		$itemList = $lobjassessmentItem->fnGetitemListList();
		$componentitem = new Zend_Dojo_Form_Element_FilteringSelect('componentitem');
		$componentitem->removeDecorator("DtDdWrapper");
		$componentitem->setAttrib('required',"false") ;
		$componentitem->removeDecorator("Label");
		$componentitem->removeDecorator('HtmlTag');
		$componentitem->addmultioptions($itemList);
		//$componentitem->setAttrib('OnChange', 'fnGetSchmeList');
		//$IdFaculty->setRegisterInArrayValidator(false);
		$componentitem->setAttrib('dojoType',"dijit.form.FilteringSelect");


			
		$IdMarksDistributionDetails = new Zend_Form_Element_Hidden('IdMarksDistributionDetails');
		$IdMarksDistributionDetails->removeDecorator("DtDdWrapper");
		$IdMarksDistributionDetails->removeDecorator("Label");
		$IdMarksDistributionDetails->removeDecorator('HtmlTag');

		$IdMarksDistributionMaster = new Zend_Form_Element_Hidden('IdMarksDistributionMaster');
		$IdMarksDistributionMaster->removeDecorator("DtDdWrapper");
		$IdMarksDistributionMaster->removeDecorator("Label");
		$IdMarksDistributionMaster->removeDecorator('HtmlTag');

		$Weightage = new Zend_Form_Element_Text('Weightage',array('regExp'=>"[0-9]+(\.[0-9][0-9]?)?",'invalidMessage'=>"Digits Only"));
		$Weightage->setAttrib('required',"true");
		$Weightage->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$ComponentName = new Zend_Form_Element_Text('ComponentName');
		$ComponentName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$ComponentName->setAttrib('required',"true")
		->setAttrib('maxlength','100')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$PassMark = new Zend_Form_Element_Text('PassMark',array('regExp'=>"[0-9]+(\.[0-9][0-9]?)?",'invalidMessage'=>"Digits Only"));
		$PassMark->setAttrib('required',"true");
		$PassMark->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$TotalMark = new Zend_Form_Element_Text('TotalMark',array('regExp'=>"[0-9]+(\.[0-9][0-9]?)?",'invalidMessage'=>"Digits Only"));
		$TotalMark->setAttrib('required',"true");
		$TotalMark->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
			
		$idStaff = new Zend_Dojo_Form_Element_FilteringSelect('idStaff');
		$idStaff->removeDecorator("DtDdWrapper");
		$idStaff->setAttrib('required',"true") ;
		$idStaff->removeDecorator("Label");
		$idStaff->removeDecorator('HtmlTag');
		$idStaff->setRegisterInArrayValidator(false);
		$idStaff->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$idSemester = new Zend_Dojo_Form_Element_FilteringSelect('idSemester');
		$idSemester->removeDecorator("DtDdWrapper");
		$idSemester->setAttrib('required',"true") ;
		$idSemester->removeDecorator("Label");
		$idSemester->removeDecorator('HtmlTag');
		$idSemester->setRegisterInArrayValidator(false);
		$idSemester->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$PassStatus = new Zend_Dojo_Form_Element_FilteringSelect('PassStatus');
		$PassStatus->removeDecorator("DtDdWrapper");
		$PassStatus->setAttrib('required',"true") ;
		$PassStatus->removeDecorator("Label");
		$PassStatus->removeDecorator('HtmlTag');
		$PassStatus->setRegisterInArrayValidator(false);
		$PassStatus->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

			
		$Add = new Zend_Form_Element_Button('Add');
		$Add->setAttrib('class', 'NormalBtn');
		$Add->setAttrib('dojoType',"dijit.form.Button");
		$Add->setAttrib('OnClick', 'addMarksDistributionDetails()')
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

		$percentage = new Zend_Form_Element_Text('percentage');
		$percentage->setAttrib('required',"false");
		$percentage->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$Marks = new Zend_Form_Element_Text('Marks');
		$Marks->setAttrib('required',"false");
		$Marks->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');




		//form elements
		$this->addElements(array(
				$IdMarksDistributionDetails,
				$IdMarksDistributionMaster,
				$Weightage,
				$ComponentName,
				$PassMark,
				$TotalMark,
				$PassStatus,
				$UpdDate,
				$UpdUser,$idStaff,$idSemester,
				$Add,
				$Save,
				$clear,
				$componentitem,
				$componenttype,
				$percentage,
				$Marks
		));

	}
}