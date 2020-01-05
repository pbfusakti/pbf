<?php 

class Krs_KrsController extends Zend_Controller_Action{
	public function viewAction(){
	
		$dbKrs = new Krs_Model_DbTable_KrsMahasiswa();
		if($this->getRequest()->isPost())
		{    
		$formData = $this->getRequest()->getPost();
			if(isset($formData['save']))
			{
				unset($formData['save']);
				$dbKrs->saveData($formData);
			}
			
			else if(isset($formData['delete']))
			{
				//echo $formData['id'];exit;
					$dbKrs->deleteData($formData['id']);
			}
			
		else if(isset($formData['search']))
			{
				$row=$dbKrs->searchData(array('semester'=>$formData['semester']));

					$this->view->krsa=$row;
			}
			
		else if(isset($formData['update']))
			{
				unset($formData['update']);
				
					$dbKrs->updateData($formData, 'semester="'.$formData['semester'].'"');
			}
			

		}
		$data=$dbKrs->getData(); 
			$this->view->krsmhs=$data;
	}

}
?>