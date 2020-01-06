<?php
<<<<<<< HEAD
class Penjadwalan_Bootstrap extends Zend_Application_Module_Bootstrap
{

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array (
				'namespace'	=> 'Penjadwalan_'	,
				'basePath'	=> APPLICATION_PATH
		));

		return $autoloader;
	}
=======
class  Penjadwalan_Bootstrap extends Zend_Application_Module_Bootstrap
{

 protected function _initAutoload() {
  $autoloader = new Zend_Application_Module_Autoloader(array (
    'namespace' => 'Penjadwalan_' ,
    'basePath' => APPLICATION_PATH
  ));

  return $autoloader;
 }
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338

}
?>