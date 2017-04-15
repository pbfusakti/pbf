<?php
/**
 * @author Muhamad Alif
 * @version 1.0
 */

class Examination_InvoiceController extends Zend_Controller_Action {

	
	private $_DbObj;
	
	public function init(){
		$db = new App_Model_General_DbTable_InvoiceMain();
		$this->_DbObj = $db;
	}
	
	public function indexAction() {
		
		//title
    	$this->view->title= $this->view->translate("Invoice");
    	
    	$msg = $this->_getParam('msg', null);
    	    	
    	if ($this->getRequest()->isPost()) {
    		/*
	    	$db = Zend_Db_Table::getDefaultAdapter();

	    	//have payment but no invoice
			$select = $db->select()
					->from(array('pm'=>'payment_main'))
					->joinLeft(array('im'=>'invoice_main'), 'pm.billing_no = im.bill_number')
					->where("im.bill_number is null")
					->where('pm.billing_no = pm.payer')
					->limit(200);			
			//exit;
					
			$row = $db->fetchAll($select);
	
			if($row){
					
				foreach ($row as $bill){
					//echo "<pre>";
					//print_r($bill);
					//echo "</pre>";
				
					//profile data		
					$profileDb = new App_Model_Application_DbTable_ApplicantProfile();
					$profile = $profileDb->getProfileByFormulir($bill['payer']);
					
					//txn data
					$transDB = new App_Model_Application_DbTable_ApplicantTransaction();
	    			$transData = $transDB->getTransactionData($profile['at_trans_id']);
	    			
	    	
					//generate USM Charges invoice
					$data_invoice = array(
						'bill_number' => $transData['at_pes_id'],
						'appl_id' => $transData['at_appl_id'],
						'no_fomulir' =>$transData['at_pes_id'],
						'academic_year' => $transData['at_academic_year'],
						'bill_amount' =>250000,
						'bill_paid'=>0,
						'bill_balance'=>250000,
						'bill_description' => 'USM Charges',
						'college_id' => 0,
						'program_code' => '0000',
						'date_create' => date('Y-m-d H:i:s', strtotime($transData['at_submit_date'])),
						'creator' => '-1',
						'fs_id' => 0,
						'fsp_id' => 0,
						'status' =>'A'
					);
					
					
					//echo "<pre>";
					//print_r($data_invoice);
					//echo "</pre>";
					//exit;
					
					$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
					$invoiceMainDb->insert($data_invoice);
					
					
				}
			}else{
				echo "No USM Fee payment with no invoice.";
				exit;
			}
    		*/
    	}
    	
    	if( $msg!=null ){
    		$this->view->noticeMessage = $msg;
    	}   	
	}
	
	public function applicantAction(){
		
		$id = $this->_getParam('id', null);
		$this->view->id = $id;
		
		//title
    	$this->view->title= $this->view->translate("Invoice : Applicant Detail");
    	
    	//applicant info
    	$applicantProfileDb = new App_Model_Application_DbTable_ApplicantProfile();
    	$this->view->profile = $applicantProfileDb->getData($id);
	}
	
	public function applicantInvoiceAction(){
		$this->_helper->layout()->disableLayout();
		
		if( $this->getRequest()->isXmlHttpRequest() ){
			$this->_helper->layout->disableLayout();
		}
		
		$appl_id = $this->_getParam('id', null);
		$this->view->appl_id = $appl_id;
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
						->from(array('im'=>'invoice_main'),array(
							'id'=>'im.id',
							'record_date'=>'im.date_create',
							'description' => 'im.bill_description',
							'program_code' => 'im.program_code',
							'txn_type' => new Zend_Db_Expr ('"Invoice"'),
							'debit' =>'bill_amount',
							'invoice_no' => 'bill_number',
							'no_fomulir' => 'no_fomulir',
							'paid' => 'bill_paid',
							'cn' => 'cn_amount',
							'dn' => 'dn_amount',
							'balance' => 'bill_balance',
							'status' => 'status',
							)
						)
						->where('im.appl_id = ?', $appl_id)
						->where('im.IdStudentRegistration is null')
						->order('im.date_create')
						->order('im.id');
						
		$row = $db->fetchAll($select);
		
		if(!$row){
			$row = null;
		}else{
			//get payment status
			$paymentDb = new Studentfinance_Model_DbTable_PaymentMain();
			
			foreach ($row as $index=>$data){
				
				$row[$index]['payment'] = $paymentDb->getInvoicePaymentRecord($data['invoice_no'], $data['no_fomulir']); 
			}
		}
				
		$this->view->invoice = $row;
		
	}
	
	public function applicantProgrammeOfferedAction(){
		$this->_helper->layout()->disableLayout();
		
		$appl_id = $this->_getParam('id', null);
		$this->view->appl_id = $appl_id;
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$select = $db->select()
				->from(array('a'=>'applicant_transaction'))
			    ->joinLeft(array('b'=>'tbl_academic_year'),'b.ay_id = a.at_academic_year')
			    ->joinLeft(array('c'=>'tbl_academic_period'),'c.ap_id = a.at_period')
			    ->joinLeft(array('d'=>'tbl_intake'),'d.IdIntake = a.at_intake')
				->where('a.at_appl_id = '. $appl_id)
				->where("a.at_status not in ('APPLY','CLOSE','PROCESS','REJECT')")
				->order('a.at_trans_id');
				
		$row = $db->fetchAll($select);
		
		if(!$row){
			$row = null;
		}else{
			
			//get offered programme
			$applicantProgrammeDb =  new App_Model_Application_DbTable_ApplicantProgram();
			
			//get assessment info
			$applicantAssessmentDb = new App_Model_Application_DbTable_ApplicantAssessment();
			$applicantAssessmentUSMDb = new App_Model_Application_DbTable_ApplicantAssessmentUsm();
			
			foreach ($row as $index=>$data){
				
				//check for proforma invoice availbility
				$proformaInvoiceDb = new Application_Model_DbTable_ProformaInvoice();
				$proforma = $proformaInvoiceDb->getTxnData($data['at_trans_id']!=0?$data['at_trans_id']:"-1");
				
				if($proforma){
					$row[$index]['proforma'] = $proforma;
				}
				
				
				$row[$index]['program_offered'] =  $applicantProgrammeDb->getOfferProgram($data['at_trans_id'],$data['at_appl_type']);
				
				if($data['at_appl_type']==1){
					$data = $applicantAssessmentUSMDb->getData($data['at_trans_id']);
					
					$row[$index]['assessment']['registration_date_start'] = $data['aaud_reg_start_date'];
					$row[$index]['assessment']['registration_date_end'] = $data['aaud_reg_end_date'];
					$row[$index]['assessment']['nomor'] = $data['aaud_nomor'];
					$row[$index]['assessment']['rank'] = $data['aau_rector_ranking'];
				}else
				if($data['at_appl_type']==2){
					$data = $applicantAssessmentDb->getData($data['at_trans_id']);
					
					$row[$index]['assessment']['registration_date_start'] = $data['aar_reg_start_date'];
					$row[$index]['assessment']['registration_date_end'] = $data['aar_reg_end_date'];
					$row[$index]['assessment']['nomor'] = $data['asd_nomor'];
					$row[$index]['assessment']['rank'] = $data['aar_rating_rector'];
				}
				
				
				
			}
		}
		
		/*echo "<pre>";
		print_r($row);
		echo "</pre>";
		exit;*/
		$this->view->data = $row;
	}

	public function applicantGenerateInvoiceAction(){
		$this->_helper->layout()->disableLayout();
		
		$this->view->title = "Generate Applicant Invoice";
		
		$txn_id = $this->_getParam('txn', null);
		$this->view->txn_id = $txn_id;
		
			
		//transaction data
		$txnDb = new App_Model_Application_DbTable_ApplicantTransaction();
		$txnData = $txnDb->getTransactionData($txn_id);
		$this->view->txn_data = $txnData;
		
		//get offered programme
		$applicantProgrammeDb =  new App_Model_Application_DbTable_ApplicantProgram();
		$program_offer =  $applicantProgrammeDb->getOfferProgram($txnData['at_trans_id'],$txnData['at_appl_type']);
		
		//get assessment info
		$applicantAssessmentDb = new App_Model_Application_DbTable_ApplicantAssessment();
		$applicantAssessmentUSMDb = new App_Model_Application_DbTable_ApplicantAssessmentUsm();
		
		if($txnData['at_appl_type']==1){
			$assessment_data = $applicantAssessmentUSMDb->getData($txnData['at_trans_id']);
			$rank = $assessment_data['aau_rector_ranking'];
		}else
		if($txnData['at_appl_type']==2){
			$assessment_data = $applicantAssessmentDb->getData($txnData['at_trans_id']);
			$rank = $assessment_data['aar_rating_rector'];
		}
		
		if($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
			$invoiceMainDb->generateApplicantInvoice($txnData['at_pes_id'], $formData['billing']);
		
			//redirect
			$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'applicant','id'=>$txnData['at_appl_id']),'default',true));
			
		}else{
		
			//get fee structure
			$feestructureDb = new Studentfinance_Model_DbTable_FeeStructure();
			$fee_structure = $feestructureDb->getApplicantFeeStructure($txnData['at_intake'], $program_offer['program_id'] );
			
			//get payment plan
			$paymentPlanDb = new Studentfinance_Model_DbTable_FeeStructurePlan();
			$payment_plan = $paymentPlanDb->getStructureData($fee_structure['fs_id']);
			
			//get payment plan detail
			$paymentPlanDetailDb = new Studentfinance_Model_DbTable_FeeStructurePlanDetail();
			foreach ($payment_plan as $index=>$plan){
				//loop each installment
				for($installment=1; $installment<=$plan['fsp_bil_installment']; $installment++){
					$payment_plan[$index]['plan_detail'] = $paymentPlanDetailDb->getPlanData($plan['fsp_structure_id'],$plan['fsp_id'],$installment, 1, $program_offer['program_id'], $rank);
				}
			}
			
			$this->view->payment_plan = $payment_plan;
		}
		
		/*echo "<pre>";
		print_r($payment_plan);
		echo "</pre>";*/
	}
	
	public function applicantProformaInvoiceAction(){
		$this->_helper->layout()->disableLayout();
		
		$txn_id = $this->_getParam('txn', null);
		$this->view->txn_id = $txn_id;
		
		$proformaInvoiceDb = new Application_Model_DbTable_ProformaInvoice();
		$this->view->data = $proformaInvoiceDb->getTxnData($txn_id);
	}
	
	public function applicantCancelInvoiceAction(){
		$invoice_id = $this->_getParam('id', null);
		$appl_id = $this->_getParam('appl_id', null);
		
		$auth = Zend_Auth::getInstance();
		
		//update invoice
		$data=array(
			'status'=>'X',
			'cancel_by' => $auth->getIdentity()->iduser,
			'cancel_date' => date('Y-m-d H:i:s')
		);
			
		$this->_DbObj->update($data, 'id = '.$invoice_id);
		
		//redirect
		$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'applicant','id'=>$appl_id),'default',true));
	}
	
	public function applicantDeleteInvoiceAction(){
		$invoice_id = $this->_getParam('id', null);
		$appl_id = $this->_getParam('appl_id', null);
	
		$auth = Zend_Auth::getInstance();
	
		//get invoice main data
		$invoice = $this->_DbObj->getData($invoice_id);
		
		//add invoice delete history
		$invoiceDeleteHistoryDb = new Studentfinance_Model_DbTable_InvoiceDeleteHistory();
		$invoice['invoice_main_id'] = $invoice['id'];
		$invoice['id'] = null;
		$invoice['delete_by'] = $auth->getIdentity()->iduser;
		$invoice['delete_date'] = date('Y-m-d H:i:s');
		
		$invoiceDeleteHistoryDb->insert($invoice);
		
		//delete
		$this->_DbObj->delete('id = '.$invoice_id);
	
		//redirect
		$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'applicant','id'=>$appl_id),'default',true));
	}
	
	public function applicantPaymentPlanAction(){
		
		$appl_id = $this->_getParam('id', null);
		$this->view->appl_id = $appl_id;
		
		$this->_helper->layout()->disableLayout();
		
		$paymentPlanDb = new Studentfinance_Model_DbTable_PaymentPlan();
		
		$data = $paymentPlanDb->getApplicantPaymentPlan($appl_id);
		$this->view->data = $data;		
		
	}
	
	public function invoicePaymentPlanAction(){
		$appl_id = $this->_getParam('appl_id', null);
		
		$msg = $this->_getParam('msg', null);
		$this->view->noticeMessage = $msg;
		
		$step = $this->_getParam('step', 1);
		$this->view->step = $step;
		
		$this->view->title = "Create Applicant Payment Plan";
		
		//set custom session
		$ses_payment_plan = new Zend_Session_Namespace('payment_plan');
		
		if($appl_id){
			Zend_Session::namespaceUnset('payment_plan');
			
			$ses_payment_plan = new Zend_Session_Namespace('payment_plan');
			$ses_payment_plan->appl_id = $appl_id;
			
			//redirect
			$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>1),'default',true));
		}
		
		//applicant info
    	$applicantProfileDb = new App_Model_Application_DbTable_ApplicantProfile();
    	$this->view->profile = $applicantProfileDb->getData($ses_payment_plan->appl_id);
		
		if( isset($ses_payment_plan->appl_id) ){
			if($step==1){
				
				if ($this->getRequest()->isPost()) {
					$formData = $this->getRequest()->getPost();
					
					if( isset($formData['invoices'])){
						$ses_payment_plan->invoice = $formData['invoices'];
						$ses_payment_plan->invoice_knockoff = null;
						$ses_payment_plan->distribution = null;
					}else{
						$ses_payment_plan->invoice = null;
						$ses_payment_plan->invoice_knockoff = null;
						$ses_payment_plan->distribution = null;
					}
					
					//redirect
					$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>2),'default',true));
					
				}else{
					//active invoice
					$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
					$invoiceList = $invoiceMainDb->getApplicantInvoiceData($ses_payment_plan->appl_id,true);
										
					if($invoiceList){
						
						//remove paid invoice
						foreach ($invoiceList as $index=>$invoice){
							
							if($invoice['bill_balance']==0 && $invoice['bill_balance']!=null){
								unset($invoiceList[$index]);
							}
						}
						$invoiceList = array_values($invoiceList);
						
						//remove invoice with credit note
						$creditNoteDb = new Studentfinance_Model_DbTable_CreditNote();
						foreach ($invoiceList as $index=>$invoice){
							if($creditNoteDb->getDataByInvoice($invoice['bill_number'])){
								unset($invoiceList[$index]);
							}
						}
						$invoiceList = array_values($invoiceList);
						
					}
						
					if(isset($ses_payment_plan->invoice)){
					
						//set checked from data session
					
						
						foreach ($invoiceList as $index=>$invoice){
							
							if(in_array($invoice['id'],array_values($ses_payment_plan->invoice))){
								$invoiceList[$index]['selected'] = true;
							}else{
								$invoiceList[$index]['selected'] = false;
							}
						}
					}
					
					$this->view->invoice_list = $invoiceList;
					
					

				}
				
				
			}else
			if($step==2){
				
				if ($this->getRequest()->isPost()) {
					$formData = $this->getRequest()->getPost();
					
					if( is_numeric($formData['installment']) && $formData['installment'] > 0 ){
						
						//reset distribution if different amount
						if( $ses_payment_plan->installment !=  $formData['installment'] ){
							$ses_payment_plan->distribution = null;	
						}
						
						$ses_payment_plan->installment = $formData['installment'];
						
					}else{
						$ses_payment_plan->installment = null;
						$ses_payment_plan->distribution = null;
					}
					
					//redirect
					$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>4),'default',true));
				}else{
					if(!isset($ses_payment_plan->invoice)){
						$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>1, 'msg'=>'Please Complete step 1 process'),'default',true));
					}
						
					if(isset($ses_payment_plan->installment)){
						$this->view->installment = $ses_payment_plan->installment;
					}
					
				}
				
			}else
			if($step==3){
				
			//redirect 
			$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>4),'default',true));
			exit;
			
				if ($this->getRequest()->isPost()) {
					$formData = $this->getRequest()->getPost();
					
					if( isset($formData['invoice']) ){
						$invoice_arr = array();
						foreach ($formData['invoice'] as $index => $invoice){
							$invoice_arr[] = array(
								'invoice_no'=>$invoice,
								'adv_amt'=> $formData['invoice_adv_pymt_amt'][$index]
							);
						}
						
						$ses_payment_plan->invoice_knockoff = $invoice_arr;
						$ses_payment_plan->distribution = null;
					}else{
						$ses_payment_plan->invoice_knockoff = null;
					}
					
					
					//redirect
					$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>4),'default',true));
				}else{
					
					if( isset($ses_payment_plan->installment) && isset($ses_payment_plan->invoice) ){
						
						$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
						$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
						
						$invoices = array();
						
						//get fee item setup
						$feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
						$feeItemList = $feeItemDb->getData();
						
						//loop each invoice						
						foreach ($ses_payment_plan->invoice as $index=>$invoice_id){
							//get selected invoice data
							$invoices[$index] = $invoiceMainDb->getData($invoice_id);
							
							//get invoice detail
							$invoices[$index]['invoice_detail'] = $invoiceDetailDb->getInvoiceDetail($invoice_id);
							
							
						
							//sum amount to fee item
							foreach ($invoices[$index]['invoice_detail'] as $inv_dtl){
								
								foreach($feeItemList as $feeItemIndex => $feeItem){
									
									if($feeItem['fi_id']==$inv_dtl['fi_id']){
										if(isset($feeItemList[$feeItemIndex]['total_amount'])){
											$feeItemList[$feeItemIndex]['total_amount'] += $inv_dtl['amount'];
										}else{
											$feeItemList[$feeItemIndex]['total_amount'] = $inv_dtl['amount'];
										}
										break;	
									}
								}
							}
						}
						
						$this->view->invoices = $invoices;
						$this->view->fee_item = $feeItemList;
						
						//get advance payment
						$advPmntDb = new Studentfinance_Model_DbTable_AdvancePayment();
						$advancePaymentList = $advPmntDb->getApplicantBalanceAvdPayment($ses_payment_plan->appl_id);
						
						$this->view->advancePaymentList = $advancePaymentList;
						
						$advance_payment_balance = 0;
						if($advancePaymentList){
							foreach ($advancePaymentList as $avd_payment){
								$advance_payment_balance += $avd_payment['advpy_total_balance'];
							}
						}
						
						$this->view->advance_payment_balance = $advance_payment_balance;
						
						if( isset($ses_payment_plan->invoice_knockoff) ){
							$this->view->invoice_knockoff = $ses_payment_plan->invoice_knockoff;
						}
						

					}else{
						$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>2, 'msg'=>'Please Complete step 2 process'),'default',true));
					}
					
				}
				
			}else
			if($step==4){
				
				if ($this->getRequest()->isPost()) {
					$formData = $this->getRequest()->getPost();
					
					$fi_loop_index = 0;
					$installment = array();
					
					for($i=0; $i<sizeof($formData['installment']); $i++){
						$feeItem = array();			
									
						//fee item loop based in size
						for($fi_cnt = 1; $fi_cnt<=$formData['fee_item_size']; $fi_cnt++){
							//echo $formData['feeItemId']['fi_loop_index'];
							$feeItem[] = array(
								'fi_id' => $formData['feeItemId'][$fi_loop_index],
								'percentage' => $formData['feeItemPercent'][$fi_loop_index],
								'fix_amount' => $formData['feeItemAmount'][$fi_loop_index],
								'value' => $formData['feeItemValue'][$fi_loop_index],
								'tot_value' => $formData['totVal'][$fi_loop_index]
							);
							
							$fi_loop_index++;
						}
						
						$installment[] = $feeItem;
					}
					
										
					$ses_payment_plan->distribution = $installment;
										
					//redirect
					$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>5),'default',true));
				}else{
					
					if( isset($ses_payment_plan->installment) && isset($ses_payment_plan->invoice) ){
						
						$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
						$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
						
						$invoices = array();
						
						//get fee item setup
						$feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
						$feeItemList = $feeItemDb->getData();
						
						//loop each selected invoice id and get detail data						
						foreach ($ses_payment_plan->invoice as $index=>$invoice_id){
							
							//get selected invoice data
							$invoices[$index] = $invoiceMainDb->getData($invoice_id);
							
							//get invoice detail
							$invoices[$index]['invoice_detail'] = $invoiceDetailDb->getInvoiceDetail($invoice_id);
						}
						
						//initial set fee item to paid according to invoice paid amount
						foreach ($invoices as $index => $invoice){
							$invoice_paid_amount = $invoice['bill_paid'];
							
							foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
								if( $invoice_paid_amount > $invoice_detail['amount'] ){
									$invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
									$invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
									$invoice_paid_amount = $invoice_paid_amount - $invoice_detail['amount'];
								}else{
									$invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_paid_amount;
									$invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $invoice_paid_amount;
									$invoice_paid_amount = $invoice_paid_amount - $invoice_paid_amount;
								}
							}
						}
						
						//loop each invoice & reduce fee item amount with advance payment option			
						if( isset($ses_payment_plan->invoice_knockoff) && $ses_payment_plan->invoice_knockoff!=null ){
							foreach ($invoices as $index => $invoice){
								foreach ($ses_payment_plan->invoice_knockoff as $invoice_knockoff){
									
									if($invoice['bill_number'] == $invoice_knockoff['invoice_no']){
										
										//calculate paid balance
										$invoices[$index]['bill_paid'] += $invoice_knockoff['adv_amt'];
										$invoices[$index]['bill_balance'] -= $invoice_knockoff['adv_amt'];

										//calculate fee item amount balance
										$adv_pymt_paid_amount = $invoice_knockoff['adv_amt'];
										foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
											if( $adv_pymt_paid_amount > $invoice_detail['amount'] ){
												$invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
												$invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
												$adv_pymt_paid_amount = $adv_pymt_paid_amount - $invoice_detail['amount'];
											}else{
												$invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $adv_pymt_paid_amount;
												$invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $adv_pymt_paid_amount;
												$adv_pymt_paid_amount = $adv_pymt_paid_amount - $adv_pymt_paid_amount;
											}
										}
									}
								}	
							}
						}
						
						//loop each invoice & calculate and inject array with fee item amount
						foreach ($invoices as $index => $invoice){	
							foreach ($invoice['invoice_detail'] as $inv_dtl){
								foreach($feeItemList as $feeItemIndex => $feeItem){
									if($feeItem['fi_id']==$inv_dtl['fi_id']){
										if(isset($feeItemList[$feeItemIndex]['total_amount'])){
											$feeItemList[$feeItemIndex]['total_amount'] += $inv_dtl['balance'];
										}else{
											$feeItemList[$feeItemIndex]['total_amount'] = $inv_dtl['balance'];
										}
										
										break;
									}
								}
							}
						}
						
						if( isset($ses_payment_plan->distribution) ){
							$this->view->distribution = $ses_payment_plan->distribution;
						}
						
						
						
						$this->view->invoices = $invoices;
						$this->view->fee_item = $feeItemList;
						$this->view->installment = $ses_payment_plan->installment;
						
					}else{
						$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>2, 'msg'=>'Please Complete step 2 process'),'default',true));
					}
					
				}
				
			}else
			if($step==5){
				
				$this->view->appl_id = $ses_payment_plan->appl_id;
				
				if(isset($ses_payment_plan->invoice) && isset($ses_payment_plan->distribution)){
					
					//invoice
					$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
					$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
					
					$invoiceList = array();
					foreach ($ses_payment_plan->invoice as $index=>$invoice_id){
						$invoiceList[] = $invoiceMainDb->getData($invoice_id);
					}
					
					//invoice detail
					foreach ($invoiceList as $index => $invoice){
						$invoiceList[$index]['invoice_detail'] = $invoiceDetailDb->getInvoiceDetail($invoice['id']);
					}
					
					
					//get advance payment
					$advPmntDb = new Studentfinance_Model_DbTable_AdvancePayment();
					$advancePaymentList = $advPmntDb->getApplicantBalanceAvdPayment($ses_payment_plan->appl_id);
					
					$this->view->advancePaymentList = $advancePaymentList;
					
					$advance_payment_balance = 0;
					if($advancePaymentList){
						foreach ($advancePaymentList as $avd_payment){
							$advance_payment_balance += $avd_payment['advpy_total_balance'];
						}
					}
					$this->view->advance_payment_balance = $advance_payment_balance;
					
					if( isset($ses_payment_plan->invoice_knockoff) ){
						
						$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
						
						$invoice_knockoff_list = array();
						foreach ($ses_payment_plan->invoice_knockoff as $index =>$invoice_knockoff){
							$invoice_knockoff_list[$index] = $invoice_knockoff;
							$invoice_knockoff_list[$index]['invoice_detail'] = $invoiceMainDb->getInvoiceData($invoice_knockoff['invoice_no'],true);
						}
						
						$this->view->invoice_knockoff = $invoice_knockoff_list;
					}
					
					//fee item distribution
					//get fee item setup
					$feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
					$feeItemList = $feeItemDb->getData();
					
					//initial set fee item to paid according to invoice paid amount
					foreach ($invoiceList as $index => $invoice){
						$invoice_paid_amount = $invoice['bill_paid'];
						
						foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
							if( $invoice_paid_amount > $invoice_detail['amount'] ){
								$invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
								$invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
								$invoice_paid_amount = $invoice_paid_amount - $invoice_detail['amount'];
							}else{
								$invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_paid_amount;
								$invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $invoice_paid_amount;
								$invoice_paid_amount = $invoice_paid_amount - $invoice_paid_amount;
							}
						}
					}
					
					
						
					//invoice knockoff using advance payment
					if( isset($ses_payment_plan->invoice_knockoff) ){
						foreach ($invoiceList as $index => $invoice){
							
							foreach ($ses_payment_plan->invoice_knockoff as $invoice_knockoff){
								
								if($invoice['bill_number'] == $invoice_knockoff['invoice_no']){
									
									//calculate fee item amount balance
									$adv_pymt_paid_amount = $invoice_knockoff['adv_amt'];
									foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
										if( $adv_pymt_paid_amount > $invoice_detail['amount'] ){
											$invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
											$invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
											$adv_pymt_paid_amount = $adv_pymt_paid_amount - $invoice_detail['amount'];
										}else{
											$invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $adv_pymt_paid_amount;
											$invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $adv_pymt_paid_amount;
											$adv_pymt_paid_amount = $adv_pymt_paid_amount - $adv_pymt_paid_amount;
										}
									}
								}
							}	
						}
					}
					
					//loop each invoice & calculate and inject array with fee item amount
					foreach ($invoiceList as $index => $invoice){	
						foreach ($invoice['invoice_detail'] as $inv_dtl){
							foreach($feeItemList as $feeItemIndex => $feeItem){
								if($feeItem['fi_id']==$inv_dtl['fi_id']){
									if(isset($feeItemList[$feeItemIndex]['total_amount'])){
										$feeItemList[$feeItemIndex]['total_amount'] += $inv_dtl['balance'];
									}else{
										$feeItemList[$feeItemIndex]['total_amount'] = $inv_dtl['balance'];
									}
									break;
								}
							}
						}
					}
					
					$this->view->invoice_list = $invoiceList;
					$this->view->fee_item = $feeItemList;
										
					//fee item installment
					$this->view->installment = $ses_payment_plan->installment;
					
					//distribution
					if( isset($ses_payment_plan->distribution) ){
						$this->view->distribution = $ses_payment_plan->distribution;
					}
					
				}else{
					//redirect
					$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-payment-plan','step'=>4, 'msg'=>'Please Complete step 4 process'),'default',true));	
				}
				
			}else 
			if($step=='confirm'){
				if ($this->getRequest()->isPost()) {
					
					$formData = $this->getRequest()->getPost();

					//data from session
					$appl_id = $ses_payment_plan->appl_id;
					$invoice = $ses_payment_plan->invoice;
					$installment = $ses_payment_plan->installment;
					$invoice_knockoff = $ses_payment_plan->invoice_knockoff;
					$fee_item_distribution = $ses_payment_plan->distribution;
					
					//invoice detail
					$invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
					$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
					
					foreach ($invoice as $index=>$item){
						$invoice[$index] = $invoiceMainDb->getData($item);
						$invoice[$index]['detail'] = $invoiceDetailDb->getInvoiceDetail($item);
					}
					
					//advance payment record
					$advancePaymentDb = new Studentfinance_Model_DbTable_AdvancePayment();
					$advance_payment_list = $advancePaymentDb->getApplicantBalanceAvdPayment($appl_id);
					
					$advance_payment = array();
					$advance_payment['record'] = $advance_payment_list;
					$advance_payment['total_amount'] = 0;
					
					foreach ($advance_payment_list as $adv_pymt){
						$advance_payment['total_amount'] += $adv_pymt['advpy_total_balance'];		
					}
					
					
					/*echo "<pre>";
					print_r($fee_item_distribution);
					echo "</pre>";
					exit;*/
					
					$auth = Zend_Auth::getInstance();
			
					$db = Zend_Db_Table::getDefaultAdapter();
					$db->beginTransaction();
					
					try {
						/*
						 * process invoice knock-off from advance payment
						 * edited: remove advance payment in payment plan
						 */
						
						/*
						$adv_pymt_amount = $advance_payment['total_amount'];
						$adv_pymt_list = $advance_payment['record'];
						
						
						$advancePaymentDetailDb = new Studentfinance_Model_DbTable_AdvancePaymentDetail();
						
						foreach($invoice as $inv){
						
							foreach($invoice_knockoff as $inv_knockoff){
								
								if($inv['bill_number'] == $inv_knockoff['invoice_no']){
									
									for($i=0;$i<sizeof($adv_pymt_list);$i++){
										
										$curr_advance_payment = $adv_pymt_list[$i];
										
										
										//even we have cater this in advance payment process. 
										//this is to ensure advance payment amount is sufficient to knockoff the invoice
										
										if( $curr_advance_payment['advpy_total_balance']>= $inv_knockoff['adv_amt']){
											$adv_pymt_list[$i]['advpy_total_balance'] = $adv_pymt_list[$i]['advpy_total_balance'] - $inv_knockoff['adv_amt'];
											
											//insert advance payment detail record
											$data = array(
												'advpydet_advpy_id' => $curr_advance_payment['advpy_id'],
												'advpydet_bill_no' => $inv_knockoff['invoice_no'],
												'advpydet_total_paid' => $inv_knockoff['adv_amt']
											);
											$advancePaymentDetailDb->insert($data);
											
											
											//update advance payment main record
											$data = array(
												'advpy_total_paid' => $curr_advance_payment['advpy_total_paid']+$inv_knockoff['adv_amt'],
												'advpy_total_balance' => $curr_advance_payment['advpy_total_balance']-$inv_knockoff['adv_amt'],
											);
											$advancePaymentDb->update($data, 'advpy_id = '.$curr_advance_payment['advpy_id']);
											
											
											//update invoice
											$data = array(
												'bill_paid' => $inv['bill_paid']+$inv_knockoff['adv_amt'],
												'bill_balance' => $inv['bill_balance']-$inv_knockoff['adv_amt'],
											);
											$invoiceMainDb->update($data, 'id = '.$inv['id']);
											

											//break loop for next invoice knockoff
											break 1;
										}
									}
								}
									
							}
							
						}
						
						*/
	
						
						/*
						 * save payment plan record
						 */
						
						$amount = 0;//calculate amount
						foreach ($fee_item_distribution as $installment_data){
							foreach ($installment_data as $fee_item){	
								$amount += $fee_item['value'];
							}
						}
						
						$paymentPlanDb = new Studentfinance_Model_DbTable_PaymentPlan();
						$data = array(
							'pp_appl_id' =>$appl_id,
							'pp_installment_no' =>$installment,
							'pp_amount' => $amount
						);
						$paymentplan_id = $paymentPlanDb->insert($data);
						
						
						
						$paymentPlanInvoiceDb = new Studentfinance_Model_DbTable_PaymentPlanInvoice();
						$auth = Zend_Auth::getInstance();
						
						//loop invoice
						foreach ($invoice as $inv){
							
							/*
							 * save payment plan invoice (invoice that will be canceled)
							 */
							$data = array(
								'ppi_plan_id' => $paymentplan_id,
								'ppi_invoice_id' => $inv['id'],
								'ppi_invoice_no' => $inv['bill_number']
							);
							$paymentPlanInvoiceDb->insert($data);
							
							
							/*
							 * cancel selected invoice for payment plan
							 */

							$data=array(
								'status'=>'X',
								'cancel_by' => $auth->getIdentity()->iduser,
								'cancel_date' => date('Y-m-d H:i:s')
							);	
							$this->_DbObj->update($data, 'id = '.$inv['id']);
						}
						
						
						
						/*
						 * generate new invoice from payment plan
						 */
						
						//get applicant fomulir no. from invoice
						$fomulir = array();
						foreach($invoice as $inv){
						    if( !in_array($inv['no_fomulir'],$fomulir) ){
						        $fomulir[] = $inv['no_fomulir'];
						    }
						}
						
						if(sizeof($fomulir) > 1){
							throw new Exception("Have 2 or more fomulir number. Cannot generate new invoice number");
						};
						
						//get next packet no. in invoice for given fomulir no.
						$paket_no = $this->_DbObj->getMaxPaketNo($fomulir[0]);
						
						//current academic year
						$academicYearDb = new App_Model_Record_DbTable_AcademicYear();
						$currenctAcademicyear = $academicYearDb->getCurrentAcademicYearData();
						
						//loop installment distribution to inject fee_item definition
						
						$feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
						foreach ($fee_item_distribution as $index=>$dist){
							foreach ($dist as $key=>$fee_item){
								//fee item description
								$fee_item_distribution[$index][$key]['fee_description'] = $feeItemDb->getData($fee_item['fi_id']);
							}
						}
						
						//get transaction data
						$txnDb = new App_Model_Application_DbTable_ApplicantTransaction();
						$txnData = $txnDb->getTransactionDataByFomulir($fomulir[0]);
						
						//get program info
						$applicantProgramDb = new App_Model_Application_DbTable_ApplicantProgram();
						$applicant_program = $applicantProgramDb->getOfferProgram($txnData['at_trans_id'],$txnData['at_appl_type']);
						
						$programDb = new App_Model_Record_DbTable_Program();
						$program = $programDb->getData($applicant_program['program_id']);
						
								
						//loop installment distribution
						foreach ($fee_item_distribution as $index=>$dist){
							
							//calculate total amount
							$total_amount = 0;
							foreach ($dist as $key=>$fee_item){	
								$total_amount += $fee_item['value'];
							}
							
							//insert main bill
							$data = array(
										'bill_number' => $paket_no.($index+1).$fomulir[0],
										'appl_id' => $appl_id,
										'no_fomulir' => $fomulir[0],
										'academic_year' => $currenctAcademicyear['ay_id'],
										//'semester' => '',
										'bill_amount' => $total_amount,
										'bill_paid' => 0,
										'bill_balance' => $total_amount,
										'bill_description' => $program['ShortName']."-PP".$paket_no." Cicilan ".($index+1),
										'college_id' => $program['IdCollege'],
										'program_code' => $program['ProgramCode'],
										'creator' => '1',
										'fs_id' => 0,
										'fsp_id' => 0,
										'status' => 'A',
										'date_create' => date('Y-m-d H:i:s')
									);

							$main_id = $this->_DbObj->insert($data);

							
							//insert bill detail
							$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
							foreach ($dist as $fee_item){
								
								$data_detail = array(
												'invoice_main_id' => $main_id,
												'fi_id' => $fee_item['fi_id'],
												'fee_item_description' => $fee_item['fee_description']['fi_name_bahasa'],
												'amount' => isset($fee_item['value']) && $fee_item['value']!=""?$fee_item['value']:0.00
											);
																
								$invoiceDetailDb->insert($data_detail);
							}
							
							/*
							 * save payment plan detail
						 	 */
							$paymentPlanDetail = new Studentfinance_Model_DbTable_PaymentPlanDetail();
							$data = array(
								'ppd_payment_plan_id' => $paymentplan_id,
								'ppd_invoice_id' => $main_id,
								'ppd_installment_no' => ($index+1)
							);
							
							$paymentPlanDetail->insert($data);
						
						
						}
						
						
						$db->commit();
						
						//redirect
						$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'applicant','id'=>$txnData['at_appl_id']),'default',true));
						
					}catch (Exception $e) {
						echo "Error in Invoice Payment Plan. <br />";
						echo $e->getMessage();
						
						echo "<pre>";
						print_r($e->getTrace());
						echo "</pre>";
						
						$db->rollBack();
		    			
		    			exit;
		    			
					}
					
					
				}
				
				exit;
			}else{
				//redirect
				$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice'),'default',true));	
			}
		}else{
			//redirect
			$this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice'),'default',true));	
		}
	}
	
	public function applicantPaymentPlanDetailAction(){
		
		$this->_helper->layout->disableLayout();
		
		$this->view->title = $this->view->translate("Payment Plan Detail");
		
		$payment_plan_id = $this->_getParam('id', null);
		$this->view->id = $payment_plan_id;
		
		$installment = $this->_getParam('installment', 1);
		$this->view->installment = $installment;
		

		$paymentPlanDb = new Studentfinance_Model_DbTable_PaymentPlan();
		$data = $paymentPlanDb->getData($payment_plan_id);
		
		$paymentPlanDetailDb = new Studentfinance_Model_DbTable_PaymentPlanDetail();
		
		$data['detail'] = $paymentPlanDetailDb->getPaymentPlanDetail($data['pp_id']);
		
		$this->view->data = $data;
		
	}
	
	public function printApplicantBankSlipAction(){
		
		$payment_plan_id = $this->_getParam('id', null);
		$this->view->id = $payment_plan_id;
		
		$installment = $this->_getParam('installment', 1);
		$this->view->installment = $installment;
		
		$bankslip = new icampus_Function_Application_BankSlip();
		
		$bankslip->generateBankSlip($payment_plan_id, $installment);
		exit;
	}
	
	public function printBankSlipAction(){
		$invoice_id = $this->_getParam('invoice_id', null);
		
		$bankslip = new icampus_Function_Application_BankSlip();
		
		$bankslip->printBankSlip($invoice_id);
		exit;
	}
	
	public function studentAction(){
	
		$IdStudentRegistration = $this->_getParam('id', null);
		$this->view->student_registration_id = $IdStudentRegistration;
	
		//title
		$this->view->title= $this->view->translate("Invoice : Student Detail");
		 
		//registration info
		$studentRegistrationDb = new Registration_Model_DbTable_Studentregistration();
		$registration = $studentRegistrationDb->getData($IdStudentRegistration);
		$this->view->registration = $registration;
		
		//transaction info
		$transDB = new App_Model_Application_DbTable_ApplicantTransaction();
		$txnData = $transDB->fetchRow('at_trans_id = '.$registration['transaction_id']);
		$this->view->txn_data = $txnData;
		
		//student profile
		$studentProfileDb = new Records_Model_DbTable_Studentprofile();
		$profile = $studentProfileDb->fnGetStudentProfileByApplicationId($registration['IdApplication']);
		$this->view->profile = $profile;
		
	}
	
	public function studentInvoiceAction(){
		
		
		$this->_helper->layout()->disableLayout();
	
		if( $this->getRequest()->isXmlHttpRequest() ){
			$this->_helper->layout->disableLayout();
		}
	
		$token = $this->_getParam('token',null);
		$IdStudentRegistration = $this->_getParam('stdid', null);
			
		
		$dbToken=new App_Model_General_DbTable_Token();
		
		if ($dbToken->isTokenValid($token, $IdStudentRegistration)) {
				
				//registration info
				$studentRegistrationDb = new App_Model_General_DbTable_Studentregistration();
				$registration = $studentRegistrationDb->getData($IdStudentRegistration);
				 
				//transaction info
				$transDB = new App_Model_General_DbTable_ApplicantTransaction();
				$txnData = $transDB->fetchRow('at_trans_id = '.$registration['transaction_id']);
				 
			
				$db = Zend_Db_Table::getDefaultAdapter();
			
				//application invoice
				$select1 = $db->select()
				->from(array('im'=>'invoice_main'),array(
						'id'=>'im.id',
						'record_date'=>'im.date_create',
						'description' => 'im.bill_description',
						'program_code' => 'im.program_code',
						'txn_type' => new Zend_Db_Expr ('"Invoice"'),
						'debit' =>'bill_amount',
						'invoice_no' => 'bill_number',
						'no_fomulir' => 'no_fomulir',
						'paid' => 'bill_paid',
						'cn' => 'cn_amount',
						'dn' => 'dn_amount',
						'balance' => 'bill_balance',
						'status' => 'status',
				)
				)
				->where('im.appl_id = ?', $registration['IdApplication'])
				->where('im.no_fomulir = ?', $txnData['at_pes_id'])
				->order('im.date_create')
				->order('im.id');
				
				$row1 = $db->fetchAll($select1);
				
				if(!$row1){
					$row1 = null;
				}else{
					//get payment status
					$paymentDb = new App_Model_General_DbTable_PaymentMain();
				
					foreach ($row1 as $index=>$data){
				
						$row1[$index]['payment'] = $paymentDb->getInvoicePaymentRecord($data['invoice_no'], $data['no_fomulir']);
					}
				}
				
				$invoice_application = $row1;
				
				//Student invoice
				$select2 = $db->select()
				->from(
						array('im'=>'invoice_main'),array(
						'id'=>'im.id',
						'record_date'=>'im.date_create',
						'description' => 'im.bill_description',
						'program_code' => 'im.program_code',
						'txn_type' => new Zend_Db_Expr ('"Invoice"'),
						'debit' =>'bill_amount',
						'invoice_no' => 'bill_number',
						'no_fomulir' => 'no_fomulir',
						'paid' => 'bill_paid',
						'cn' => 'cn_amount',
						'dn' => 'dn_amount',
						'balance' => 'bill_balance',
						'status' => 'status',
						)
				)
				->where('im.IdStudentRegistration = ?', $registration['IdStudentRegistration'])
				->order('im.date_create')
				->order('im.id');
				
				$row2 = $db->fetchAll($select2);
			
				if(!$row2){
					$row2 = null;
				}else{
					//get payment status
					$paymentDb = new App_Model_General_DbTable_PaymentMain();
						
					foreach ($row2 as $index=>$data){
			
						$row2[$index]['payment'] = $paymentDb->getInvoicePaymentRecord($data['invoice_no'], $data['no_fomulir']);
					}
				}
			
				$invoice_student = $row2;
				$invoce=array('applicant'=>$invoice_application,"student"=>$invoice_student);
				
			} else {
				$invoce=array();
			
			}
			$this->_helper->layout->disableLayout();
			
			$ajaxContext = $this->_helper->getHelper('AjaxContext');
				
			$ajaxContext->addActionContext('view', 'html')
			->addActionContext('form', 'html')
			->addActionContext('process', 'json')
			->initContext();
			
			$json = Zend_Json::encode($invoce);
			
			echo $json;
			exit();
	
	}
	
	public function studentDeleteInvoiceAction(){
	  $invoice_id = $this->_getParam('id', null);
	  $idStudentRegistration = $this->_getParam('idreg', null);
	
	  $auth = Zend_Auth::getInstance();
	
	  //get invoice main data
	  $invoice = $this->_DbObj->getData($invoice_id);
	
	  //add invoice delete history
	  $invoiceDeleteHistoryDb = new Studentfinance_Model_DbTable_InvoiceDeleteHistory();
	  $invoice['invoice_main_id'] = $invoice['id'];
	  $invoice['id'] = null;
	  $invoice['delete_by'] = $auth->getIdentity()->iduser;
	  $invoice['delete_date'] = date('Y-m-d H:i:s');
	
	  $invoiceDeleteHistoryDb->insert($invoice);
	
	  //delete
	  $this->_DbObj->delete('id = '.$invoice_id);
	
	  //redirect
	  $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'student','id'=>$idStudentRegistration),'default',true));
	}
	
	public function studentPaymentPlanAction(){
	
	  $appl_id = $this->_getParam('id', null);
	  $this->view->appl_id = $appl_id;
	  
	  $idStudentRegistration = $this->_getParam('idStudentRegistration', null);
	  $this->view->idStudentRegistration = $idStudentRegistration;
	
	  $this->_helper->layout()->disableLayout();
	
	  $paymentPlanDb = new Studentfinance_Model_DbTable_PaymentPlan();
	
	  $data = $paymentPlanDb->getApplicantPaymentPlan($appl_id);
	  $this->view->data = $data;
	
	}
	
	
	public function invoiceStudentPaymentPlanAction(){
	  $appl_id = $this->_getParam('appl_id', null);
	  $idStudentRegistration = $this->_getParam('idStudentRegistration', null);
	
	  $msg = $this->_getParam('msg', null);
	  $this->view->noticeMessage = $msg;
	
	  $step = $this->_getParam('step', 1);
	  $this->view->step = $step;
	
	  $this->view->title = "Create Student Payment Plan";
	
	  //set custom session
	  $ses_payment_plan = new Zend_Session_Namespace('payment_plan');
	
	  if($appl_id){
	    Zend_Session::namespaceUnset('payment_plan');
	    	
	    $ses_payment_plan = new Zend_Session_Namespace('payment_plan');
	    $ses_payment_plan->appl_id = $appl_id;
	    $ses_payment_plan->idStudentRegistration = $idStudentRegistration;
	    	
	    //redirect
	    $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>1),'default',true));
	  }
	
	  //applicant info
	  $applicantProfileDb = new App_Model_Application_DbTable_ApplicantProfile();
	  $this->view->profile = $applicantProfileDb->getData($ses_payment_plan->appl_id);
	  
	  //registration info
      $studentRegistrationDb = new Registration_Model_DbTable_Studentregistration();
      $registration = $studentRegistrationDb->getData($ses_payment_plan->idStudentRegistration);
      $this->view->registration = $registration;
      
      
	
	  if( isset($ses_payment_plan->appl_id) ){
	    if($step==1){
	      
	      //semester list
	      $semesterDb = new Records_Model_DbTable_SemesterMaster();
	      $semester_list = $semesterDb->fetchAll(null,'SemesterMainStartDate DESC')->toArray();
	      $this->view->semester_list = $semester_list;
	       
	      $semester_id = $this->_getParam('sem', $semester_list[0]['IdSemesterMaster']);
	      $this->view->semester_id = $semester_id;
	
	      if ($this->getRequest()->isPost()) {
	        $formData = $this->getRequest()->getPost();
	        	
	        if( isset($formData['invoices'])){
	          $ses_payment_plan->invoice = $formData['invoices'];
	          $ses_payment_plan->invoice_knockoff = null;
	          $ses_payment_plan->distribution = null;
	        }else{
	          $ses_payment_plan->invoice = null;
	          $ses_payment_plan->invoice_knockoff = null;
	          $ses_payment_plan->distribution = null;
	        }
	        
	        $ses_payment_plan->semester = $semester_id;
	        	
	        //redirect
	        $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>2),'default',true));
	        	
	      }else{
	        
	        
	        
	        //active invoice
	        $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
	        $invoiceList = $invoiceMainDb->getStudentInvoiceData($ses_payment_plan->appl_id,null,$semester_id,null,true);
	
	        if($invoiceList){
	
	          //remove paid invoice
	          foreach ($invoiceList as $index=>$invoice){
	            	
	            if($invoice['bill_balance']==0 && $invoice['bill_balance']!=null){
	              unset($invoiceList[$index]);
	            }
	          }
	          $invoiceList = array_values($invoiceList);
	
	          //remove invoice with credit note
	          $creditNoteDb = new Studentfinance_Model_DbTable_CreditNote();
	          foreach ($invoiceList as $index=>$invoice){
	            if($creditNoteDb->getDataByInvoice($invoice['bill_number'])){
	              unset($invoiceList[$index]);
	            }
	          }
	          $invoiceList = array_values($invoiceList);
	
	        }
	
	        if(isset($ses_payment_plan->invoice)){
	          	
	          //set checked from data session
	          	
	
	          foreach ($invoiceList as $index=>$invoice){
	            	
	            if(in_array($invoice['id'],array_values($ses_payment_plan->invoice))){
	              $invoiceList[$index]['selected'] = true;
	            }else{
	              $invoiceList[$index]['selected'] = false;
	            }
	          }
	        }
	        	
	        $this->view->invoice_list = $invoiceList;
	        	
	        	
	
	      }
	
	
	    }else
	      if($step==2){
	
	      if ($this->getRequest()->isPost()) {
	        $formData = $this->getRequest()->getPost();
	        	
	        if( is_numeric($formData['installment']) && $formData['installment'] > 0 ){
	
	          //reset distribution if different amount
	          if( $ses_payment_plan->installment !=  $formData['installment'] ){
	            $ses_payment_plan->distribution = null;
	          }
	
	          $ses_payment_plan->installment = $formData['installment'];
	
	        }else{
	          $ses_payment_plan->installment = null;
	          $ses_payment_plan->distribution = null;
	        }
	        	
	        //redirect
	        $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>4),'default',true));
	      }else{
	        if(!isset($ses_payment_plan->invoice)){
	          $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>1, 'msg'=>'Please Complete step 1 process'),'default',true));
	        }
	
	        if(isset($ses_payment_plan->installment)){
	          $this->view->installment = $ses_payment_plan->installment;
	        }
	        	
	      }
	
	    }else
	      if($step==3){
	
	      //redirect
	    $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>4),'default',true));
	    exit;
	    	
	    if ($this->getRequest()->isPost()) {
	      $formData = $this->getRequest()->getPost();
	      	
	      if( isset($formData['invoice']) ){
	        $invoice_arr = array();
	        foreach ($formData['invoice'] as $index => $invoice){
	          $invoice_arr[] = array(
	              'invoice_no'=>$invoice,
	              'adv_amt'=> $formData['invoice_adv_pymt_amt'][$index]
	          );
	        }
	
	        $ses_payment_plan->invoice_knockoff = $invoice_arr;
	        $ses_payment_plan->distribution = null;
	      }else{
	        $ses_payment_plan->invoice_knockoff = null;
	      }
	      	
	      	
	      //redirect
	      $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>4),'default',true));
	    }else{
	      	
	      if( isset($ses_payment_plan->installment) && isset($ses_payment_plan->invoice) ){
	
	        $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
	        $invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
	
	        $invoices = array();
	
	        //get fee item setup
	        $feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
	        $feeItemList = $feeItemDb->getData();
	
	        //loop each invoice
	        foreach ($ses_payment_plan->invoice as $index=>$invoice_id){
	          //get selected invoice data
	          $invoices[$index] = $invoiceMainDb->getData($invoice_id);
	          	
	          //get invoice detail
	          $invoices[$index]['invoice_detail'] = $invoiceDetailDb->getInvoiceDetail($invoice_id);
	          	
	          	
	
	          //sum amount to fee item
	          foreach ($invoices[$index]['invoice_detail'] as $inv_dtl){
	
	            foreach($feeItemList as $feeItemIndex => $feeItem){
	              	
	              if($feeItem['fi_id']==$inv_dtl['fi_id']){
	                if(isset($feeItemList[$feeItemIndex]['total_amount'])){
	                  $feeItemList[$feeItemIndex]['total_amount'] += $inv_dtl['amount'];
	                }else{
	                  $feeItemList[$feeItemIndex]['total_amount'] = $inv_dtl['amount'];
	                }
	                break;
	              }
	            }
	          }
	        }
	
	        $this->view->invoices = $invoices;
	        $this->view->fee_item = $feeItemList;
	
	        //get advance payment
	        $advPmntDb = new Studentfinance_Model_DbTable_AdvancePayment();
	        $advancePaymentList = $advPmntDb->getApplicantBalanceAvdPayment($ses_payment_plan->appl_id);
	
	        $this->view->advancePaymentList = $advancePaymentList;
	
	        $advance_payment_balance = 0;
	        if($advancePaymentList){
	          foreach ($advancePaymentList as $avd_payment){
	            $advance_payment_balance += $avd_payment['advpy_total_balance'];
	          }
	        }
	
	        $this->view->advance_payment_balance = $advance_payment_balance;
	
	        if( isset($ses_payment_plan->invoice_knockoff) ){
	          $this->view->invoice_knockoff = $ses_payment_plan->invoice_knockoff;
	        }
	
	
	      }else{
	        $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>2, 'msg'=>'Please Complete step 2 process'),'default',true));
	      }
	      	
	    }
	
	    }else
	      if($step==4){
	
	      if ($this->getRequest()->isPost()) {
	        $formData = $this->getRequest()->getPost();
	        	
	        $fi_loop_index = 0;
	        $installment = array();
	        	
	        for($i=0; $i<sizeof($formData['installment']); $i++){
	          $feeItem = array();
	          	
	          //fee item loop based in size
	          for($fi_cnt = 1; $fi_cnt<=$formData['fee_item_size']; $fi_cnt++){
	            //echo $formData['feeItemId']['fi_loop_index'];
	            $feeItem[] = array(
	                'fi_id' => $formData['feeItemId'][$fi_loop_index],
	                'percentage' => $formData['feeItemPercent'][$fi_loop_index],
	                'fix_amount' => $formData['feeItemAmount'][$fi_loop_index],
	                'value' => $formData['feeItemValue'][$fi_loop_index],
	                'tot_value' => $formData['totVal'][$fi_loop_index]
	            );
	            	
	            $fi_loop_index++;
	          }
	
	          $installment[] = $feeItem;
	        }
	        	
	
	        $ses_payment_plan->distribution = $installment;
	
	        //redirect
	        $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>5),'default',true));
	      }else{
	        	
	        if( isset($ses_payment_plan->installment) && isset($ses_payment_plan->invoice) ){
	
	          $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
	          $invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
	
	          $invoices = array();
	
	          //get fee item setup
	          $feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
	          $feeItemList = $feeItemDb->getData();
	
	          //loop each selected invoice id and get detail data
	          foreach ($ses_payment_plan->invoice as $index=>$invoice_id){
	            	
	            //get selected invoice data
	            $invoices[$index] = $invoiceMainDb->getData($invoice_id);
	            	
	            //get invoice detail
	            $invoices[$index]['invoice_detail'] = $invoiceDetailDb->getInvoiceDetail($invoice_id);
	          }
	
	          //initial set fee item to paid according to invoice paid amount
	          foreach ($invoices as $index => $invoice){
	            $invoice_paid_amount = $invoice['bill_paid'];
	            	
	            foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
	              if( $invoice_paid_amount > $invoice_detail['amount'] ){
	                $invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
	                $invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
	                $invoice_paid_amount = $invoice_paid_amount - $invoice_detail['amount'];
	              }else{
	                $invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_paid_amount;
	                $invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $invoice_paid_amount;
	                $invoice_paid_amount = $invoice_paid_amount - $invoice_paid_amount;
	              }
	            }
	          }
	
	          //loop each invoice & reduce fee item amount with advance payment option
	          if( isset($ses_payment_plan->invoice_knockoff) && $ses_payment_plan->invoice_knockoff!=null ){
	            foreach ($invoices as $index => $invoice){
	              foreach ($ses_payment_plan->invoice_knockoff as $invoice_knockoff){
	                	
	                if($invoice['bill_number'] == $invoice_knockoff['invoice_no']){
	
	                  //calculate paid balance
	                  $invoices[$index]['bill_paid'] += $invoice_knockoff['adv_amt'];
	                  $invoices[$index]['bill_balance'] -= $invoice_knockoff['adv_amt'];
	
	                  //calculate fee item amount balance
	                  $adv_pymt_paid_amount = $invoice_knockoff['adv_amt'];
	                  foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
	                    if( $adv_pymt_paid_amount > $invoice_detail['amount'] ){
	                      $invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
	                      $invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
	                      $adv_pymt_paid_amount = $adv_pymt_paid_amount - $invoice_detail['amount'];
	                    }else{
	                      $invoices[$index]['invoice_detail'][$fee_item_index]['paid'] = $adv_pymt_paid_amount;
	                      $invoices[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $adv_pymt_paid_amount;
	                      $adv_pymt_paid_amount = $adv_pymt_paid_amount - $adv_pymt_paid_amount;
	                    }
	                  }
	                }
	              }
	            }
	          }
	
	          //loop each invoice & calculate and inject array with fee item amount
	          foreach ($invoices as $index => $invoice){
	            foreach ($invoice['invoice_detail'] as $inv_dtl){
	              foreach($feeItemList as $feeItemIndex => $feeItem){
	                if($feeItem['fi_id']==$inv_dtl['fi_id']){
	                  if(isset($feeItemList[$feeItemIndex]['total_amount'])){
	                    $feeItemList[$feeItemIndex]['total_amount'] += $inv_dtl['balance'];
	                  }else{
	                    $feeItemList[$feeItemIndex]['total_amount'] = $inv_dtl['balance'];
	                  }
	
	                  break;
	                }
	              }
	            }
	          }
	
	          if( isset($ses_payment_plan->distribution) ){
	            $this->view->distribution = $ses_payment_plan->distribution;
	          }
	
	
	
	          $this->view->invoices = $invoices;
	          $this->view->fee_item = $feeItemList;
	          $this->view->installment = $ses_payment_plan->installment;
	
	        }else{
	          $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>2, 'msg'=>'Please Complete step 2 process'),'default',true));
	        }
	        	
	      }
	
	    }else
	      if($step==5){
	
	      $this->view->appl_id = $ses_payment_plan->appl_id;
	
	      if(isset($ses_payment_plan->invoice) && isset($ses_payment_plan->distribution)){
	        	
	        //invoice
	        $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
	        $invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
	        	
	        $invoiceList = array();
	        foreach ($ses_payment_plan->invoice as $index=>$invoice_id){
	          $invoiceList[] = $invoiceMainDb->getData($invoice_id);
	        }
	        	
	        //invoice detail
	        foreach ($invoiceList as $index => $invoice){
	          $invoiceList[$index]['invoice_detail'] = $invoiceDetailDb->getInvoiceDetail($invoice['id']);
	        }
	        	
	        	
	        //get advance payment
	        $advPmntDb = new Studentfinance_Model_DbTable_AdvancePayment();
	        $advancePaymentList = $advPmntDb->getApplicantBalanceAvdPayment($ses_payment_plan->appl_id);
	        	
	        $this->view->advancePaymentList = $advancePaymentList;
	        	
	        $advance_payment_balance = 0;
	        if($advancePaymentList){
	          foreach ($advancePaymentList as $avd_payment){
	            $advance_payment_balance += $avd_payment['advpy_total_balance'];
	          }
	        }
	        $this->view->advance_payment_balance = $advance_payment_balance;
	        	
	        if( isset($ses_payment_plan->invoice_knockoff) ){
	
	          $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
	
	          $invoice_knockoff_list = array();
	          foreach ($ses_payment_plan->invoice_knockoff as $index =>$invoice_knockoff){
	            $invoice_knockoff_list[$index] = $invoice_knockoff;
	            $invoice_knockoff_list[$index]['invoice_detail'] = $invoiceMainDb->getInvoiceData($invoice_knockoff['invoice_no'],true);
	          }
	
	          $this->view->invoice_knockoff = $invoice_knockoff_list;
	        }
	        	
	        //fee item distribution
	        //get fee item setup
	        $feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
	        $feeItemList = $feeItemDb->getData();
	        	
	        //initial set fee item to paid according to invoice paid amount
	        foreach ($invoiceList as $index => $invoice){
	          $invoice_paid_amount = $invoice['bill_paid'];
	
	          foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
	            if( $invoice_paid_amount > $invoice_detail['amount'] ){
	              $invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
	              $invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
	              $invoice_paid_amount = $invoice_paid_amount - $invoice_detail['amount'];
	            }else{
	              $invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_paid_amount;
	              $invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $invoice_paid_amount;
	              $invoice_paid_amount = $invoice_paid_amount - $invoice_paid_amount;
	            }
	          }
	        }
	        	
	        	
	
	        //invoice knockoff using advance payment
	        if( isset($ses_payment_plan->invoice_knockoff) ){
	          foreach ($invoiceList as $index => $invoice){
	            	
	            foreach ($ses_payment_plan->invoice_knockoff as $invoice_knockoff){
	
	              if($invoice['bill_number'] == $invoice_knockoff['invoice_no']){
	                	
	                //calculate fee item amount balance
	                $adv_pymt_paid_amount = $invoice_knockoff['adv_amt'];
	                foreach ($invoice['invoice_detail'] as $fee_item_index => $invoice_detail){
	                  if( $adv_pymt_paid_amount > $invoice_detail['amount'] ){
	                    $invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $invoice_detail['amount'];
	                    $invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = 0;
	                    $adv_pymt_paid_amount = $adv_pymt_paid_amount - $invoice_detail['amount'];
	                  }else{
	                    $invoiceList[$index]['invoice_detail'][$fee_item_index]['paid'] = $adv_pymt_paid_amount;
	                    $invoiceList[$index]['invoice_detail'][$fee_item_index]['balance'] = $invoice_detail['amount'] - $adv_pymt_paid_amount;
	                    $adv_pymt_paid_amount = $adv_pymt_paid_amount - $adv_pymt_paid_amount;
	                  }
	                }
	              }
	            }
	          }
	        }
	        	
	        //loop each invoice & calculate and inject array with fee item amount
	        foreach ($invoiceList as $index => $invoice){
	          foreach ($invoice['invoice_detail'] as $inv_dtl){
	            foreach($feeItemList as $feeItemIndex => $feeItem){
	              if($feeItem['fi_id']==$inv_dtl['fi_id']){
	                if(isset($feeItemList[$feeItemIndex]['total_amount'])){
	                  $feeItemList[$feeItemIndex]['total_amount'] += $inv_dtl['balance'];
	                }else{
	                  $feeItemList[$feeItemIndex]['total_amount'] = $inv_dtl['balance'];
	                }
	                break;
	              }
	            }
	          }
	        }
	        	
	        $this->view->invoice_list = $invoiceList;
	        $this->view->fee_item = $feeItemList;
	
	        //fee item installment
	        $this->view->installment = $ses_payment_plan->installment;
	        	
	        //distribution
	        if( isset($ses_payment_plan->distribution) ){
	          $this->view->distribution = $ses_payment_plan->distribution;
	        }
	        	
	      }else{
	        //redirect
	        $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'invoice-student-payment-plan','step'=>4, 'msg'=>'Please Complete step 4 process'),'default',true));
	      }
	
	    }else
	      if($step=='confirm'){
	      if ($this->getRequest()->isPost()) {
	        	
	        $formData = $this->getRequest()->getPost();
	
	        //data from session
	        $appl_id = $ses_payment_plan->appl_id;
	        $idStudentRegistration = $ses_payment_plan->idStudentRegistration;
	        $invoice = $ses_payment_plan->invoice;
	        $installment = $ses_payment_plan->installment;
	        $invoice_knockoff = $ses_payment_plan->invoice_knockoff;
	        $fee_item_distribution = $ses_payment_plan->distribution;
	        $semester = $ses_payment_plan->semester;
	        
	        //registration info
	        $studentRegistrationDb = new Registration_Model_DbTable_Studentregistration();
	        $registration = $studentRegistrationDb->getData($idStudentRegistration);
	        
	        //intake info
	        $intakeDb = new App_Model_Record_DbTable_Intake();
	        $intake = $intakeDb->fetchRow('IdIntake = '.$registration['IdIntake'])->toArray();
	        
	        //get semester info
	        $semesterDb = new Records_Model_DbTable_SemesterMaster();
	        $semester_data = $semesterDb->fetchRow('IdSemesterMaster = '.$semester)->toArray();
	         
	        //academic year
	        $academicYearDb = new App_Model_Record_DbTable_AcademicYear();
	        $academicYear = $academicYearDb->fetchRow('ay_id = '.$semester_data['idacadyear'])->toArray();
	        
	        
	        //invoice detail
	        $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
	        $invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
	        	
	        foreach ($invoice as $index=>$item){
	          $invoice[$index] = $invoiceMainDb->getData($item);
	          $invoice[$index]['detail'] = $invoiceDetailDb->getInvoiceDetail($item);
	        }
	        	
	        //advance payment record
	        $advancePaymentDb = new Studentfinance_Model_DbTable_AdvancePayment();
	        $advance_payment_list = $advancePaymentDb->getApplicantBalanceAvdPayment($appl_id);
	        	
	        $advance_payment = array();
	        $advance_payment['record'] = $advance_payment_list;
	        $advance_payment['total_amount'] = 0;
	        	
	        if($advance_payment_list){
    	        foreach ($advance_payment_list as $adv_pymt){
    	          $advance_payment['total_amount'] += $adv_pymt['advpy_total_balance'];
    	        }
	        }
	        
	        	
	        $auth = Zend_Auth::getInstance();
	        	
	        $db = Zend_Db_Table::getDefaultAdapter();
	        $db->beginTransaction();
	        	
	        try {
	
	          /*
	           * save payment plan record
	          */
	
	          $amount = 0;//calculate amount
	          foreach ($fee_item_distribution as $installment_data){
	            foreach ($installment_data as $fee_item){
	              $amount += $fee_item['value'];
	            }
	          }
	
	          $paymentPlanDb = new Studentfinance_Model_DbTable_PaymentPlan();
	          $data = array(
	              'pp_appl_id' =>$appl_id,
	              'pp_installment_no' =>$installment,
	              'pp_amount' => $amount
	          );
	          $paymentplan_id = $paymentPlanDb->insert($data);
	
	
	
	          $paymentPlanInvoiceDb = new Studentfinance_Model_DbTable_PaymentPlanInvoice();
	          $auth = Zend_Auth::getInstance();
	
	          //loop invoice
	          foreach ($invoice as $inv){
	            	
	            /*
	             * save payment plan invoice (invoice that will be canceled)
	            */
	            $data = array(
	                'ppi_plan_id' => $paymentplan_id,
	                'ppi_invoice_id' => $inv['id'],
	                'ppi_invoice_no' => $inv['bill_number']
	            );
	            $paymentPlanInvoiceDb->insert($data);
	            	
	            	
	            /*
	             * cancel selected invoice for payment plan
	            */
	
	            $data=array(
	                'status'=>'X',
	                'cancel_by' => $auth->getIdentity()->iduser,
	                'cancel_date' => date('Y-m-d H:i:s')
	            );
	            $this->_DbObj->update($data, 'id = '.$inv['id']);
	          }
	
	
	
	          /*
	           * generate new invoice from payment plan
	          */
	          
	          //loop installment distribution to inject fee_item definition
	          $feeItemDb = new Studentfinance_Model_DbTable_FeeItem();
	          foreach ($fee_item_distribution as $index=>$dist){
	            foreach ($dist as $key=>$fee_item){
	              //fee item description
	              $fee_item_distribution[$index][$key]['fee_description'] = $feeItemDb->getData($fee_item['fi_id']);
	            }
	          }
	
	          //get program info
	          $programDb = new App_Model_Record_DbTable_Program();
	          $program = $programDb->getData($registration['IdProgram']);
	
	          
	          //loop installment distribution
	          foreach ($fee_item_distribution as $index=>$dist){
	            	
	            //calculate total amount
	            $total_amount = 0;
	            foreach ($dist as $key=>$fee_item){
	              $total_amount += $fee_item['value'];
	            }
	            
	            //generate billing number
	            $seq_data = array(
	                date('y',strtotime($academicYear['ay_start_date'])),
	                substr($intake['IntakeId'],2,2),
	                $program['ProgramCode'], 0
	            );
	            
	            $stmt = $db->prepare("SELECT invoice_seq(?,?,?,?) AS invoice_no");
	            $stmt->execute($seq_data);
	            $invoice_no = $stmt->fetch();
	            
	            //insert main bill
	            $data = array(
	                'bill_number' => $invoice_no['invoice_no'],
	                'appl_id' => $registration['IdApplication'],
	                'IdStudentRegistration' => $registration['IdStudentRegistration'],
	                'academic_year' => $academicYear['ay_id'],
	                'semester' => $semester_data['IdSemesterMaster'],
	                'bill_amount' => $total_amount,
	                'bill_paid' => 0,
	                'bill_balance' => $total_amount,
	                'bill_description' => $program['ShortName']."-PPS"." Cicilan ".($index+1),
	                'college_id' => $program['IdCollege'],
	                'program_code' => $program['ProgramCode'],
	                'creator' => '1',
	                'fs_id' => 0,
	                'fsp_id' => 0,
	                'status' => 'A',
	                'date_create' => date('Y-m-d H:i:s')
	            );
	
	            $main_id = $this->_DbObj->insert($data);
	
	            	
	            //insert bill detail
	            $invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
	            foreach ($dist as $fee_item){
	
	              $data_detail = array(
	                  'invoice_main_id' => $main_id,
	                  'fi_id' => $fee_item['fi_id'],
	                  'fee_item_description' => $fee_item['fee_description']['fi_name_bahasa'],
	                  'amount' => isset($fee_item['value']) && $fee_item['value']!=""?$fee_item['value']:0.00
	              );
	
	              $invoiceDetailDb->insert($data_detail);
	            }
	            	
	            /*
	             * save payment plan detail
	            */
	            $paymentPlanDetail = new Studentfinance_Model_DbTable_PaymentPlanDetail();
	            $data = array(
	                'ppd_payment_plan_id' => $paymentplan_id,
	                'ppd_invoice_id' => $main_id,
	                'ppd_installment_no' => ($index+1)
	            );
	            	
	            $paymentPlanDetail->insert($data);
	
	
	          }
	
	
	          $db->commit();
	
	          //redirect
	          $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice', 'action'=>'student','id'=>$registration['IdStudentRegistration']),'default',true));
	
	        }catch (Exception $e) {
	          echo "Error in Invoice Student Payment Plan. <br />";
	          echo $e->getMessage();
	
	          echo "<pre>";
	          print_r($e->getTrace());
	          echo "</pre>";
	
	          $db->rollBack();
	           
	          exit;
	           
	        }
	        	
	        	
	      }
	
	      exit;
	    }else{
	      //redirect
	      $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice'),'default',true));
	    }
	  }else{
	    //redirect
	    $this->_redirect($this->view->url(array('module'=>'studentfinance','controller'=>'invoice'),'default',true));
	  }
	}
}