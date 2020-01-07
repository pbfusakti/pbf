<?php
class icampus_Function_Studentfinance_SemesterInvoicing{
	
	/*
	 * Get data for invoicing
	*/
	public function getStudentSemesterPaymentRequirement($registrationDataArr,$semesterId, $level, $feeItemSelected=array()){
		
		$idRegistration = $registrationDataArr['IdStudentRegistration'];
		$intakeId = $registrationDataArr['IdIntake'];
		$programId = $registrationDataArr['IdProgram'];
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		/*
		 * get registered course in particular semester
		 */
		$subjectRegisterDb = new Registration_Model_DbTable_Studentregsubjects();
		$registered_subject = $subjectRegisterDb->getRegisteredSubject($idRegistration,$semesterId, '1,3');
		
		
		//profile
		$studentProfileDb = new Records_Model_DbTable_Studentprofile();
		$profile = $studentProfileDb->fnViewStudentAppnDetails($idRegistration);
		$invoice['nationality'] = $profile['appl_nationality'];
		
		
		/*
		 * fee structure detail
		 */
		$feeStructureDb = new Studentfinance_Model_DbTable_FeeStructure();
		
		//get fee structure
        //TODO: put this part into config. instead of 96, use constant or something
		if($profile['appl_nationality']!=96){
			$student_category = 315;
		}else{
			$student_category = 314;
		}
		
		$feeStructure = $feeStructureDb->getApplicantFeeStructure($intakeId,$programId,null,$student_category);
		
		$feeStructureItemDb = new Studentfinance_Model_DbTable_FeeStructureItem();
		$fee_item = $feeStructureItemDb->getStructureData($feeStructure['fs_id']);
				
		//filter only selected fee item
		foreach ($fee_item as $index=>$fee){
			if( !in_array($fee['fi_id'],$feeItemSelected) ){
				unset($fee_item[$index]);
			}
		}
		
		
		
		/*
		 * get fee item frequency type
		 */
		
		$sem_fee_item = array();
		foreach ($fee_item as $fs){
		
			//1st sem
			if($level==1 && $fs['fi_frequency_mode']== 302 ){
				$sem_fee_item[] = $fs;
			}
		
			//every sem
			if($fs['fi_frequency_mode']== 303 || $fs['fi_frequency_mode']== 453){
				$sem_fee_item[] = $fs;
			}
			 
			//every senior semester
			if($level>1 && $fs['fi_frequency_mode']== 304){
				$sem_fee_item[] = $fs;
			}
		
			//defined semester
			if($fs['fi_frequency_mode']== 305){
				 
				foreach ($fs['semester'] as $sem_defined){
					if($sem_defined['fsis_semester'] == $level){
						$sem_fee_item[] = $fs;
					}
				}
			}
			 
			//every gasal regular
			if($fs['fi_frequency_mode']== 460 && $semester['SemesterCountType']==1 && $semester['SemesterFunctionType']==0){
				$sem_fee_item[] = $fs;
			}
			 
		}
		
		
		/*
		 * get fee item calculation type
		 */
		$invoice['amount']=0.00;
		
		/*if($idRegistration==40380){
			echo "<pre>";
			print_r($sem_fee_item);
			echo "</pre>";
			exit;
		}*/
		 
		foreach ($sem_fee_item as $item){
		
			//nilai tetap
			if($item['fi_amount_calculation_type']==300){
				$invoice['amount'] +=  $item['fsi_amount'];
				$item['total_amount'] = $item['fsi_amount'];
				$invoice['fee_item'][] = $item;
			}else
		
			//pendaraban SKS
			if($item['fi_amount_calculation_type']==299){
				$total_sks = 0;
				for($i=0; $i<sizeof($registered_subject); $i++){
					$total_sks +=$registered_subject[$i]['CreditHours'];
				}
				 
				if($total_sks!=0){
					$invoice['amount'] +=  $item['fsi_amount']*$total_sks;
					$item['total_amount'] = $item['fsi_amount']*$total_sks;
					$item['total_sks'] = $total_sks;
					
					$item['sks'] = $total_sks;
					$invoice['fee_item'][] = $item;
				}
				 
			}else
		
			//pendaraban jumlah subject
			if($item['fi_amount_calculation_type']==301){
				$total_subject = sizeof($registered_subject);
				 
				if($total_subject!=0){
					$invoice['amount'] +=  $item['fsi_amount']*$total_subject;
					$item['total_amount'] = $item['fsi_amount']*$total_subject;
					$item['total_subject'] = $total_subject;
					
					$item['bil_sub'] = $total_subject;
					$invoice['fee_item'][] = $item;
				}
			}else
		
			//registered subject
			if($item['fi_amount_calculation_type']==459){
		
				$item['total_amount'] = 0;
		
				for($i=0; $i<sizeof($registered_subject); $i++){
		
					if(isset($item['subject'])){
						foreach ($item['subject'] as $subject){
		
							if($subject['fsisub_subject_id'] = $registered_subject[$i]['IdSubject']){
								$invoice['amount'] +=  $item['fsi_amount'];
								$item['total_amount'] = $item['fsi_amount'];
							}
		
						}
					}
		
					if($item['total_amount']>0){
						$invoice['fee_item'][] = $item;
					}
		
				}
				 
			}
		
		}
			 
		
		return $invoice;
	}
	
}
?>