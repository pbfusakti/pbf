<?php 

class Penjadwalan_PenjadwalanController extends Zend_Controller_Action{
 public function viewAction(){
 
  $dbJdw = new Penjadwalan_Model_DbTable_PenjadwalanMahasiswa();
  if($this->getRequest()->isPost())
  {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['save']))
   {
    unset($formData['save']);
    //echo var_dump($formData);exit;
    $dbJdw->saveData($formData);
   }
   
  else if(isset($formData['delete']))
   {
    /*echo var_dump($formData);exit;*/
     $dbJdw->deleteData($formData['id']);
   }
   
  else if(isset($formData['search']))
   {
    $row=$dbJdw->searchData(array('kodemk'=>$formData['kodemk']));
     $this->view->jadwalan=$row;
   }
   
  else if(isset($formData['update']))
   {
    unset($formData['update']);
     $dbJdw->updateData($formData, 'matakuliah="'.$formData['matakuliah'].'"');
 
    
   }
  }
  $data=$dbJdw->getData(); 
   $this->view->datajdw=$data;
 }

}
?>