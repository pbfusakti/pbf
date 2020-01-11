<?php
class Examination_LoginController extends Zend_Controller_Action {

	public function indexAction() {
		 
		
	}
 
    public function ajaxLoginAction(){
       
    	 
    	$user = $this->_getParam('user',null);
    	$password = $this->_getParam('password',null);
    	 
    	$this->_helper->layout->disableLayout();
    	
    	$ajaxContext = $this->_helper->getHelper('AjaxContext');
    	     
    	$dbLog=new App_Model_Login();
    	
    	$token = $dbLog->attempLogin($user, $password);
    	$dbToken=new App_Model_General_DbTable_Token();
    	$data=array(
    			'IdLogin' => $token['IdStudentRegistration'],
    			'userid' => $token['userid'],
    			'token' => $token['token'],
    			'Active' => '1'
    	);
    	$dbToken->addData($data);
    	
    	$ajaxContext->addActionContext('view', 'html')
    	->addActionContext('form', 'html')
    	->addActionContext('process', 'json')
    	->initContext();
    
    	$json = Zend_Json::encode($token);
    
    	echo $json;
    	exit();
    }
		
	}

	


