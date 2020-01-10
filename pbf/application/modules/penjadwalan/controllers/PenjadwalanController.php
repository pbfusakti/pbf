<?php 

class Penjadwalan_PenjadwalanController extends Zend_Controller_Action{
	public function viewAction(){
	
	$dbRef = new Penjadwalan_Model_DbTable_PenjadwalanMenu();
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
					$dbRef->updateData($formData, 'matakuliah="'.$formData['matakuliah'].'"');
			}
		
		}
		$data=$dbRef->getData(); 
			$this->view->refmhs=$data;
	}
}
?>