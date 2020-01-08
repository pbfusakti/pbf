<?php 

class Kurikulum_KurikulumController extends Zend_Controller_Action{
	public function viewAction(){
	
		$dbKur = new Kurikulum_Model_DbTable_KurikulumMahasiswa();
		if($this->getRequest()->isPost())
		{
		$formData = $this->getRequest()->getPost();
			if(isset($formData['save']))
			{
				unset($formData['save']);
				$dbKur->saveData($formData);
			}
			
			else if(isset($formData['delete']))
			{
				//echo $formData['id'];exit;
					$dbKur->deleteData($formData['id']);
			}
			
		else if(isset($formData['search']))
			{
				$row=$dbKur->searchData(array('kodemk'=>$formData['kodemk']));

					$this->view->kurikuluma=$row;
			}
			
		else if(isset($formData['update']))
			{
				unset($formData['update']);
					/*echo var_dump($formData); exit;*/
					$dbKur->updateData($formData, 'semester="'.$formData['semester'].'"');
			}
			

		}
		$data=$dbKur->getData(); 
			$this->view->kurmhs=$data;
	}

}
?>