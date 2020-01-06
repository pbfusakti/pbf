<?php
<<<<<<< HEAD
class  Mahasiswa_Bootstrap extends Zend_Application_Module_Bootstrap
{

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
				'namespace'	=> 'Mahasiswa_'	,
				'basePath'	=> APPLICATION_PATH
		));

		return $autoloader;
	}

}
=======
class Mahasiswa_Bootstrap extends Zend_Application_Module_Bootstrap
{
	
	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
			'namespace' => 'Mahasiswa_',
			'basePath'  => APPLICATION_PATH
		));
		
		return $autoloader;
	}
}

>>>>>>> 42ef2360412a2489043137b89e5d6fb202bcea24
?>