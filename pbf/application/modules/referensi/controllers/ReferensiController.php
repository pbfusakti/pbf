<?php 

class Referensi_ReferensiController extends Zend_Controller_Action{
	public function viewAction(){
	
		$dbRef = new Referensi_Model_DbTable_ReferensiMatakuliah();
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
				$row=$dbRef->searchData(array('matakuliah'=>$formData['matakuliah']));
					$this->view->datapersonal=$row;
			}
			
		else if(isset($formData['update']))
			{
				unset($formData['update']);
					$dbRef->updateData($formData, 'matakuliah="'.$formData['matakuliah'].'"');
			}
		
		}
		$data=$dbRef->getData(); 
			$this->view->refmhs=$data;
	}

}
?>