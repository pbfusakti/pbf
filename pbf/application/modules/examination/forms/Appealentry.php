<?php
class Examination_Form_Appealentry extends Zend_Dojo_Form { //Formclass for the user module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');
			
		$IdAppealEntry  = new Zend_Form_Element_Hidden('IdAppealEntry');
		$IdAppealEntry->removeDecorator("DtDdWrapper");
		$IdAppealEntry->removeDecorator("Label");
		$IdAppealEntry->removeDecorator('HtmlTag');

		$IdAppeal  = new Zend_Form_Element_Hidden('IdAppeal');
		$IdAppeal->removeDecorator("DtDdWrapper");
		$IdAppeal->removeDecorator("Label");
		$IdAppeal->removeDecorator('HtmlTag');

		$NewMarks = new Zend_Form_Element_Text('NewMarks',array('regExp'=>"^[1-9]\d*(\.\d+)?$",'invalidMessage'=>"Only digits"));
		$NewMarks->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$NewMarks->setAttrib('required',"true")
		->setAttrib('maxlength','20')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$DateOfRemarks = new Zend_Form_Element_Hidden('DateOfRemarks');
		$DateOfRemarks->removeDecorator("DtDdWrapper");
		$DateOfRemarks->removeDecorator("Label");
		$DateOfRemarks->removeDecorator('HtmlTag');

		$RemarkedBy  = new Zend_Form_Element_Hidden('RemarkedBy');
		$RemarkedBy->removeDecorator("DtDdWrapper");
		$RemarkedBy->removeDecorator("Label");
		$RemarkedBy->removeDecorator('HtmlTag');

		$Idverifiermarks  = new Zend_Form_Element_Hidden('Idverifiermarks');
		$Idverifiermarks->removeDecorator("DtDdWrapper");
		$Idverifiermarks->removeDecorator("Label");
		$Idverifiermarks->removeDecorator('HtmlTag');

		$prevmarks  = new Zend_Form_Element_Hidden('prevmarks');
		$prevmarks->removeDecorator("DtDdWrapper");
		$prevmarks->removeDecorator("Label");
		$prevmarks->removeDecorator('HtmlTag');
			
		$Save = new Zend_Form_Element_Submit('Save');
		$Save->dojotype="dijit.form.Button";
		$Save->label = $gstrtranslate->_("Save");
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator("Label");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$this->addElements(array($IdAppealEntry,
				$IdAppeal,
				$NewMarks,
				$DateOfRemarks,$prevmarks,
				$RemarkedBy,$Idverifiermarks,
				$Save
		));
	}
}