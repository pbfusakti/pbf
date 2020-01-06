<?php 

class Penjadwalan_PenjadwalanController extends Zend_Controller_Action{
 public function viewAction(){
 
<<<<<<< HEAD
 $dbRef = new Penjadwalan_Model_DbTable_PenjadwalanMenu();
=======
  $dbJdw = new Penjadwalan_Model_DbTable_PenjadwalanMahasiswa();
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338
  if($this->getRequest()->isPost())
  {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['save']))
   {
    unset($formData['save']);
<<<<<<< HEAD
    $dbRef->saveData($formData);
   }
   
   else if(isset($formData['delete']))
   {
    //echo $formData['id'];exit;
     $dbRef->deleteData($formData['id']);
=======
    //echo var_dump($formData);exit;
    $dbJdw->saveData($formData);
   }
   
  else if(isset($formData['delete']))
   {
    /*echo var_dump($formData);exit;*/
     $dbJdw->deleteData($formData['id']);
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338
   }
   
  else if(isset($formData['search']))
   {
<<<<<<< HEAD
    $row=$dbRef->searchData(array('kodemk'=>$formData['kodemk']));
     $this->view->datapersonal=$row;
=======
    $row=$dbJdw->searchData(array('kodemk'=>$formData['kodemk']));
     $this->view->jadwalan=$row;
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338
   }
   
  else if(isset($formData['update']))
   {
    unset($formData['update']);
<<<<<<< HEAD
     $dbRef->updateData($formData, 'matakuliah="'.$formData['matakuliah'].'"');
   }
  
  }
  $data=$dbRef->getData(); 
   $this->view->refmhs=$data;
 }
=======
     $dbJdw->updateData($formData, 'matakuliah="'.$formData['matakuliah'].'"');
 
    
   }
  }
  $data=$dbJdw->getData(); 
   $this->view->datajdw=$data;
 }

>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338
}
?>