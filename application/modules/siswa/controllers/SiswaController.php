<?php 

class Siswa_SiswaController extends Zend_Controller_Action{
 public function viewAction(){
 
 $dbRef = new Siswa_Model_DbTable_SiswaMenu();
  if($this->getRequest()->isPost())
  {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['save']))
   {
    unset($formData['save']);
    /*echo var_dump($formData); exit;*/
    $dbRef->saveData($formData);
   }
   
   else if(isset($formData['delete']))
   {
    //echo $formData['id'];exit;
     $dbRef->deleteData($formData['id']);
   }
   
  else if(isset($formData['search']))
   {
    $row=$dbRef->searchData(array('nim'=>$formData['nim']));
     $this->view->datapersonal=$row;
   }
   
  else if(isset($formData['update']))
   {
    unset($formData['update']);
     $dbRef->updateData($formData, 'siswa="'.$formData['siswa'].'"');
   }
  
  }
  $data=$dbRef->getData(); 
   $this->view->refmhs=$data;
 }
}
?>