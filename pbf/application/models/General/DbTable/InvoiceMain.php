<?php
class App_Model_General_DbTable_InvoiceMain extends Zend_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'invoice_main';
	protected $_primary = "id";

    protected $_dependentTables = array('Studentfinance_Model_DbTable_InvoiceDetail');

    public function save(array $data)
    {

        if (isset($data['id'])) {
            $where = $this->getAdapter()->quoteInto('id = ?', $data['id']);
            $this->update($data, $where);
            $id = $data['id'];
        } else {
            $id = $this->insert($data);
        }
        return $id;

    }

	public function getData($id, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->where("im.id = ?", (int)$id);

		if($active){
			$selectData->where("im.status = 'A'");
		}
		
		$row = $db->fetchRow($selectData);				
		return $row;
	}
	
	public function getInvoiceData($billing_no, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->where('im.bill_number = ?', $billing_no);
					
		if($active){
			$selectData->where("im.status = 'A'");
		}

		$row = $db->fetchRow($selectData);

		if(!$row){
			return null;
		}else{
			return $row;	
		}
		
	}
	
	public function getAllInvoiceData($billing_no, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->where('im.bill_number = ?', $billing_no);
					
		if($active){
			$selectData->where("im.status = 'A'");
		}

		$row = $db->fetchAll($selectData);

		if(!$row){
			return null;
		}else{
			return $row;	
		}
		
	}
	
	public function getApplicantInvoiceData($appl_id, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->where('im.appl_id = ?', $appl_id)
		            ->where('im.IdStudentRegistration is null');
					
		if($active){
			$selectData->where("im.status = 'A'");
		}

		$row = $db->fetchAll($selectData);

		if(!$row){
			return null;
		}else{
			return $row;	
		}
		
	}
	
	public function getStudentInvoiceData($appl_id, $idStudentRegistration=null, $semester_id=null, $academic_year_id=null, $active=false){
	  $db = Zend_Db_Table::getDefaultAdapter();
	  $selectData = $db->select()
	  ->from(array('im'=>$this->_name))
	  ->where('im.appl_id = ?', $appl_id);
	  
	  if($idStudentRegistration){
	    $selectData->where('im.IdStudentRegistration = ?', $idStudentRegistration);
	  }else{
	    $selectData->where('im.IdStudentRegistration is not null');
	  }
	  	
	  if($active){
	    $selectData->where("im.status = 'A'");
	  }
	  
	  if($semester_id){
	    $selectData->where("im.semester = ?", $semester_id);
	  }
	  
	  if($academic_year_id){
	    $selectData->where("im.academic_year = ?", $academic_year_id);
	  }
	
	  $row = $db->fetchAll($selectData);
	
	  if(!$row){
	    return null;
	  }else{
	    return $row;
	  }
	
	}
	
	public function getApplicantInvoiceBalanceAmount($appl_id, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
		->from(array('im'=>$this->_name))
		->where('im.appl_id = ?', $appl_id);
			
		if($active){
			$selectData->where("im.status = 'A'");
		}
	
		$rows = $db->fetchAll($selectData);
	
		if(!$rows){
			return null;
		}else{
			$balance = 0.00;
			foreach ($rows as $row){
				$balance += $row['bill_balance'];
			}
			
			return $balance;
		}
	
	}
	
	public function getFomulirInvoiceData($fomulir, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->where('im.no_fomulir = ?', $fomulir);
					
		if($active){
			$selectData->where("im.status = 'A'");
		}

		$row = $db->fetchAll($selectData);

		if(!$row){
			return null;
		}else{
			return $row;	
		}
		
	}
	
	public function getInvoiceFromApplId($appl_id, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
		->from(array('im'=>$this->_name))
		->where('im.appl_id = ?', $appl_id);
			
		if($active){
			$selectData->where("im.status = 'A'");
		}
	
		$row = $db->fetchAll($selectData);
	
		if(!$row){
			return null;
		}else{
			return $row;
		}
	
	}
	
	public function getIssuedInvoiceData($payee, $program_code=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->where("im.no_fomulir = '".$payee."'");
					
		if($program_code!=null){
			$selectData->where('im.program_code =?',$program_code);
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
	 * Return boolean if found any proforma invoice not in invoice main table
	 * @param String $billing_no
	 */
	public function getProformaInvoiceNotInInvoice($billing_no,$payer_id=null){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//select invoice
		/*$select_invoice = $db->select()
					->from(array('im'=>$this->_name), array('im.bill_number'))
					->where("im.bill_number = ?", $billing_no);
					
		//select proforma invoice
		$select_proforma = $db->select()
					->from(array('api'=>'applicant_proforma_invoice'))
					->where("api.billing_no = '".$billing_no."'")
					->where("api.billing_no not in (".$select_invoice.")");
	
		if($payer_id!=null){
			$select_proforma->where('api.payee_id = ?',$payer_id);
		}*/
		
		$select = $db->select()
					->from(array('api'=>'applicant_proforma_invoice'))
					->joinLeft(array('im'=>$this->_name), 'api.billing_no = im.bill_number')
					->where("im.bill_number is null")
					->where('api.billing_no = ?', $billing_no);
		
		//echo $select;
		//exit;
		$row = $db->fetchRow($select);
		
		if(!$row){
			return false;
		}else{
			return true;	
		}
		
	}

	/*
	 * Overite Insert function
	 */
	
	public function insert(array $data){
		
		$auth = Zend_Auth::getInstance();
		
		if(!isset($data['creator'])){
			$data['creator'] = $auth->getIdentity()->iduser;
		}
		
		if( !isset($data['date_create'])  ){
			$data['date_create'] = date('Y-m-d H:i:s');
		}
				
		return parent::insert( $data );
	}
	
	public function generateApplicantInvoice($payer_id, $billing){
				
		
		try {
			
			//get proforma invoice
			$db = Zend_Db_Table::getDefaultAdapter();
			$select = $db->select()
					->from(array('api'=>'applicant_proforma_invoice'))
					->where('api.payee_id = ?', $payer_id)
					->where('api.billing_no = ?', $billing);
			
			$row = $db->fetchAll($select);
			
			if($row!=null){
				//loop each invoice
				for($i=0; $i<sizeof($row); $i++){
					
					$proforma_invoice = $row[$i];
					
					//check not in invoice table or with status cancel 
					$select = $db->select()
						->from(array('api'=>'applicant_proforma_invoice'))
						->joinLeft(array('im'=>$this->_name), 'api.billing_no = im.bill_number')
						->where('api.billing_no = ?', $proforma_invoice['billing_no'])
						->where("im.bill_number is null or (im.bill_number is not null and im.status = 'X')");
						
					
					$row_invoice = $db->fetchRow($select);
					
					if($row_invoice){
						
						$date_invoice = null;
						if( isset($row_invoice['offer_date']) && $row_invoice['offer_date'] != '0000-00-00' ){
							$date_invoice = date('Y-m-d H:i:s', strtotime($row_invoice['offer_date']));
						}
						
						//get transaction data
						$transaction = $this->getTransaction($payer_id);
						
						//get selection rank
						$selection_rank = $this->getSelectionRank($transaction['at_trans_id'], $transaction['at_appl_type']);
						
						//get program data (code & college)
						$program = $this->getProgram($payer_id);
						
						//get fee structure
						$feeStructureDb = new Studentfinance_Model_DbTable_FeeStructure();
						if(!$this->islocalNationality($transaction['at_trans_id'])){
							//315 is foreigner in lookup db
							$fee_structure = $feeStructureDb->getApplicantFeeStructure($transaction['at_intake'],$program['IdProgram'],null,315);
						} else {
							//default to local
							$fee_structure = $feeStructureDb->getApplicantFeeStructure($transaction['at_intake'],$program['IdProgram']);
						}
						
						
						//get selected payment plan
						$paymentplanDb = new Studentfinance_Model_DbTable_FeeStructurePlan();
						$payment_plan = $paymentplanDb->getBillingPlan($fee_structure['fs_id'],$billing);
						
						//inject plan detail (installment)
						$paymentPlanDetailDb = new Studentfinance_Model_DbTable_FeeStructurePlanDetail();
						$payment_plan['installment_detail'] = array();
						for($i=1;$i<=$payment_plan['fsp_bil_installment']; $i++){
							$payment_plan['installment_detail'][$i] = $paymentPlanDetailDb->getPlanData($fee_structure['fs_id'], $payment_plan['fsp_id'], $i, 1, $program['IdProgram'], $selection_rank );
							
							
						}
												
						//loop each cicilan
						for($i=1;$i<=$payment_plan['fsp_bil_installment']; $i++){
							set_time_limit(0);
							
							//total amount for each cicilan
							$total_invoice_amount = 0;
							foreach ($payment_plan['installment_detail'][$i] as $cicilan){
								$total_invoice_amount = $total_invoice_amount + $cicilan['total_amount'];
							}
							
							//desc cicilan
							$paket_info = "";
							if($payment_plan['fsp_bil_installment']==1){
								$paket_info = "Lunas";
							}else
							if($payment_plan['fsp_bil_installment']>1){
								$paket_info = "Cicilan ".$i;
							}
							
							//check for payment to calculate paid and balance
							$paymentMainDb = new Studentfinance_Model_DbTable_PaymentMain();
							$payment_record =  $paymentMainDb->getInvoicePaymentRecord($payment_plan['fsp_billing_no'].$i.$payer_id,$payer_id);

							//excess payment
							if( $payment_record!=null && $payment_record['amount'] > $total_invoice_amount ){
								
								$amount_paid = $total_invoice_amount;
								
								//insert main bill
								$data = array(
											'bill_number' => $payment_plan['fsp_billing_no'].$i.$payer_id,
											'appl_id' => $transaction['at_appl_id'],
											'no_fomulir' => $payer_id,
											'academic_year' => $transaction['at_academic_year'],
											//'semester' => '',
											'bill_amount' => $total_invoice_amount,
											'bill_paid' => $amount_paid,
											'bill_balance' => $total_invoice_amount - $amount_paid,
											'bill_description' => $program['ShortName']."-"."P".$selection_rank."-".$payment_plan['fsp_name']." ".$paket_info,
											'college_id' => $program['IdCollege'],
											'program_code' => $program['ProgramCode'],
											'creator' => '1',
											'fs_id' => $payment_plan['fsp_structure_id'],
											'fsp_id' => $payment_plan['fsp_id'],
											'status' => 'A',
											'date_create' => $date_invoice
										);
								
								$main_id = $this->insert($data);
								
								//insert bill detail
								$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
								foreach ($payment_plan['installment_detail'][$i] as $detail){
									
									$data_detail = array(
													'invoice_main_id' => $main_id,
													'fi_id' => $detail['fi_id'],
													'fee_item_description' => $detail['fi_name_bahasa'],
													'amount' => $detail['amount']
												);
									
									
									$invoiceDetailDb->insert($data_detail);
								}
								
								//advance payment
								$advancePaymentDb = new Studentfinance_Model_DbTable_AdvancePayment();
					       		$adv_amount = $payment_record['amount'] - $total_invoice_amount;
					       		$data = array(
					       			'advpy_appl_id' => $transaction['at_appl_id'],
					       			'advpy_acad_year_id' => $transaction['at_academic_year'],
					       			'advpy_sem_id' => '',
					       			'advpy_prog_code' => $program['ProgramCode'],
					       			'advpy_fomulir' => $payer_id,
					       			'advpy_invoice_no' => $payment_plan['fsp_billing_no'].$i.$payer_id,
					       			'advpy_invoice_id' => $main_id,
					       			'advpy_payment_id' => $payment_record['id'],
					       			'advpy_description' => 'Excess Payment for invoice no:'.$payment_plan['fsp_billing_no'].$i.$payer_id,
					       			'advpy_amount' => $adv_amount,
					       			'advpy_total_paid' => 0,
					       			'advpy_total_balance' => $adv_amount,
					       			'advpy_status' => 'A'
					       		);
					       		$advancePaymentDb->insert($data);
					       		
							}else{
								
								$amount_paid = $payment_record['amount'];
								
								//insert main bill
								$data = array(
											'bill_number' => $payment_plan['fsp_billing_no'].$i.$payer_id,
											'appl_id' => $transaction['at_appl_id'],
											'no_fomulir' => $payer_id,
											'academic_year' => $transaction['at_academic_year'],
											//'semester' => '',
											'bill_amount' => $total_invoice_amount,
											'bill_paid' => $amount_paid,
											'bill_balance' => $total_invoice_amount - $amount_paid,
											'bill_description' => $program['ShortName']."-"."P".$selection_rank."-".$payment_plan['fsp_name']." ".$paket_info,
											'college_id' => $program['IdCollege'],
											'program_code' => $program['ProgramCode'],
											'creator' => '1',
											'fs_id' => $payment_plan['fsp_structure_id'],
											'fsp_id' => $payment_plan['fsp_id'],
											'status' => 'A',
											'date_create' => $date_invoice
										);
								
								$main_id = $this->insert($data);
								
								//insert bill detail
								$invoiceDetailDb = new Studentfinance_Model_DbTable_InvoiceDetail();
								foreach ($payment_plan['installment_detail'][$i] as $detail){
									
									$data_detail = array(
													'invoice_main_id' => $main_id,
													'fi_id' => $detail['fi_id'],
													'fee_item_description' => $detail['fi_name_bahasa'],
													'amount' => $detail['amount']
												);
									
									
									$invoiceDetailDb->insert($data_detail);
								}
							}
						}
						
						
						
					}
				}
			}
			
		}catch (Exception $e) {
			
			echo $e->getMessage();
			
			echo "<pre>";
			print_r($e->getTrace());
			echo "</pre>";
			
			throw new Exception('Error in Invoice Main');
		}
	}
	
	public function getMaxPaketNo($fomulir_no){
		$db = Zend_Db_Table::getDefaultAdapter();
		$selectData = $db->select()
					->from(array('im'=>$this->_name),array( 'paket' => new Zend_Db_Expr("MAX(SUBSTRING(bill_number,1,1))")))
					->where('im.no_fomulir = ?', $fomulir_no);

		$row = $db->fetchRow($selectData);

		if(!$row){
			return 1;
		}else{
			return $row[paket]+1;	
		}
		
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
	
	private function getProgram($payer_id){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//get application type
		$selectData = $db->select()
					->from(array('at'=>'applicant_transaction'))
					->where("at.at_pes_id = ?", $payer_id);
							
		$txn_row = $db->fetchRow($selectData);			

		if($txn_row){
			
			$selectData = $db->select()
					->from(array('at'=>'applicant_transaction'),array())
					->join(array('ap'=>'applicant_program'), 'ap.ap_at_trans_id = at.at_trans_id', array())
					->join(array('p'=>'tbl_program'),'p.ProgramCode = ap.ap_prog_code')
					->where("at.at_pes_id = ?", $payer_id);
							
			if($txn_row['at_appl_type']==1){
				$selectData->where("ap.ap_usm_status = 1");			
			}
			
			$row = $db->fetchRow($selectData);

			if(!$row){
				return null;
			}else{
				return $row;
			}
		}else{
			return null;
		}	
	}
	
	private function getSelectionRank($txn_id, $application_type){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		if($application_type == 1){
			$selectData = $db->select()
					->from(array('aau'=>'applicant_assessment_usm'), array('rank'=>'aau.aau_rector_ranking'))
					->where("aau.aau_trans_id = ?", $txn_id)
					->order('aau.aau_id desc');
		}else
		if($application_type == 2){
			$selectData = $db->select()
					->from(array('aa'=>'applicant_assessment'), array('rank'=>'aa.aar_rating_rector'))
					->where("aa.aar_trans_id = ?", $txn_id)
					->order('aa.aar_id desc');
		}
		
		$row = $db->fetchRow($selectData);			

		if(!$row){
			return 3;
		}else{
			return $row['rank'];
		}
	}
	
	private function islocalNationality($txn_id){
		//get profile
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db ->select()
		->from(array('at'=>'applicant_transaction'))
		->join(array('ap'=>'applicant_profile'),'ap.appl_id = at.at_appl_id')
		->where("at_trans_id = ".$txn_id);
	
		$row = $db->fetchRow($select);
	
		//nationality
		if( isset($row['appl_nationality']) ){
				
			if($row['appl_nationality']==96){
				return true;
			}else{
				return false;
			}
		}else{
			//default to local if null data
			return true;
		}
	}
	
	public function getTotalPaidInvoiceAmount($payer){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db ->select()
		->from(array('im'=>'invoice_main'))
		->join(array('pi'=>'applicant_proforma_invoice'),'im.bill_number = pi.billing_no')
		->where("pi.payee_id ='".$payer."'");
		 
		$row = $db->fetchAll($select);
	
		if(!$row){
			return 0;
		}else{
			$total = 0;
			foreach ($row as $bil){
				$total = $total + $bil['bill_paid'];
			}
				
			return $total;
		}
	
	}
	
	public function getApplicantInvoice($payer){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db ->select()
		->from(array('im'=>'invoice_main'))
		->join(array('pi'=>'applicant_proforma_invoice'),'im.bill_number = pi.billing_no')
		->where("pi.payee_id ='".$payer."'");
			
		$row = $db->fetchAll($select);
	
		if(!$row){
			return null;
		}else{
			return $row;
		}
	
	}
	
	public function getInvoicedProformaData($fomulir, $active=false){
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$selectData = $db->select()
					->from(array('im'=>$this->_name))
					->join(array('pi'=>'applicant_proforma_invoice'),'pi.billing_no = im.bill_number', array())
					->where('im.no_fomulir = ?', $fomulir);
			
		if($active){
			$selectData->where("im.status = 'A'");
		}
	
		$row = $db->fetchAll($selectData);
	
		if(!$row){
			return null;
		}else{
			return $row;
		}
	
	}

    public function getStudentInvoiceForSemester($IdStudentRegistration, $IdSemester) {
            $select = $this->select()
                ->where('IdStudentRegistration = ?', $IdStudentRegistration)
                ->where('semester = ?', $IdSemester)
            ;

            $invoice = $this->fetchAll($select);
            return($invoice);
    }

    public function hasPBBItem($IdStudentRegistration, $IdSemester) {
        $has_pbb_fee_item = false;
        $invoices = $this->getStudentInvoiceForSemester($IdStudentRegistration, $IdSemester);
        foreach($invoices as $invoice) {
            //get invoice details
            $invoice_details = $invoice->findDependentRowset('Studentfinance_Model_DbTable_InvoiceDetail');
            foreach($invoice_details as $invoice_details) {
                if($invoice_details['fi_id'] == 2){
                    if($invoice->bill_paid > 0) {
                        $has_pbb_fee_item = $invoice->id;
                        break;
                    }
                }
            }
        }

        return($has_pbb_fee_item);
    }

    public function cancel_invoice($id) {
        $invoices = $this->find('where id = ' . $id);
        if(empty($invoices)) {
            return false;
        }

        $invoice = $invoices->current();
        $invoicedata['id'] = $invoice->id;
        $invoicedata['status'] = 'X';

        $this->save($invoicedata);
        return(true);

    }


    //generete bill number for invoice
    public function generateBillNumber() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $stmt = $db->query("SELECT seq('bill_seq')bill");
        $seq_bill = $stmt->fetch();

        return($seq_bill['bill']);

    }

}