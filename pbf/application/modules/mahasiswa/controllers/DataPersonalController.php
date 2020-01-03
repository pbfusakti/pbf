<?php 
class Mahasiswa_DataPersonalController extends Zend_Controller_Action {
	
	
	public function viewAction()
	{
		$dbMhs = new Mahasiswa_Model_DbTable_BiodataMahasiswa();
		$data=$dbMhs->getData();
		$this->view->datamhs=$data;
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
				
			}
			
		else if(isset($formData['search']))
			{
			}
			
		else if(isset($formData['update']))
			{
				
			}
			
		else if(isset($formData['approve']))
			{
				
			}
		}
	}
	
	
}


?>