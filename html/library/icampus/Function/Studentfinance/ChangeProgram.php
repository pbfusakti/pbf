<?php 
class icampus_Function_Studentfinance_ChangeProgram{
	
	
	public function changeProgram($txn_id_from, $txn_id_to){
		
		try {
		//get (from) transaction data
		$transactionDb = new App_Model_Application_DbTable_ApplicantTransaction();
		$txnData = $transactionDb->getTransactionData($txn_id_from);
    	    
		//get program offered
		$applicantProgramDb = new App_Model_Application_DbTable_ApplicantProgram();
		$programOffered = $applicantProgramDb->getProgramOffered($txnData['at_trans_id'],$txnData['at_appl_type']);
    	    
		//get invoice issued from payee id
		$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
		$invoiceList = $invoiceMainDb->getIssuedInvoiceData($txnData['at_pes_id'],$programOffered['program_code']);

		//issue credit note & advance payment, new invoice and advance payment knockoff
		if($invoiceList){
			
				$creditNoteDb = new Studentfinance_Model_DbTable_CreditNote();

				//create CN for each invoice issued and paid
				foreach ($invoiceList as $invoice){
					$data = array(
    	    			'cn_billing_no' => $invoice['bill_number'],
    	    			'cn_fomulir' => $invoice['no_fomulir'],
    	    			'appl_id' => $invoice['appl_id'],
    	    			'cn_amount' => $invoice['bill_amount'],
    	    			'cn_description' => 'Change Program',
    	    			'cn_creator' => -1,
    	    			'cn_approver' => -1,
    	    			'cn_approve_date' => date('Y-m-d H:i:s')
    	    		);
	    	    		
					$creditNoteDb->insert($data);
	    	    		
	    	    	//update invoice balance and CN value
					$data = array(
						'bill_balance' => 0,
						'cn_amount' => $invoice['bill_amount'],
					);
					$invoiceMainDb->update($data,'id = '.$invoice['id']);
						
					//get payment on invoice issued
		    	    $paymentMainDb = new Studentfinance_Model_DbTable_PaymentMain();
		    	    $payment = $paymentMainDb->getInvoicePaymentRecord($invoice['bill_number'], $invoice['no_fomulir']);
			    	   
		    	    if($payment!=null){
		    	    	
			    	    //transfer payment to advance payment
			    	    $advPaymentDb = new Studentfinance_Model_DbTable_AdvancePayment();
			    	    $data2 = array(
			    	    	'advpy_appl_id' => $invoice['appl_id'],
			    	    	'advpy_acad_year_id' => $invoice['academic_year'],
			    	    	'advpy_sem_id' => $invoice['semester'],
			    	    	'advpy_prog_code' => $invoice['program_code'],
			    	    	'advpy_fomulir' => $invoice['no_fomulir'],
			    	    	'advpy_invoice_no' =>$invoice['bill_number'],
			    	    	'advpy_invoice_id' =>$invoice['id'],
			    	    	'advpy_payment_id' => $payment['id'],
			    	    	'advpy_description' => 'Change Program Payment Transfer',
			    	    	'advpy_amount' => $payment['amount'],
			    	    	'advpy_total_paid' => 0,
			    	    	'advpy_total_balance' => $payment['amount'],
			    	    	'advpy_status' => 'A',
			    	    	'advpy_creator' => -1
			    	    );
			    	    
			    	    $advPaymentDb->insert($data2);
		    	    }
    	    	}

				//get old program packet
				$old_program_packet = $invoiceList[0];
	    	    	
				//new program billnumber
				$newTxn = $transactionDb->getTransactionData($txn_id_to);

				//packet no from old bill 01 or 11 + fomulir
				$new_bill = substr($old_program_packet['bill_number'],0,1).substr($old_program_packet['bill_number'],1,1).$newTxn['at_pes_id'];
	    	    	
				//bill applicant with new program invoice
				$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
				$invoiceMainDb->generateApplicantInvoice($newTxn['at_pes_id'], $new_bill, 0);
    	    }
		}catch (exception $e) {
				    
		    echo "<pre>";
		    echo $e->getMessage();
			print_r($e->getTrace());
			echo "</pre>";
			
			throw $e;
		}
		
	}
}
?>