<?php
class Examination_Form_Studentattandance extends Zend_Dojo_Form { //Formclass for the Programmaster	 module
	public function init() {
		$gstrtranslate =Zend_Registry::get('Zend_Translate');

		$AttandanceStatus = new Zend_Form_Element_Checkbox('AttandanceStatus');
		$AttandanceStatus ->setAttrib('dojoType',"dijit.form.CheckBox")
		->setChecked(true)
		->removeDecorator("DtDdWrapper")
		->removeDecorator("Label")
		->removeDecorator('HtmlTag');

		$UpdDate = new Zend_Form_Element_Hidden('UpdDate');
		$UpdDate->removeDecorator("DtDdWrapper");
		$UpdDate->removeDecorator("Label");
		$UpdDate->removeDecorator('HtmlTag');

		$UpdUser  = new Zend_Form_Element_Hidden('UpdUser');
		$UpdUser->removeDecorator("DtDdWrapper");
		$UpdUser->removeDecorator("Label");
		$UpdUser->removeDecorator('HtmlTag');

		$Save = new Zend_Form_Element_Submit('Save');
		$Save->label = $gstrtranslate->_("Save");
		$Save->dojotype="dijit.form.Button";
		$Save->removeDecorator("DtDdWrapper");
		$Save->removeDecorator('HtmlTag')
		->class = "NormalBtn";
			
			
		//form elements
		$this->addElements(array($UpdDate,$UpdUser,$Save,$AttandanceStatus));

	}
}