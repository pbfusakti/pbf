<?php

class Examination_Form_Assessment extends Zend_Dojo_Form {

	public function init() {
		$gstrtranslate = Zend_Registry::get('Zend_Translate');

		$IdDescription = new Zend_Form_Element_Text('IdDescription');
		$IdDescription->removeDecorator("DtDdWrapper");
		$IdDescription->setAttrib('required', "true");
		$IdDescription->setAttrib('maxlength', '50');
		$IdDescription->removeDecorator("Label");
		$IdDescription->removeDecorator('HtmlTag');
		$IdDescription->setAttrib('dojoType', "dijit.form.ValidationTextBox");

		$IdDescriptionItem = new Zend_Form_Element_Text('IdDescriptionItem');
		$IdDescriptionItem->removeDecorator("DtDdWrapper");
		$IdDescriptionItem->setAttrib('required', "true");
		$IdDescriptionItem->setAttrib('maxlength', '50');
		$IdDescriptionItem->removeDecorator("Label");
		$IdDescriptionItem->removeDecorator('HtmlTag');
		$IdDescriptionItem->setAttrib('dojoType', "dijit.form.ValidationTextBox");


		$Description = new Zend_Form_Element_Textarea('Description');
		$Description->setAttrib('cols', '30')
		->setAttrib('rows', '5')
		->setAttrib('style', 'width = 10%;')
		->setAttrib('maxlength', '250')
		->setAttrib('required', 'true')
		->setAttrib('dojoType', "dijit.form.SimpleTextarea")
		->setAttrib('style', 'margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')->setAttrib("propercase", "true");

		$DescriptionItem = new Zend_Form_Element_Textarea('DescriptionItem');
		$DescriptionItem->setAttrib('cols', '30')
		->setAttrib('rows', '5')
		->setAttrib('style', 'width = 10%;')
		->setAttrib('maxlength', '250')
		->setAttrib('required', 'true')
		->setAttrib('dojoType', "dijit.form.SimpleTextarea")
		->setAttrib('style', 'margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag')->setAttrib("propercase", "true");

		$DescriptionDefaultlang = new Zend_Form_Element_Textarea('DescriptionDefaultlang');
		$DescriptionDefaultlang->setAttrib('cols', '30')
		->setAttrib('rows', '5')
		->setAttrib('style', 'width = 10%;')
		->setAttrib('maxlength', '250')
		->setAttrib('required', 'true')
		->setAttrib('dojoType', "dijit.form.SimpleTextarea")
		->setAttrib('style', 'margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$DescriptionDefaultlangItem = new Zend_Form_Element_Textarea('DescriptionDefaultlangItem');
		$DescriptionDefaultlangItem->setAttrib('cols', '30')
		->setAttrib('rows', '5')
		->setAttrib('style', 'width = 10%;')
		->setAttrib('maxlength', '250')
		->setAttrib('required', 'true')
		->setAttrib('dojoType', "dijit.form.SimpleTextarea")
		->setAttrib('style', 'margin-top:10px;border:1px light-solid #666666;color:#666666;font-size:11px')
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$clear = new Zend_Form_Element_Button('Clear');
		$clear->setAttrib('class', 'NormalBtn');
		$clear->setAttrib('dojoType', "dijit.form.Button");
		$clear->label = $gstrtranslate->_("Clear");
		$clear->setAttrib('OnClick', 'clearType()');
		$clear->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$ClearItem = new Zend_Form_Element_Button('ClearItem');
		$ClearItem->setAttrib('class', 'NormalBtn');
		$ClearItem->setAttrib('dojoType', "dijit.form.Button");
		$ClearItem->label = $gstrtranslate->_("Clear");
		$ClearItem->setAttrib('OnClick', 'cleardesctype()');
		$ClearItem->removeDecorator("Label")
		->removeDecorator("DtDdWrapper")
		->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->label = $gstrtranslate->_("Save");
		$Save->dojotype = "dijit.form.Button";
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";

		$SaveItem = new Zend_Form_Element_Submit('SaveItem');
		$SaveItem->label = $gstrtranslate->_("Save");
		$SaveItem->dojotype = "dijit.form.Button";
		$SaveItem->removeDecorator("DtDdWrapper");
		$SaveItem->removeDecorator('HtmlTag')
		->class = "NormalBtn";


		$this->addElements(array(
				$IdDescription, $Description, $DescriptionDefaultlang,
				$clear,$Save,$IdDescriptionItem,$DescriptionItem,
				$DescriptionDefaultlangItem,$ClearItem,$SaveItem
		));
	}

}

?>
