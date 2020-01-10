<?php 

class Penjadwalan_PenjadwalanController extends Zend_Controller_Action{
 public function viewAction(){
 
  $dbMhs = new Penjadwalan_Model_DbTable_BiodataPenjadwalan();
  if($this->getRequest()->isPost())
  {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['save']))
   {
    unset($formData['save']);
    //echo var_dump($formData);exit;
    $dbMhs->saveData($formData);
   }
   
   else if(isset($formData['delete']))
   {
    //echo $formData['id'];exit;
     $dbMhs->deleteData($formData['id']);
   }
   
  else if(isset($formData['search']))
   {
    $row=$dbMhs->searchData(array('nim'=>$formData['nim']));
     $this->view->Mahasiswa=$row;
   }
   
  else if(isset($formData['update']))
   {
    unset($formData['update']);
     $dbMhs->updateData($formData, 'name="'.$formData['name'].'"');
 
    
   }
  }
  $data=$dbMhs->getData(); 
   $this->view->datamhs
   =$data;
 }

}
?>