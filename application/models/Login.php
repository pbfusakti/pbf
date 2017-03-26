<?php 

class App_Model_Login extends Zend_Db_Table_Abstract {
	
	protected $_name = 'applicant_profile'; 
	

	public function attempLogin($user,$pass){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
					  ->from(array('sa'=>$this->_name))
					  ->where('sa.appl_email=?',$user)
					  ->where('sa.appl_password=?',$pass);
		$rows = $db->fetchRow($select);
		$hash="";
		if ($rows) {
			//generate token
			$myNamespace = new Zend_Session_Namespace('authtoken');
			$myNamespace->setExpirationSeconds(900);
			$myNamespace->authtoken = $hash = md5(uniqid(rand(),1));
			
		}
	 
		return $hash;
	}
	
	public function cekToken($token) {
		$mysession = new Zend_Session_Namespace('authtoken');
		$hash = $mysession->authtoken;
		if($hash == $token){
			return true;
		} else {
			return false;
		}
	}
	
	
	 
	
	 
	
	
}

?>