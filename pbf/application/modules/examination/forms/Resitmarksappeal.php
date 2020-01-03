<?php
class Examination_Form_Resitmarksappeal extends Zend_Dojo_Form{
	public function init()
	{

		$gstrtranslate =Zend_Registry::get('Zend_Translate');

		$StudentCode = new Zend_Form_Element_Text('StudentCode');
		$StudentCode->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('required',"true")
		->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$Studentcode = new Zend_Form_Element_Hidden('Studentcode');
		$Studentcode->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');




		$Save = new Zend_Form_Element_Submit('Save');
		$Save->label = $gstrtranslate->_("Save");
		$Save->dojotype="dijit.form.Button";
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$SemesterCode = new Zend_Form_Element_Select('SemesterCode');
		$SemesterCode->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$SemesterCode->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdCourse = new Zend_Form_Element_Select('IdCourse');
		$IdCourse->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$IdCourse->removeDecorator("DtDdWrapper")
		->setAttrib('required',"false")
		->removeDecorator("Label")
		->setAttrib('OnChange', 'fngetComponent')
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$IdProgram = new Zend_Form_Element_Hidden('IdProgram');
		$IdProgram->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$IdStudent = new Zend_Form_Element_Hidden('IdStudent');
		$IdStudent->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$IdComponent = new Zend_Form_Element_Hidden('IdComponent');
		$IdComponent->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$Add = new Zend_Form_Element_Button('Add');
		$Add->label = $gstrtranslate->_("Add");
		$Add->dojotype="dijit.form.Button";
		$Add->removeDecorator("DtDdWrapper");
		$Add->setAttrib('OnClick', 'addappeal()');
		$Add->removeDecorator('HtmlTag')
		->class = "NormalBtn";


		$Clear = new Zend_Form_Element_Button('Clear');
		$Clear->dojotype="dijit.form.Button";
		$Clear->label = $gstrtranslate->_("Clear");
		$Clear->setAttrib('OnClick', 'clearappeal()');
		$Clear->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$UpdatedBy = new Zend_Form_Element_Hidden('UpdatedBy');
		$UpdatedBy->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$UpdatedOn = new Zend_Form_Element_Hidden('UpdatedOn');
		$UpdatedOn->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$ProcessedOn = new Zend_Form_Element_Hidden('ProcessedOn');
		$ProcessedOn->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$ProcessedBy = new Zend_Form_Element_Hidden('ProcessedBy');
		$ProcessedBy->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');




















		$IdUniversity = new Zend_Form_Element_Hidden('IdUniversity');
		$IdUniversity->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');



		$ChooseMarks= new Zend_Form_Element_Select('ChooseMarks');
		$ChooseMarks->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$ChooseMarks->removeDecorator("DtDdWrapper")
		->setAttrib('required',"true")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field2 = new Zend_Form_Element_Text('field2');
		$field2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field2->setAttrib('class', 'txt_put')
		->setAttrib('required',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field23 = new Zend_Form_Element_Select('field23');
		$field23->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field23->removeDecorator("DtDdWrapper");
		$field23->setAttrib('required',"true");
		$field23->removeDecorator("Label");
		$field23->removeDecorator('HtmlTag');
		$field23->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$field24 = new Zend_Form_Element_Select('field24');
		$field24->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field24->removeDecorator("DtDdWrapper");
		$field24->setAttrib('required',"false");
		$field24->removeDecorator("Label");
		$field24->removeDecorator('HtmlTag');
		$field24->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field25 = new Zend_Form_Element_Select('field25');
		$field25->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field25->removeDecorator("DtDdWrapper");
		$field25->setAttrib('required',"true");
		$field25->removeDecorator("Label");
		$field25->removeDecorator('HtmlTag');
		$field25->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field26 = new Zend_Form_Element_Select('field26');
		$field26->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field26->removeDecorator("DtDdWrapper");
		$field26->setAttrib('required',"true");
		$field26->removeDecorator("Label");
		$field26->removeDecorator('HtmlTag');
		$field26->setAttrib('dojoType',"dijit.form.FilteringSelect");


		$field27 = new Zend_Form_Element_Select('field27');
		$field27->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field27->removeDecorator("DtDdWrapper");
		$field27->setAttrib('required',"true");
		$field27->removeDecorator("Label");
		$field27->removeDecorator('HtmlTag');
		$field27->setAttrib('dojoType',"dijit.form.FilteringSelect");







		$this->addElements(array(
				$StudentCode,$Save,$SemesterCode,$IdProgram,$IdComponent,
				$IdCourse,$Add,$Clear,$UpdatedBy,$UpdatedOn,$ProcessedBy,
				$ProcessedOn,$IdStudent,$Studentcode
		));

		/* Form Elements & Other Definitions Here ... */
	}
}

?>
