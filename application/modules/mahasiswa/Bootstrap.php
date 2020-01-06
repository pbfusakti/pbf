<?php
<<<<<<< HEAD
class  Mahasiswa_Bootstrap extends Zend_Application_Module_Bootstrap {
=======
<<<<<<< HEAD
class  Mahasiswa_Bootstrap extends Zend_Application_Module_Bootstrap
{
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
				'namespace'	=> 'Mahasiswa_'	,
				'basePath'	=> APPLICATION_PATH
		));

		return $autoloader;
	}

}
<<<<<<< HEAD
=======
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
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338
?>