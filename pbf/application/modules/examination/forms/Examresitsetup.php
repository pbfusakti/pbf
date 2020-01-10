<?php
class Examination_Form_Examresitsetup extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');

		$FinalMarks= new Zend_Form_Element_Select('FinalMarks');
		$FinalMarks->removeDecorator("DtDdWrapper")
		->setAttrib('required',"true")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect");



		$gradelist = array('1' => 'A','2'=>'A+','3'=>'B');
		$MinimumGrade= new Zend_Form_Element_Select('MinimumGrade');
		$MinimumGrade->removeDecorator("DtDdWrapper")
		->setAttrib('required',"true")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect")
		->addmultioptions($gradelist);


		$IdUniversity = new Zend_Form_Element_Hidden('IdUniversity');
		$IdUniversity->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$ApplicationCount = new Zend_Form_Element_Text('ApplicationCount');
		$ApplicationCount->removeDecorator("DtDdWrapper");
		$ApplicationCount->setAttrib('required',"true") ;
		$ApplicationCount->setAttrib('constraints', '{min:1,max:999,pattern:"#.##"}');
		$ApplicationCount->setAttrib('invalidMessage', 'Only numbers greater than/equal to 1 upto two decimal places is allowed');
		$ApplicationCount->removeDecorator("Label");
		$ApplicationCount->removeDecorator('HtmlTag');
		$ApplicationCount->setAttrib('dojoType',"dijit.form.NumberTextBox");

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag');
		$Save->class = "NormalBtn";

		$UpdatedOn = new Zend_Form_Element_Hidden('UpdatedOn');
		$UpdatedOn->removeDecorator("DtDdWrapper");
		$UpdatedOn->removeDecorator("Label");
		$UpdatedOn->removeDecorator('HtmlTag');

		$UpdatedBy  = new Zend_Form_Element_Hidden('UpdatedBy');
		$UpdatedBy->removeDecorator("DtDdWrapper");
		$UpdatedBy->removeDecorator("Label");
		$UpdatedBy->removeDecorator('HtmlTag');

		$this->addElements(array($ApplicationCount,$Save,$UpdatedOn,$UpdatedBy,
				$IdUniversity,$FinalMarks,$MinimumGrade
		));



	}
}

?>
