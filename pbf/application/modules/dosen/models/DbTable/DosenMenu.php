<?php 
class Dosen_Model_DbTable_DosenMenu extends Zend_Db_Table {

protected $_name='dosen';
protected $_primary='id';

   public function getData(){
     $db= Zend_Db_Table::getDefaultAdapter();
     $select=$db->select()
         ->from($this->_name);
     $row=$db->fetchAll($select);
     return $row;
   }
   
public function saveData($data) {
 $db = Zend_Db_Table::getDefaultAdapter();
 $id=$db->insert($this->_name,$data);
 return $id;
}

public function deleteData($id){
  $db = Zend_Db_Table::getDefaultAdapter();
  $id=$db->delete($this->_name,"id = ".$id);
}

   
public function searchData($filter){
  $db= Zend_Db_Table::getDefaultAdapter();
     $select=$db->select()
         ->from($this->_name);
     if (isset($filter['kodemk']) && $filter['kodemk']!=null) 
     $select->where('kodemk=?',$filter['kodemk']);
     if (isset($filter['kodemk']) && $filter['kodemk']!=null) 
     $select->where('kodemk like "%',$filter['kodemk'].'%"');
      $row=$db->fetchRow($select);
     return $row;
     }
     
 
public function approveData($data,$id){
 
}

public function updateData($data, $key) {
 $db = Zend_Db_Table::getDefaultAdapter(); 
  $id=$db->update($this->_name,$data,$key);
  return $id;
}

}
?>