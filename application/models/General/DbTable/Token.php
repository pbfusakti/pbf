<?php 
class App_Model_General_DbTable_Token extends Zend_Db_Table_Abstract
{
    protected $_name = 'tbl_token';
	protected $_primary = "Id";
	
	public function getData($id=0){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$id = (int)$id;
		
		if($id!=0){

	        $select = $db->select()
	                ->from(array('v'=>$this->_name))
	                ->where('v.'.$this->_primary.' = ' .$id);
					 
	         
	        $row = $db->fetchRow($select);
	    
        
		}else{
			$select = $db->select()
	                ->from(array('v'=>$this->_name)); 
					 
	         
	        $row = $db->fetchAll($select);
		}
		
		return $row;
	}
	
	
	public function isTokenValid($token,$Id) {
	
		$db = Zend_Db_Table::getDefaultAdapter();
	 
	
		 
			$select = $db->select()
			->from(array('v'=>$this->_name))
			->where('v.token=?',$token)
			->where('v.IdLogin=?',$Id)
			->where('v.dt_entry >= CURDATE() - INTERVAL 30 MINUTES')
			->where('v.Active="1"');
	
	
			$row = $db->fetchRow($select);
			if ($row) return true; else return false;
	}
 
	
	public function addData($data){
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$data = array(
			'dt_entry' => date('Y-m-d H:i:s'),
			'IdLogin' => $data['IdLogin'],
			'userid' => $data['userid'],
			'token' => $data['token'],
			'Active' => $data['Active'],
			 
		);
		$db->insert($this->_name,$data);
	}
	
	public function updateData($data,$id){
		$db = Zend_Db_Table::getDefaultAdapter();
		$data = array(
			'dt_entry' => $date('Y-m-d H:i:s'),
			'IdLogin' => $data['IdLogin'],
			'userid' => $data['userid'],
			'token' => $data['token'],
			'Active' => $data['Active'],
			 
		);
		
		$db->update($this->_name,$data, $this->_primary . ' = ' . (int)$id);
	}
	
	public function deleteData($id){
		$this->delete($this->_primary .' =' . (int)$id);
	}
	
	 
}
?>