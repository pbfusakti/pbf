<?php 

class Kurikulumsearch_KurikulumsearchController extends Zend_Controller_Action{
 public function viewAction(){
 
  $dbKur = new Kurikulumsearch_Model_DbTable_KurikulumsearchMahasiswa();
  if($this->getRequest()->isPost())
  {
  $formData = $this->getRequest()->getPost();
   
   
  if(isset($formData['searchkurikulum']))
   {
    $row=$dbKur->searchData(array('semester'=>$formData['semester']));

     $this->view->kurikuluma=$row;
   }
   

  }
  $data=$dbKur->getData(); 
  $this->view->kurmhs=$data;
 }

}
?>