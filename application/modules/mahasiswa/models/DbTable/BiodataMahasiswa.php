<?php 
class Mahasiswa_Model_DbTable_BiodataMahasiswa extends Zend_Db_Table {

protected $_name='mahasiswa';
<<<<<<< HEAD
protected $_primary='Idmhs';
=======
protected $_primary='id';
>>>>>>> 42ef2360412a2489043137b89e5d6fb202bcea24

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
<<<<<<< HEAD

public function deleteData($id){
 $db = Zend_Db_Table::getDefaultAdapter();
 $id=$db->delete($id);
}
   
=======
 
>>>>>>> 42ef2360412a2489043137b89e5d6fb202bcea24
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
     
<<<<<<< HEAD
 
public function approveData($data,$id){
 
}

public function updateData($data,$key) {
 $db = Zend_Db_Table::getDefaultAdapter();
 $id=$db->update($data);
 return $id;
=======
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
 
>>>>>>> 42ef2360412a2489043137b89e5d6fb202bcea24
}

}
?>