<?php 
class Pelajaran_Model_DbTable_PelajaranMenu extends Zend_Db_Table {

protected $_name='matakuliah';
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
     if (isset($filter['matakuliah']) && $filter['matakuliah']!=null) 
     $select->where('matakuliah like "%',$filter['matakuliah'].'%"');
      $row=$db->fetchRow($select);
     return $row;
     }
     
 


public function updateData($data, $key) {
 $db = Zend_Db_Table::getDefaultAdapter(); 
  $id=$db->update($this->_name,$data,$key);
  return $id;
}
}
?>