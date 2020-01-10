<?php 
class App_Model_General_DbTable_Blocktaken extends Zend_Db_Table {
	protected $_name = 'tbl_blocktaken';
	
	
	public function checkBlockTaken($idstudreg,$blockid){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>$this->_name))
		 				 ->where("idstudentreg = ?",$idstudreg)
		 				 ->where("blockid = ?",$blockid);
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
	}
	
	public function addTaken($data){
		$this->insert($data);
	}
	
	public function getNextBlock($idstudreg){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array("a"=>$this->_name))
		 				 ->where("idstudentreg = ?",$idstudreg)
		 				 ->where("blockstatus = ?",1)
		 				 ->order("blocklevel desc");
		//echo  $lstrSelect;				 
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		if(count($larrResult)>0){
			return $larrResult["blocklevel"]+1;
		}else{
			return 0;
		}
	}
	
	public function updTaken($data,$idbltaken){
		$this->update($data,"id = $idbltaken");
	}	
}
?>