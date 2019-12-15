<?php 

class Dosen_DosenController extends Zend_Controller_Action{
 public function viewAction(){
 
  $dbRef = new Dosen_Model_DbTable_DosenMenu();
  if($this->getRequest()->isPost())
  {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['save']))
   {
    unset($formData['save']);
    //echo var_dump($formData);exit;
    $dbRef->saveData($formData);
   }
   
   else if(isset($formData['delete']))
   {
    //echo $formData['id'];exit;
     $dbRef->deleteData($formData['id']);
   }
   
  else if(isset($formData['search']))
   {
    $row=$dbRef->searchData(array('kodemk'=>$formData['kodemk']));
     $this->view->dosen=$row;
   }
   
  else if(isset($formData['update']))
   {
    unset($formData['update']);
     $dbRef->updateData($formData, 'matakuliah="'.$formData['matakuliah'].'"');
 
    
   }
  }
  $data=$dbRef->getData(); 
   $this->view->refmhs
   =$data;
 }

}
?>