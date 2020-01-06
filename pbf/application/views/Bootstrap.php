<?php
class  Referensi_Boostrap extends Zend_Application_Module_Bootstrap
{

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
				'namespace'	=> 'Referensi_'	,
				'basePath'	=> APPLICATION_PATH
		));

		return $autoloader;
	}

}
?>