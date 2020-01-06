<?php 

<<<<<<< HEAD
class Mahasiswa_MahasiswaController extends Zend_Controller_Action{
	public function viewAction(){
	
		$dbRef = new Mahasiswa_Model_DbTable_BiodataMahasiswa();
		if($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			if(isset($formData['save']))
			{
				unset($formData['save']);
				$dbRef->saveData($formData);
			}
			
			else if(isset($formData['delete']))
			{
				//echo $formData['id'];exit;
					$dbRef->deleteData($formData['id']);
			}
			
		else if(isset($formData['search']))
			{
				$row=$dbRef->searchData(array('mahasiswa'=>$formData['mahasiswa']));
					$this->view->mahasiswa=$row;
			}
			
		else if(isset($formData['update']))
			{
				unset($formData['update']);
					$dbRef->updateData($formData, 'mahasiswa="'.$formData['mahasiswa'].'"');
			}
			
		else if(isset($formData['approve']))
			{
				
			}
		}
		$data=$dbRef->getData(); 
			$this->view->refmhs=$data;
	}
=======
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
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338

}
?>