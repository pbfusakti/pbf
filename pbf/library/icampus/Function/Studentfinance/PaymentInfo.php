<?php 
class icampus_Function_Studentfinance_PaymentInfo{
 
  /*
   * Get payment status and invoice detail
   */
  public function getStudentPaymentInfo($idRegistration,$semesterMainId=null, $academicYearId=null){
    
    //get student profile info
    $studentProfileDb = new Records_Model_DbTable_Studentprofile();
    $profile = $studentProfileDb->fnGetStudentProfileByIdStudentRegistration($idRegistration);
    
    
    //get invoice 
    $invoiceMainDb = new Studentfinance_Model_DbTable_InvoiceMain();
    
    
    $condition = array('appl_id =?' => $profile['appl_id']);
    
    //specific semester
    if($semesterMainId){
      $condition['semester = ?'] = $semesterMainId;
    }
    
    //specific academic year
    if($academicYearId){
      $condition['academic_year = ?'] = $academicYearId;
    }
    
    
    $invoices = $invoiceMainDb->fetchAll($condition)->toArray();
    
    
    //pack invoice into array
    $result['idStudentRegistration'] = $idRegistration;
    $result['nim'] = $profile['registrationId'];
    
    $result['fullname'] = "";
    $profile['appl_fname']!=""?$result['fullname'] .= $profile['appl_fname']." ":"";
    $profile['appl_mname']!=""?$result['fullname'] .= $profile['appl_mname']." ":"";
    $profile['appl_lname']!=""?$result['fullname'] .= $profile['appl_lname']." ":"";
    
    if($semesterMainId){
      $result['semester_id'] = $semesterMainId;
    }
    if($academicYearId){
      $result['academic_year_id'] = $academicYearId;
    }
    
    $result['total_invoice_amount'] = 0;
    $result['total_invoice_paid'] = 0;
    $result['total_invoice_balance'] = 0;
    
    if($invoices){
      $result['invoices'] = $invoices;
    }else{
      $result['invoices'] = null;
    }
    
    
    
    foreach ($invoices as $invoice){
      $result['total_invoice_amount'] += $invoice['bill_amount'];
      $result['total_invoice_paid'] += $invoice['bill_paid'];
      $result['total_invoice_balance'] += $invoice['bill_balance'];
    }
    
    //get exception
    if($semesterMainId){
    
      $db = Zend_Db_Table::getDefaultAdapter();
      $selectData = $db->select()
      ->from(array('pe'=>'payment_exception'))
      ->where("pe.idsemester = ?", (int)$semesterMainId)
      ->where("pe.idreg = ?", $profile['registrationId']);
    
      $row_ex = $db->fetchAll($selectData);
      $result['exception'] = array();
      foreach ($row_ex as $exp){
        $result['exception'][$exp['extype']] = true;
      }
    }
    
    return $result;
    
  } 
}
?>