<?php 
class Kurikulumsearch_Model_DbTable_KurikulumsearchMahasiswa extends Zend_Db_Table {

protected $_name='kurikulum';
protected $_primary='id';

   public function getData(){
     $db= Zend_Db_Table::getDefaultAdapter();
     $select=$db->select()
         ->from($this->_name);
     $row=$db->fetchAll($select);
     return $row;
   }
   
   
public function searchData($filter){
  $db= Zend_Db_Table::getDefaultAdapter();
     $select=$db->select()
         ->from($this->_name);
     if (isset($filter['mata_kuliah']) && $filter['mata_kuliah']!=null) 
     $select->where('mata_kuliah like "%',$filter['mata_kuliah'].'%"');
     if (isset($filter['semester']) && $filter['semester']!=null) 
     $select->where('semester like "%',$filter['semester'].'%"');
     if (isset($filter['kodemk']) && $filter['kodemk']!=null) 
     $select->where('kodemk like "%',$filter['kodemk'].'%"');
     if (isset($filter['tentang_mk']) && $filter['tentang_mk']!=null) 
     $select->where('tentang_mk like "%',$filter['tentang_mk'].'%"');
     if (isset($filter['tentang_mk']) && $filter['tentang_mk']!=null) 
     $select->where('bobot like "%',$filter['bobot'].'%"');
     if (isset($filter['dosen']) && $filter['dosen']!=null) 
     $select->where('dosen like "%',$filter['dosen'].'%"');
      $row=$db->fetchAll($select);
     return $row;
     }

}
?>