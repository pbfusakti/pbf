<?php
class App_Model_General_DbTable_PaymentMain extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'payment_main';
	protected $_primary = "id";
		
	public function getData($id){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('pm'=>$this->_name))
					->where("pm.id ?", (int)$id);

		$row = $db->fetchRow($selectData);				
		return $row;
	}
	
	public function getBankTransactionRecord($bank_transaction_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('pm'=>$this->_name))
					->join(array('pbrd'=>'payment_bank_record_detail'), 'pbrd.id = pm.transaction_reference')
					->where("pbrd.pbr_id = ?", (int)$bank_transaction_id);
		
		$row = $db->fetchAll($selectData);	

		if(!$row){
			return null;
		}else{
			return $row;
		}
	}
	
	public function getPaymentByBankTransaction($bank_transaction_id,$billing_no){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('pm'=>$this->_name))
					->join(array('pbrd'=>'payment_bank_record_detail'), 'pbrd.id = pm.transaction_reference',array())
					->where("pbrd.id =pm.transaction_reference")
					->where("pm.billing_no = ?", $billing_no)
					->where("pbrd.bancs_journal_number = ?", $bank_transaction_id);
		
		$row = $db->fetchRow($selectData);	

		if(!$row){
			return null;
		}else{
			return $row;
		}
	}
	
	public function getInvoicePaymentRecord($billing_no, $payer=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('pm'=>$this->_name))
					->where("pm.billing_no = ?", $billing_no);
		if($payer){
			$selectData->where("pm.payer = ?", $payer);
		}
		
		$row = $db->fetchRow($selectData);	

		if(!$row){
			return null;
		}else{
			return $row;
		}
	}
	
	public function getAllInvoicePaymentRecord($billing_no, $payer=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('pm'=>$this->_name))
					->joinLeft(array('pbrd'=>'payment_bank_record_detail'),'pbrd.id = pm.transaction_reference')
					->where("pm.billing_no = ?", $billing_no);
		
		if($payer){
			$selectData->where("pm.payer = ?", $payer);
		}
		
		$row = $db->fetchAll($selectData);	

		if(!$row){
			return null;
		}else{
			return $row;
		}
	}
	
	/**
	 * 
	 * Save Bank transaction to main payment record and detail payment record
	 * @param array $data_bank_detail
	 * @throws Exception
	 */
	public function saveBankPaymentTransaction($data_bank_detail, $payment_detail_id_ref, $student=0){
		try {
			

			$data = array(
	       		'billing_no' => $data_bank_detail['billing_no'],
	       		'payment_description' => $data_bank_detail['bill_ref_5'],
	       		'amount' => $data_bank_detail['amount_total'],
	       		'payment_mode' => 'SPC-BNI',
	       		'transaction_reference' => $payment_detail_id_ref,
				'payment_date' => date('Y-m-d', strtotime($data_bank_detail['paid_date']))
	       	);
			
			if($student==1){
				$data['IdStudentRegistration'] = $data_bank_detail['payee_id'];
				
				//get appl id
				$studentRegDb = new App_Model_Record_DbTable_StudentRegistration();
				$regInfo = $studentRegDb->fetchRow('registrationId = '.$data_bank_detail['payee_id']);
				$data['IdStudentRegistration'] = $regInfo['IdStudentRegistration'];
				$data['appl_id'] = $regInfo['IdApplication'];
				
			}else{
				//get appl id
				$transaction = $this->getTransaction($data_bank_detail['payee_id']);
				$data['payer'] = $transaction['at_pes_id'];
				$data['appl_id'] = $transaction['at_appl_id'];
			}
	       		       		
	       	return $payment_id = $this->insert($data);
	       		
	       	/*
	       	 * save payment record detail
	       	 */
	       	
	       	//get invoice detail item for payment detail description as we set in proforma invoice
	       	/*$feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
	        $invoice_detail = $feeItemDb->getActiveFeeItem();
	        
	       	$paymentDetailDb = new Studentfinance_Model_DbTable_PaymentDetail();	
	       	for($i=0; $i<10; $i++){
	       		
	       		if( isset($data_bank_detail['amount_'.($i+1)]) && $data_bank_detail['amount_'.($i+1)]!=0 && $data_bank_detail['amount_'.($i+1)]!= null ){
		       		$data = array(
		       			'pm_id' => $payment_id,
		       			'item_description' => $invoice_detail[$i]['fi_name_bahasa'],
		       			'item_amount' => $data_bank_detail['amount_'.($i+1)]
		       		);
		       			
		       		$paymentDetailDb->insert($data);
	       		}
	       	}*/
		}catch (Exception $e) {
			echo $e->getMessage();
		    throw new Exception('Error in Payment Main');
		}
	}

	/*
	 * Overite Insert function
	 */
	
	public function insert(array $data){
		
		if( !isset($data['payment_date']) ){
			$data['payment_date'] = date('Y-m-d H:i:s');
		}
			
		return parent::insert( $data );
	}
	
	private function getTransaction($payer_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//get application type
		$selectData = $db->select()
					->from(array('at'=>'applicant_transaction'))
					->where("at.at_pes_id = ?", $payer_id);
			
		$txn_row = $db->fetchRow($selectData);			

		if(!$txn_row){
			return null;
		}else{
			return $txn_row;
		}
	}
	
	public function getPaymentByApplicationId($payer_id){
		$db = Zend_Db_Table::getDefaultAdapter();
	
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
		->from(
				array('pm'=>'payment_main'),array(
						'billing_no'=>'pm.billing_no',
						'record_date'=>'pm.payment_date',
						'description' => 'pm.payment_description',
						'txn_type' => new Zend_Db_Expr ('"Payment"'),
						'debit' =>new Zend_Db_Expr ('"0.00"'),
						'credit' => 'amount',
						'document' => 'pbrd.bancs_journal_number',
						'payment_mode' => 'payment_mode'
				)
		)
		->joinLeft(array('pbrd'=>'payment_bank_record_detail'),'pbrd.id = pm.transaction_reference', array())
		->where("pm.payer = ?",$payer_id);
		
		$row = $db->fetchAll($select);
		
	
		if(!$row){
			return null;
		}else{
			return $row;
		}
	}
	
	public function getApplicantPaymentInfo($payer){
    	$db = Zend_Db_Table::getDefaultAdapter();
        $select = $db ->select()
				->from(array('pm'=>'payment_main'))
				->join(array('pi'=>'applicant_proforma_invoice'),'pm.billing_no = pi.billing_no')
				->where("pi.payee_id ='".$payer."'");
                                                 
		$row = $db->fetchAll($select);
		
		if(!$row){
			return null;
		}else{
			return $row;
		}
		
	}
	
	public function getApplicantPaymentTotalAmount($payer){
    	$db = Zend_Db_Table::getDefaultAdapter();
        $select = $db ->select()
				->from(array('im'=>'invoice_main'),array('amount'=>'bill_paid'))
				->join(array('pi'=>'applicant_proforma_invoice'),'im.bill_number = pi.billing_no')
				->where("pi.payee_id ='".$payer."'");
                                                 
		$row = $db->fetchAll($select);
		
		if(!$row){
			return 0;
		}else{
			$total = 0;
			foreach ($row as $bil){
				$total = $total + $bil['amount'];
			}
			
			return $total;
		}
		
	}

}
?>