<?php 

class App_Model_Login extends Zend_Db_Table_Abstract {
	
	protected $_name = 'applicant_profile'; 
	

	public function attempLogin($user,$pass){
	
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$select = $db ->select()
					  ->from(array('sa'=>$this->_name))
					  ->join(array('st'=>'tbl_studentregistration'),'sa.appl_id=st.IdApllication')
					  ->where('sa.appl_email=?',$user)
					  ->where('sa.appl_password=?',$pass);
		$rows = $db->fetchRow($select);
		$result=array();
		if ($rows) {
			//get appl_id
			
			//generate token
			$myNamespace = new Zend_Session_Namespace('authtoken');
			$myNamespace->setExpirationSeconds(900);
			$myNamespace->authtoken = $hash = md5(uniqid(rand(),1));
			$result=array('token'=>$hash,
					'IdStudentRegistration'=>$rows['IdStudentRegistration'],
					'Nim'=>$rows['registrationId'],
					'Name'=>$rows['appl_fname'].' '.$rows['appl_mname'].' '.$rows['appl_lname']
			);
			
		}
	 
		return $result;
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