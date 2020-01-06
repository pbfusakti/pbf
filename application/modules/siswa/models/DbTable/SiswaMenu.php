<?php 
class Siswa_Model_DbTable_SiswaMenu extends Zend_Db_Table {

protected $_name='siswa';
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
 if (isset($filter['nim']) && $filter['nim']!=null) 
     $select->where('nim=?',$filter['nim']);
     if (isset($filter['siswa']) && $filter['siswa']!=null) 
     $select->where('siswa "%',$filter['siswa'].'%"');
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