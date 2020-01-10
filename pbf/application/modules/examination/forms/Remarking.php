<?php

class Examination_Form_Remarking extends Zend_Dojo_Form
{

	public function init()
	{

		$gstrtranslate =Zend_Registry::get('Zend_Translate');


		$UpdUser = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$IdUniversity = new Zend_Form_Element_Hidden('IdUniversity');
		$IdUniversity->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$MaxAppeal = new Zend_Form_Element_Text('MaxAppeal');
		$MaxAppeal->setAttrib('dojoType',"dijit.form.ValidationTextBox")
		->setAttrib('required',"true")
		->setAttrib('class', 'txt_put')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');


		$Semester = new Zend_Form_Element_Select('SemesterCode');
		$Semester->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$Semester->removeDecorator("DtDdWrapper")
		->setAttrib('required',"true")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$ChooseMarks= new Zend_Form_Element_Select('ChooseMarks');
		$ChooseMarks->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$ChooseMarks->removeDecorator("DtDdWrapper")
		->setAttrib('required',"true")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')
		->setAttrib('dojoType',"dijit.form.FilteringSelect");

		$field1 = new Zend_Form_Element_Text('field1');
		$field1->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field1->setAttrib('class', 'txt_put')
		->setAttrib('required',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		$field2 = new Zend_Form_Element_Text('field2');
		$field2->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field2->setAttrib('class', 'txt_put')
		->setAttrib('required',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		$field3 = new Zend_Form_Element_Text('field3');
		$field3->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field3->setAttrib('class', 'txt_put')
		->setAttrib('required',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		$field4 = new Zend_Form_Element_Text('field4');
		$field4->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field4->setAttrib('class', 'txt_put')
		->setAttrib('required',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');
		$field5 = new Zend_Form_Element_Text('field5');
		$field5->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field5->setAttrib('class', 'txt_put')
		->setAttrib('required',"true")
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$field6 = new Zend_Form_Element_Text('field6');
		$field6->setAttrib('dojoType',"dijit.form.ValidationTextBox");
		$field6->setAttrib('class', 'txt_put')
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

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->label = $gstrtranslate->_("Save");
		$Save->dojotype="dijit.form.Button";
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";
			
			
		$Add = new Zend_Form_Element_Button('Add');
		$Add->label = $gstrtranslate->_("Add");
		$Add->dojotype="dijit.form.Button";
		$Add->removeDecorator("DtDdWrapper");
		$Add->removeDecorator('HtmlTag')
		->class = "NormalBtn";


		$Clear = new Zend_Form_Element_Submit('Clear');
		$Clear->dojotype="dijit.form.Button";
		$Clear->label = $gstrtranslate->_("Clear");
		$Clear->setAttrib('class', 'NormalBtn')
		->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');



		$this->addElements(array($UpdUser,$Clear,$UpdDate, $Semester,$IdUniversity, $ChooseMarks, $MaxAppeal, $Add, $Save, $field2, $field23, $field24, $field25, $field26, $field27, $field1, $field3, $field4, $field5, $field6));

		/* Form Elements & Other Definitions Here ... */
	}


}
