<?php 
class Mahasiswa_Model_DbTable_BiodataMahasiswa extends Zend_Db_Table {

protected $_name='mahasiswa';
 
protected $_primary='Idmhs';
 
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
 $id=$db->delete($id);
}
   
 
public function searchData($filter){
  $db= Zend_Db_Table::getDefaultAdapter();
     $select=$db->select()
         ->from($this->_name);
     if (isset($filter['nim']) && $filter['nim']!=null) 
     $select->where('nim=?',$filter['nim']);
     if (isset($filter['name']) && $filter['name']!=null) 
     $select->where('name like "%',$filter['name'].'%"');
      $row=$db->fetchRow($select);
     return $row;
     }
  
 
public function approveData($data,$id){
 
}

public function updateData($data,$key) {
 $db = Zend_Db_Table::getDefaultAdapter();
 $id=$db->update($data);
 return $id;
 
public function updateData($data,$key) {
 	$db = Zend_Db_Table::getDefaultAdapter();
 	$id=$db->update($this->_name,$data,$key);
 	return $id;
}

public function deleteData($id){
 	$db = Zend_Db_Table::getDefaultAdapter();
 	$id=$db->delete($this->_name,"id = ".$id);
}
  
public function approveData($data,$id){
 
 
}

}
?>