<?php 

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

}
?>