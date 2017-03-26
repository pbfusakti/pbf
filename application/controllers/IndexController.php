<?php
class IndexController extends Base_Base {

	private $gstrsessionSIS;//Global Session Name
	public function init() { //instantiate log object

	}


    
    public function ajaxLoginAction(){
    
    	$user = $this->_getParam('user',null);
    	$password = $this->_getParam('password',null);
    	 
    	$this->_helper->layout->disableLayout();
    
    	$ajaxContext = $this->_helper->getHelper('AjaxContext');
    	     
    	$dbLog=new App_Model_Login();
    	
    	$token = $dbLog->attempLogin($user, $password);
     	 
    
    	$ajaxContext->addActionContext('view', 'html')
    	->addActionContext('form', 'html')
    	->addActionContext('process', 'json')
    	->initContext();
    
    	$json = Zend_Json::encode($token);
    
    	echo $json;
    	exit();
    }
		
	}

	


