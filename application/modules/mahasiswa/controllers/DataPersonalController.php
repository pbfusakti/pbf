<?php 
class Mahasiswa_DataPersonalController extends Zend_Controller_Action {
<<<<<<< HEAD
 
 
 public function viewAction()
 {
  $dbMhs = new Mahasiswa_Model_DbTable_BiodataMahasiswa();
  
  if($this->getRequest()->isPost())
  {
   $formData = $this->getRequest()->getPost();
   if(isset($formData['save']))
   {
    unset($formData['save']);
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
    $this->view->datapersonal=$row;
   }
   
  else if(isset($formData['update']))
   {
   unset($formData['update']);
    $dbMhs->updateData($formData, 'nim="'.$formData['nim'].'"');
   }
   
   
  else if(isset($formData['approve']))
   {
    
   }
  }
  $data=$dbMhs->getData();
  $this->view->datamhs=$data;
 }
 
 
=======
	
	
	public function viewAction()
	{
		$dbMhs = new Mahasiswa_Model_DbTable_BiodataMahasiswa();
		
		if($this->getRequest()->isPost())
		{
			$formData = $this->getRequest()->getPost();
			if(isset($formData['save']))
			{
				unset($formData['save']);
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
				$this->view->datapersonal=$row;
			}
			
		else if(isset($formData['update']))
			{
			unset($formData['update']);
				$dbMhs->updateData($formData, 'nim="'.$formData['nim'].'"');
			}
			
			
		else if(isset($formData['approve']))
			{
				
			}
		}
		$data=$dbMhs->getData();
		$this->view->datamhs=$data;
	}
	
	
>>>>>>> 3ab65f8b212da4368f2167b8b27140869c7e9338
}


?>