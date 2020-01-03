<?php 
class Referensi_Model_DbTable_ReferensiMatakuliah extends Zend_Db_Table {

protected $_name='referensi';
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
     if (isset($filter['kode_mk']) && $filter['kode_mk']!=null) 
     $select->where('kode_mk=?',$filter['kode_mk']);
     if (isset($filter['mata_kuliah']) && $filter['mata_kuliah']!=null) 
     $select->where('mata_kuliah like "%',$filter['mata_kuliah'].'%"');
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