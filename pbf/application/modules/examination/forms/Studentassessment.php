<?php
class Examination_Form_Studentassessment extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$month= date("m"); // Month value
		$day= date("d"); //today's date
		$year= date("Y"); // Year value
		$yesterdaydate= date('Y-m-d', mktime(0,0,0,$month,($day),$year));
		$joiningdate = "{max:'$yesterdaydate',datePattern:'dd-MM-yyyy'}";
		
		$lobjdeftype = new App_Model_Definitiontype();

		$lobjschemesetupmodel = new GeneralSetup_Model_DbTable_Schemesetup();
		$schemeList = $lobjschemesetupmodel->fnGetSchemeDetails();
		$IdScheme = new Zend_Dojo_Form_Element_FilteringSelect('IdScheme');
		$IdScheme->removeDecorator("DtDdWrapper");
		$IdScheme->setAttrib('required',"true") ;
		$IdScheme->removeDecorator("Label");
		$IdScheme->removeDecorator('HtmlTag');
		$IdScheme->setRegisterInArrayValidator(false);		
		foreach($schemeList as $larrschemearr) {
			$IdScheme->addMultiOption($larrschemearr['IdScheme'],$larrschemearr['EnglishDescription']);
		}
		$IdScheme->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$lobjplacementtest = new Application_Model_DbTable_Placementtest();
		$ProgramList=$lobjplacementtest->fnGetProgramMaterList();
		$IdProgram = new Zend_Dojo_Form_Element_FilteringSelect('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper");
		$IdProgram->setAttrib('required',"true") ;		
		$IdProgram->removeDecorator("Label");
		$IdProgram->removeDecorator('HtmlTag');
		$IdProgram->setRegisterInArrayValidator(false);
		$IdProgram->addMultiOptions($ProgramList);
		$IdProgram->setAttrib('dojoType',"dijit.form.FilteringSelect");	
		

		$IdStudent = new Zend_Form_Element_Text('IdStudent');
		$IdStudent->removeDecorator("DtDdWrapper");
		$IdStudent->setAttrib('required',"false") ;
		$IdStudent->removeDecorator("Label");
		$IdStudent->removeDecorator('HtmlTag');		
		$IdStudent->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		
		$StudentName = new Zend_Form_Element_Text('StudentName');
		$StudentName->removeDecorator("DtDdWrapper");
		$StudentName->setAttrib('required',"false") ;
		$StudentName->removeDecorator("Label");
		$StudentName->removeDecorator('HtmlTag');		
		$StudentName->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		

				
		$this->addElements(array(
			$IdScheme,$IdProgram,$IdStudent,
			$StudentName
		));

	}
}