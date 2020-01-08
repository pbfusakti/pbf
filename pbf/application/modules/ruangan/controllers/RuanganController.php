<?php 

class Ruangan_RuanganController extends Zend_Controller_Action{
	public function viewAction(){
	
	$dbRef = new Ruangan_Model_DbTable_RuanganMenu();
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
				$row=$dbRef->searchData(array('kodemk'=>$formData['kodemk']));
					$this->view->datapersonal=$row;
			}
			
		else if(isset($formData['update']))
			{
				unset($formData['update']);
					$dbRef->updateData($formData, 'ruangan="'.$formData['ruangan'].'"');
			}
		
		}
		$data=$dbRef->getData(); 
			$this->view->refmhs=$data;
	}
}
?>