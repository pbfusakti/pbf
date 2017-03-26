<?php
class App_Model_General_DbTable_Landscape extends Zend_Db_Table_Abstract
{
	protected $_name = 'tbl_landscape';
	private $lobjDbAdpt;

	public function init()
	{
		$this->lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	}

	public function fnSearchProgram($post = array()) { //Function for searching the university details
		$field7 = "Active = ".$post["field7"];
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_program'),array('IdProgram'))
		->where('a.ProgramName  like "%" ? "%"',$post['field3'])
		->where('a.ArabicName   like "%" ? "%"',$post['field4'])
		->where($field7);
		$select  ->order("a.ProgramName");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fninsertToLandScapeBlocksubjectLevel($formData,$idlandscpae) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksubject";
		$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				'blockid'=>$formData['blockid'],
				'subjectid'=>$formData['subjectid'],
				'coursetypeid'=>$formData['cooursetypeid']);
		$db->insert($table,$larrtepprogrmentryreq);

	}


	public function deleteLandscapeBlocksubjectLevel($landscape){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksubject";
		$where = $db->quoteInto('IdLandscape = ?',$landscape);
		$db->delete($table, $where);

	}

	public function fnGetTempLandscapeSubjectLevel($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_templandscapeblocksubject'),array('a.IdLandscapetempblocksubject'))
		->where("a.IdLandscape = '$Idlandscape'")
		->where("a.deleteFlag = '1'")
		->where("a.session_id = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnaddLandscape($formData) { //Function for adding the University details to the table

		unset($formData['Save']);
		unset($formData['Back']);
		unset($formData['block']);
		unset($formData['semesterid']);
		unset($formData['blocknamegrid']);
		unset($formData['semesteridnamegrid']);
		unset($formData['Semester']);
		$this->insert($formData);
		$lobjdb = Zend_Db_Table::getDefaultAdapter();
		return $lobjdb->lastInsertId();
	}


	public function fneditLandscape($idlandscape) { //Function for the view University
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
		->join(array('prg'=>'tbl_programrequirement'),'lan.IdLandscape = prg.IdLandscape')
		->join(array('def'=>'tbl_definationms'),'prg.SubjectType = def.idDefinition')
		->where('lan.IdLandscape = ?',$idlandscape);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fneditLandblock($idlandscape) { //Function for the view University
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
		->join(array('blc'=>'tbl_landscapeblock'),'lan.IdLandscape = blc.idlandscape')
		->join(array('sem'=>'tbl_semester'),'blc.semesterid = sem.IdSemester')
		->join(array('sma'=>'tbl_semestermaster'),'sem.IdSemester = sma.IdSemesterMaster')
		->where('lan.IdLandscape = ?',$idlandscape);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnLandscapeList($IdProgram=null) { //Function for the view University
		$session = new Zend_Session_Namespace('sis');
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active','lan.ProgramDescription'))
		->join(array('prg'=>'tbl_program'),'lan.IdProgram = prg.IdProgram',array('prg.IdProgram'))
		->join(array('def'=>'tbl_definationms'),'lan.LandscapeType = def.idDefinition')
		->joinLeft(array('tink'=>'tbl_intake'),'tink.IdIntake = lan.IdStartSemester',array('tink.IntakeId'))
		->joinLeft(array('scm'=>'tbl_scheme'),'lan.Scheme = scm.IdScheme',array('scm.EnglishDescription as SchemeName'));
		
		if ($IdProgram==null) {
			 
			if($session->IdRole == 605 || $session->IdRole == 311 || $session->IdRole == 298 || $session->IdRole == 579){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("prg.IdCollege='".$session->idCollege."'");
			}
			if($session->IdRole == 470 || $session->IdRole == 480){ //FACULTY DEAN atau FACULTY ADMIN nampak faculty dia sahaja
				$select->where("prg.IdProgram='".$session->idDepartment."'");
			}
			 
		} else {$select->where('lan.IdProgram = ?',$IdProgram);}
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnLandscapeTypeList($IdProgram) { //Function for the view University
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active'))
		->join(array('def'=>'tbl_definationms'),'lan.LandscapeType = def.idDefinition')
		->join(array('sem'=>'tbl_semester'),'lan.IdStartSemester = sem.IdSemester')
		->join(array('sma'=>'tbl_semestermaster'),'sem.IdSemester = sma.IdSemesterMaster')
		->where('lan.IdProgram = ?',$IdProgram);
		$result = $this->fetchRow($select);
		return $result;
	}

	public function fnGetLandScapeBlockDtls($idlandscape) { //Function for the view University
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active'))
		->join(array('lb'=>'tbl_landscapeblock'),'lan.IdLandscape = lb.idlandscape')
		->where('lan.IdLandscape  = ?',$idlandscape);

		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnGetLandScapeBlockSubjectDtls($idlandscape) { //Function for the view University
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lbs'=>'tbl_landscapeblocksubject'),array('lbs.blockid','lbs.IdLandscape'))
		->join(array('lb'=>'tbl_landscapeblock'),'lbs.blockid = lb.block')
		->join(array('sm'=>'tbl_subjectmaster'),'lbs.subjectid = sm.IdSubject')
		->join(array('ct'=>'tbl_coursetype'),'ct.IdCourseType = lbs.coursetypeid',array('ct.CourseType','ct.IdCourseType'))
		->where('lbs.IdLandscape = ?',$idlandscape)
		->where('lb.idlandscape = ?',$idlandscape);
		//->group('lbs.subjectid');

		$result = $this->fetchAll($select);
		return $result->toArray();
	}


	public function fnGetLandScapeBlockSemesterDtls($idlandscape) { //Function for the view University
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lbs'=>'tbl_landscapeblocksemester'),'lb.*')
		->join(array('lb'=>'tbl_landscapeblock'),'lbs.blockid=lb.block')
		->where('lbs.IdLandscape = ?',$idlandscape)
		->where('lb.idlandscape = ?',$idlandscape);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnGetLandScapeBlockYearDtls($idlandscape){
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $db->select()
		->from(array('lby'=>'tbl_blocksemesteryear'),array('lby.*'))
		->where('lby.IdLandscape = ?',$idlandscape);
		$result = $db->fetchAll($select);
		return $result;
	}

	public function fnupdateLandscapeActive($formData,$lintIdProgram) { //Function for updating the university
		unset ( $formData ['Save'] );
		unset($formData['block']);
		unset($formData['semesterid']);
		unset($formData['blocknamegrid']);
		unset($formData['semesteridnamegrid']);
		$where = 'IdProgram = '.$lintIdProgram;
		$formData['Active'] = 0;
		$this->update($formData,$where);
	}

	public function fnGetProgramRequrimentEditDetails($lintIdLandscape) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_programrequirement'),array('a.*'))
		->where("a.IdLandscape = '$lintIdLandscape'");
		$result = $this->fetchAll($select);
		return $result;
	}

	public function fninserttempprogramentryrequriments($larrmainprogramresult,$idlandscape) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_tempprogramrequirement";
		$sessionID = Zend_Session::getId();

		foreach($larrmainprogramresult as $formData) {
			$larrtepprogrmentryreq = array('IdProgram'=>$formData['IdProgram'],
					'SubjectType'=>$formData['SubjectType'],
					'CreditHours'=>$formData['CreditHours'],
					'UpdDate'=>$formData['UpdDate'],
					'UpdUser'=>$formData['UpdUser'],
					'unicode'=>$idlandscape,
					'Date'=>date("Y-m-d"),
					'sessionId'=>$sessionID,
					'idExists'=>$formData['IdProgramReq'],
					'deleteFlag'=>'1'
			);

			$db->insert($table,$larrtepprogrmentryreq);
		}
	}



	public function fnGetTemproryProgReqDetails($idlandscape) { //Function for the view University
		$sessionID = Zend_Session::getId();
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
		//->joinLeft(array('prgsub'=>'tbl_templandscapesubject'),'lan.IdLandscape = prgsub.unicode AND lan.IdProgram = prgsub.IdProgram ',array('SUM(prgsub.CreditHours) as CreditHoursAdded'))
		->join(array('prg'=>'tbl_tempprogramrequirement'),'lan.IdLandscape = prg.unicode')
		->join(array('def'=>'tbl_definationms'),'prg.SubjectType = def.idDefinition')
		->where('lan.IdLandscape = ?',$idlandscape)
		->where('prg.deleteFlag = 1')
		->where('prg.sessionId = ?',$sessionID);
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnGetLandscapeEditDetails($lintIdLandscape) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_landscapesubject'),array('a.*'))
		->where("a.IdLandscape = '$lintIdLandscape'");
		$result = $this->fetchAll($select);
		return $result;
	}

	public function fninserttemplanscapesubject($larrmainlandscapesubesult,$idlandscape) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapesubject";
		$sessionID = Zend_Session::getId();

		foreach($larrmainlandscapesubesult as $formData) {
			$larrteplandscapesubresult = array('IdProgram'=>$formData['IdProgram'],
					'IdSubject'=>$formData['IdSubject'],
					'SubjectType'=>$formData['SubjectType'],
					'IdSemester'=>$formData['IdSemester'],
					'CreditHours'=>$formData['CreditHours'],
					'Active'=>$formData['Active'],
					'UpdDate'=>$formData['UpdDate'],
					'UpdUser'=>$formData['UpdUser'],
					'unicode'=>$idlandscape,
					'Date'=>date("Y-m-d"),
					'sessionId'=>$sessionID,
					'compulsory' => $formData['Compulsory'],
					'idExists'=>$formData['IdLandscapeSub'],
					'IDProgramMajoring'=>$formData['IDProgramMajoring'],
					'deleteFlag'=>'1'
			);

			$db->insert($table,$larrteplandscapesubresult);
		}

	}

	public function fnGetTemproryLandscapesubResult($idlandscape,$landscapetype) { //Function for the view University
		$sessionID = Zend_Session::getId();
		$db = Zend_Db_Table::getDefaultAdapter();
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape'))
		->join(array('prg'=>'tbl_templandscapesubject'),'lan.IdLandscape = prg.unicode')
		->join(array('def'=>'tbl_definationms'),'prg.SubjectType = def.idDefinition')
		->joinLeft(array('sub'=>'tbl_subjectmaster'),'prg.IdSubject = sub.IdSubject')
		->joinLeft(array('maj'=>'tbl_programmajoring'),'prg.IDProgramMajoring=maj.IDProgramMajoring',array('maj.EnglishDescription'))
		->where('lan.IdLandscape = ?',$idlandscape)
		->where('prg.deleteFlag = 1')
		->where('prg.sessionId = ?',$sessionID);
		//->order('prg.IdSemester');
		
		$select .= ("ORDER BY prg.IdSemester,CASE WHEN prg.IDProgramMajoring = '57' THEN 0 ELSE 1 END , prg.IDProgramMajoring");
		
		$result = $db->fetchAll($select);
		//print $idlandscape;
		return $result;
		//return $result->toArray();
	}

	public function fnUpdateTempProgramRequriments($IdTempProgReq) {  // function to update po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_tempprogramrequirement";
		$larramounts = array('deleteFlag'=>'0');
		$where = "IdTempProgramReq = '".$IdTempProgReq."'";
		$db->update($table,$larramounts,$where);
	}

	public function fnUpdateTempLandscapesubject($IdTempLandscapesub) {  // function to update po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapesubject";
		$larramounts = array('deleteFlag'=>'0');
		$where = "IdTempLandscapeSub = '".$IdTempLandscapesub."'";
		$db->update($table,$larramounts,$where);
	}


	public function fnUpdateMainLandscape($IdLandscape,$idstartsemester,$description,$AddDrop,$landscapeDefaultLanguage,$IdScheme) {  // function to update po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscape";
		$larrupdate = array('IdStartSemester'=>$idstartsemester,'ProgramDescription'=>$description,'AddDrop'=>$AddDrop,'landscapeDefaultLanguage'=>$landscapeDefaultLanguage,'Scheme' => $IdScheme);
		$where = "IdLandscape = '".$IdLandscape."'";
		$db->update($table,$larrupdate,$where);
	}


	public function fnGetTempProgramrequrimentsDetails($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_tempprogramrequirement'),array('a.IdTempProgramReq'))
		->where("a.unicode = '$Idlandscape'")
		->where("a.sessionId = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result;
	}

	public function fnGetTempProgramrequrimentsDetailslevel($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_tempprogramrequirement'),array('a.IdTempProgramReq'))
		->where("a.unicode = '$Idlandscape'")
		->where("a.deleteFlag = '1'")
		->where("a.sessionID = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnGetTempLandscapeSubject($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_templandscapesubject'),array('a.IdTempProgramReq'))
		->where("a.unicode  = '$Idlandscape'")
		->where("a.deleteFlag = '1'")
		->where("a.sessionID = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}



	public function fnGetTempLandscapeBlockLevel($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_templandscapeblock'),array('a.IdTempProgramReq'))
		->where("a.IdLandscape = '$Idlandscape'")
		->where("a.deleteFlag = '1'")
		->where("a.session_id = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}



	public function fnGetTempLandscapeSemesterLevel($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_templandscapeblocksemester'),array('a.IdTempProgramReq'))
		->where("a.IdLandscape = '$Idlandscape'")
		->where("a.deleteFlag = '1'")
		->where("a.session_id = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function fnGetTempLandscapeYear($Idlandscape,$sessionID){
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_templandscapeblockyear'),array('a.IdLandscapetempblockyear'))
		->where("a.IdLandscape = '$Idlandscape'")
		->where("a.session_id = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result;
	}

	public function fnDeleteFromProgramRequirements($landscape){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_programrequirement";
		$where = $db->quoteInto('IdLandscape = ?',$landscape);
		$db->delete($table, $where);
	}


	public function deleteLandscapeSubjectLevel($landscape){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapesubject";
		$where = $db->quoteInto('IdLandscape = ?',$landscape);
		$db->delete($table, $where);

	}


	public function deleteLandscapeBlockLevel($landscape){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblock";
		$where = $db->quoteInto('IdLandscape = ?',$landscape);
		$db->delete($table, $where);

	}


	public function deleteLandscapeSemesterLevel($landscape){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksemester";
		$where = $db->quoteInto('IdLandscape = ?',$landscape);
		$db->delete($table, $where);

	}

	public function deleteLandscapeyear($idlandscpae){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_blocksemesteryear";
		$where = $db->quoteInto('IdLandscape = ?',$idlandscpae);
		$db->delete($table, $where);
	}

	public function fnDeleteProgramrequriments($programreq ) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_programrequirement";
		$where = $db->quoteInto('IdProgramReq = ?', $programreq);
		$db->delete($table, $where);
	}


	public function fnGetTempLandscapeSubjectDetails($Idlandscape,$sessionID) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_templandscapesubject'),array('a.IdTempLandscapeSub'))
		->where("a.unicode = '$Idlandscape'")
		->where("a.sessionId = '$sessionID'");
		$result = $this->fetchAll($select);
		return $result;
	}

	public function fnDeleteLandscapeSubject($IdLandscapeSub ) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapesubject";
		$where = $db->quoteInto('IdLandscapeSub = ?', $IdLandscapeSub);
		$db->delete($table, $where);
	}

	public function fnDeleteTempProgramReq($Idlandscape,$sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_tempprogramrequirement";
		$where = $db->quoteInto('unicode = ?', $Idlandscape);
		$where = $db->quoteInto('sessionId = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnDeleteTemplandscapesub($Idlandscape,$sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapesubject";
		$where = $db->quoteInto('unicode = ?', $Idlandscape);
		$where = $db->quoteInto('sessionId = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnDeleteTempProgramreqBysession($sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_tempprogramrequirement";
		$where = $db->quoteInto('sessionId = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnDeleteTemplandsacpesubBysession($sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapesubject";
		$where = $db->quoteInto('sessionId = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnDeleteTemplandsacpeblockBysession($sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblock";
		$where = $db->quoteInto('session_id = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnDeleteTemplandsacpeblocksemesterBysession($sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblocksemester";
		$where = $db->quoteInto('session_id = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnDeleteTemplandsacpeblocksubjectBysession($sessionID) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblocksubject";
		$where = $db->quoteInto('session_id = ?', $sessionID);
		$db->delete($table, $where);
	}

	public function fnUpdateTempblock($IdLandscapetempblock) {  // function to update po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblock";
		$larramounts = array('deleteFlag'=>'0');
		$where = "IdLandscapetempblock = '".$IdLandscapetempblock."'";
		$db->update($table,$larramounts,$where);
	}

	public function fnUpdateTempblocksub($IdLandscapetempblocksubject) {  // function to update po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblocksubject";
		$larramounts = array('deleteFlag'=>'0');
		$where = "IdLandscapetempblocksubject = '".$IdLandscapetempblocksubject."'";
		$db->update($table,$larramounts,$where);
	}

	public function fnUpdateTempblocksem($IdLandscapetempblocksemester) {  // function to update po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblocksemester";
		$larramounts = array('deleteFlag'=>'0');
		$where = "IdLandscapetempblocksemester = '".$IdLandscapetempblocksemester."'";
		$db->update($table,$larramounts,$where);
	}


	public function fnUpdateTempblockyear($IdLandscapetempblockyear){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_templandscapeblockyear";
		$larramounts = array('deleteFlag'=>'0');
		$where = "IdLandscapetempblockyear = '".$IdLandscapetempblockyear."'";
		$db->update($table,$larramounts,$where);
	}

	public function fninsertoldlandscapesemester($formData,$resultLandscape) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_oldlandsacpesemester";
		$count = count($formData['Semester']);
		for($i=0;$i<$count;$i++){
			$larrtepprogrmentryreq = array('IdLandscape'=>$resultLandscape,
					'semesterid'=>$formData['Semester'][$i]);
			$db->insert($table,$larrtepprogrmentryreq);
		}
	}

	public function fninsertToProgramRequirements($formData,$idlandscpae) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_programrequirement";
		$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				'IdProgram'=>$formData['IdProgram'],
				'SubjectType'=>$formData['SubjectType'],
				'CreditHours'=>$formData['CreditHours'],
				'UpdDate'=>$formData['UpdDate'],
				'UpdUser'=>$formData['UpdUser']);
		$db->insert($table,$larrtepprogrmentryreq);

	}


	public function fninsertToLandScapeSubjectLevel($formData,$idlandscpae) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapesubject";
		$larrtepprogrmentryreq = array('IdProgram'=>$formData['IdProgram'],
				'IdLandscape'=>$idlandscpae,
				'IdSubject'=>$formData['IdSubject'],
				'SubjectType'=>$formData['SubjectType'],
				'IdSemester'=>$formData['IdSemester'],
				'CreditHours'=>$formData['CreditHours'],
				'IDProgramMajoring'=>$formData['IDProgramMajoring'],
				'Compulsory'=>$formData['Compulsory'],
				'IDProgramMajoring'=>$formData['IDProgramMajoring'],
				'Active'=>$formData['Active'],
				'UpdDate'=>$formData['UpdDate'],
				'UpdUser'=>$formData['UpdUser']);
		$db->insert($table,$larrtepprogrmentryreq);

	}



	public function fninsertToLandScapeBlockLevel($formData,$idlandscpae) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblock";

		$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				'block'=>$formData['blockid'],
				'blockname'=>$formData['blockname'],
				'CreditHours'=>$formData['CreditHours']);
		$db->insert($table,$larrtepprogrmentryreq);

	}



	public function fninsertToLandScapeSemesterLevel($formData,$idlandscpae) {  // function to insert po details
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksemester";
		$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				'blockid'=>$formData['blockid'],
				'semesterid'=>$formData['semesterid']);
		$db->insert($table,$larrtepprogrmentryreq);

	}

	public function fninsertToLandScapeYear($templandScapeYear,$idlandscpae){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_blocksemesteryear";
		$larrtepprogrmentryreq = array('IdLandscape'=>$idlandscpae,
				'Year'=>$templandScapeYear['Year'],
				'YearSemester'=>$templandScapeYear['YearSemester']);
		$db->insert($table,$larrtepprogrmentryreq);

	}

	public function fnGetoldLandscapeDetails($idlandscape) { // Function to edit Purchase order details
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_oldlandsacpesemester'),array('a.*'))
		->where("a.IdLandscape = '$idlandscape'");
		$result = $this->fetchAll($select);
		return $result;
	}

	public function fnDeletelandscapeoldsemester($IdLandscape) { //Function for Delete Purchase order terms
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_oldlandsacpesemester";
		$where = $db->quoteInto('IdLandscape = ?', $IdLandscape);
		$db->delete($table, $where);
	}

	public function fnupdateLandscapeActiveaction($idlandscape,$IdProgram,$idintake,$idscheme) { //Function for updating the university
		$where1 = "IdProgram = '.$IdProgram.' AND IdStartSemester = '.$idintake.' AND Scheme = '.$idscheme.'AND Active='123' ";
		$formData1['Active'] = 124;
		$this->update($formData1,$where1);

		$where = 'IdLandscape = '.$idlandscape;
		$formData['Active'] = 123;
		$this->update($formData,$where);
	}

	public function fnupdateLandscapeInActiveaction($idlandscape) { //Function for updating the university
		$where = 'IdLandscape = '.$idlandscape;
		$formData['Active'] = 124;
		$this->update($formData,$where);
	}

	public function fnGetMajoringList($IdProgram){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_programmajoring"),array("key"=>"a.IDProgramMajoring","value"=>"a.EnglishDescription"))
		->where('a.idProgram=?',$IdProgram)
		->order("a.EnglishDescription");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	public function fnGetMajoringListAll(){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_programmajoring"),array("key"=>"a.IDProgramMajoring","value"=>"a.EnglishDescription"))
		->order("a.EnglishDescription");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	//Added by Mervyn
	public function fngetlandscapeblock($idlandscape) {
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_landscapeblock'),array('a.block','a.blockname','a.CreditHours'))
		->join(array('b'=>'tbl_landscapeblocksemester'),'a.idlandscape = b.IdLandscape AND a.block = b.blockid	',array('b.semesterid'))
		->join(array('c'=>'tbl_landscapeblocksubject'),'a.idlandscape = c.IdLandscape AND b.blockid = c.blockid',array('c.subjectid'))
		->join(array('d'=>'tbl_subjectmaster'),'c.subjectid = d.IdSubject',array('d.SubjectName','d.SubCode'))
		->where("a.idlandscape = ?",$idlandscape);
		$larrResult = $this->lobjDbAdpt->fetchAll($select);
		return $larrResult;
	}

	public function fngetlandscapesemester($idlandscape) {
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_landscapesubject'),array(),array('a.CreditHours','a.IdSemester'))
		->join(array('b' => 'tbl_definationms'),'a.SubjectType =b.idDefinition',array('b.DefinitionDesc as SubjectType'))
		->join(array('d'=>'tbl_subjectmaster'),'a.IdSubject = d.IdSubject',array('d.SubjectName','d.SubCode'))
		->where("a.idlandscape = ?",$idlandscape)
		->order("a.IdSemester");
		$larrResult = $this->lobjDbAdpt->fetchAll($select);
		return $larrResult;
	}

	public function fngetlandscapelevel($idlandscape) {
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_landscapeblock'),array(),array('a.block','a.blockname'))
		->join(array('b'=>'tbl_landscapeblocksemester'),'a.idlandscape = b.IdLandscape AND a.block = b.blockid	',array('b.semesterid'))
		->join(array('c'=>'tbl_landscapesubject'),'a.idlandscape = c.IdLandscape AND b.semesterid = c.IdSemester',array('c.IdSubject','c.CreditHours'))
		->join(array('d'=>'tbl_subjectmaster'),'c.IdSubject = d.IdSubject',array('d.SubjectName','d.SubCode'))
		->join(array('e' => 'tbl_definationms'),'c.SubjectType =e.idDefinition',array('e.DefinitionDesc as SubjectType'))
		->where("a.idlandscape = ?",$idlandscape);
		$larrResult = $this->lobjDbAdpt->fetchAll($select);
		return $larrResult;
	}

	public function fngetlandscapedetails($idlandscape) {
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('a' => 'tbl_landscape'),array(),array())
		->join(array('b' => 'tbl_scheme'),'a.Scheme =b.IdScheme',array('b.EnglishDescription as Scheme'))
		->join(array('d'=>'tbl_program'),'a.IdProgram = d.IdProgram',array('d.ProgramName'))
		->where("a.idlandscape = ?",$idlandscape);
		$larrResult = $this->lobjDbAdpt->fetchAll($select);
		return $larrResult;
	}

	/**
	 * Function to get the landscape intake
	 * @author: Vipul
	 */
	public function fnfetchLandScapeIntake($idlandscape){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_landscape"),array("a.IdLandscape","a.IdStartSemester"))
		->join(array("b"=>"tbl_intake"),'a.IdStartSemester = b.IdIntake',array("b.IntakeId"))
		->where("a.IdLandscape = ?",$idlandscape);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	/**
	 * Function to get the landscape ID
	 * @author: Vipul
	 */
	public function getlandscapeID($pid,$intkid,$schmid,$landscapetype=NULL){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_landscape"))
		->where("a.IdProgram = ?",$pid)
		->where("a.IdStartSemester = ?",$intkid)
		->where("a.LandscapeType = ?",$landscapetype)
		->where("a.Scheme = ?",$schmid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		// asd($larrResult);
		if(count($larrResult)>1) {
			$lstrSelect = $lobjDbAdpt->select()
			->from(array("a"=>"tbl_landscape"))
			->where("a.IdProgram = ?",$pid)
			->where("a.IdStartSemester = ?",$intkid)
			->where("a.LandscapeType = ?",$landscapetype)
			->where("a.Scheme = ?",$schmid)
			->order("a.IdLandscape ASC ")
			->limit('1')  ;
			$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		}

		return $larrResult;
	}




	/**
	 * Function to get landscape BLock Subject
	 * @author: Vipul
	 */
	public function getlandscapeblocksemester($lid){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_landscapeblocksubject"))
		->where("a.IdLandscape = ?",$lid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	/**
	 * Function to INSERT thelandscape BLock Subject
	 * @author: Vipul
	 */
	public function fnaddlandscapeBlockSubject($formData){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_landscapeblocksubject";
		$data = array('IdLandscape' => $formData['IdLandscape'],
				'blockid' => $formData['blockid'],
				'subjectid' => $formData['subjectid'],
				'coursetypeid' => $formData['coursetypeid']);
		$db->insert($table,$data);
	}


	/**
	 * Function to get landscape BLock SEMESTER YEAR
	 * @author: Vipul
	 */
	public function getlandscapeblocksemesteryear($lid){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_blocksemesteryear"))
		->where("a.IdLandscape = ?",$lid);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}

	/**
	 * Function to INSERT thelandscape BLock Subject
	 * @author: Vipul
	 */
	public function fnaddlandscapeblocksemesteryear($formData){
		$db = Zend_Db_Table::getDefaultAdapter();
		$table = "tbl_blocksemesteryear";
		$data = array('IdLandscape' => $formData['IdLandscape'],
				'Year' => $formData['Year'],
				'YearSemester' => $formData['YearSemester']);
		$db->insert($table,$data);
	}


	/**
	 * Function to get the balance and added hours based on landscpae, program and SubjectType
	 * @author: Vipul
	 */
	public function fnfetchHours($idlandscape,$idProg,$idST){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_templandscapesubject"),array("SUM(a.CreditHours) as TotalAddedHours"))
		->where("a.IdProgram = ?",$idProg)
		->where("a.SubjectType = ?",$idST)
		->where("a.unicode = ?",$idlandscape);
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	/**
	 * Funtion to calculate balance and added hours left based on landscape and program IDs.
	 * @author Vipul
	 */
	public function fnGetBalAddedHoursList($idLandscape,$idProgram,$idSubjectType){
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		$lstrSelect = $lobjDbAdpt->select()
		->from(array("a"=>"tbl_templandscapesubject"),array("SUM(a.CreditHours) as TotalLeftHours"))
		->where("unicode = '".$idLandscape."' AND IdProgram='".$idProgram."' AND SubjectType='".$idSubjectType."' AND deleteFlag='0' ");
		$larrResult = $lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	/*
	 *  Function to return the all courses of landscape on the basis of scheme
	*/

	public function fnGetcoursesByScheme($studentId){
		/*$lstrSelect = $this->lobjDbAdpt->select()
		 ->from(array("a"=>"tbl_landscape"),array())
		->joinLeft(array("b"=>"tbl_landscapesubject"),"a.IdLandscape = b.IdLandscape",array())
		->joinLeft(array("c"=>"tbl_subjectmaster"),"b.IdSubject = c.IdSubject",array("key" => "c.IdSubject","name" => "c.SubjectName"))
		->where("a.Scheme =?",$idScheme);
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;*/
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("b"=>"tbl_studentregistration"),array())
		->joinLeft(array("c"=>"tbl_landscapesubject"),"b.IdLandscape = c.IdLandscape",array())
		//->joinLeft(array("e"=>"tbl_studentregsubjects"),"b.IdStudentRegistration=e.IdStudentRegistration AND (e.exam_status !='C' OR e.exam_status is null)AND e.Active is null",array())
		->joinLeft(array("d"=>"tbl_subjectmaster"),"c.IdSubject = d.IdSubject",array("key" => "d.IdSubject","name" => "CONCAT_WS(' - ',IFNULL(d.ShortName,''),IFNULL(d.SubjectName,''))"))
		->where("b.IdStudentRegistration =?",$studentId)
		->group('d.IdSubject');
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;

	}


	public function checkActiveLandscape($IdProgram) {

		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("b"=>"tbl_landscape"))
		->where("b.IdProgram =?",$IdProgram)
		->where("b.Active =?",'123');
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	public function fngetActiveLandscape($IdProgram,$idintake,$idscheme) {
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("b"=>"tbl_landscape"))
		->where("b.IdProgram =?",$IdProgram)
		->where("b.IdStartSemester =?",$idintake)
		->where("b.Scheme =?",$idscheme)
		->where("b.Active =?",'123');
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	public function fngetActiveLandscapeSubjects($IdProgram) {
		$lstrSelect = $this->lobjDbAdpt->select()
		->from(array("b"=>"tbl_landscape"))
		->where("b.IdProgram =?",$IdProgram)
		->joinLeft(array("c"=>"tbl_landscapesubject"),"b.IdLandscape = c.IdLandscape",array())
		->joinLeft(array("d"=>"tbl_subjectmaster"),"c.IdSubject = d.IdSubject AND d.Active=1 ",array("key" => "d.IdSubject","value" => "CONCAT_WS(' - ',IFNULL(d.SubjectName,''),IFNULL(d.SubCode,''))"))
		->where("b.Active =?",'123')->group("d.IdSubject");
		//echo $lstrSelect; die;
		$larrResult = $this->lobjDbAdpt->fetchAll($lstrSelect);
		return $larrResult;
	}


	   /*start yatie*/
        
	public function getData($idlandscape){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array('l'=>$this->_name))
		 				 ->join(array('def'=>'tbl_definationms'),'l.LandscapeType = def.idDefinition')
		 				 
		 				 ->where("l.IdLandscape = ?",$idlandscape);
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
     }
     
	public function LandscapeListByProgram($IdProgram) { 
		$select = $this->select()
		->setIntegrityCheck(false)
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active','lan.ProgramDescription'))
		->join(array('prg'=>'tbl_program'),'lan.IdProgram = prg.IdProgram',array('prg.IdProgram'))
		->join(array('def'=>'tbl_definationms'),'lan.LandscapeType = def.idDefinition')
		->joinLeft(array('tink'=>'tbl_intake'),'tink.IdIntake = lan.IdStartSemester',array('tink.IntakeId'))
		->where('lan.IdProgram = ?',$IdProgram)
		->order("lan.UpdDate DESC");
		
		return $select;
	}
	
	public function getLandscapeDetails($idlandscape){
     	$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
		 $lstrSelect = $lobjDbAdpt->select()
		 				 ->from(array('l'=>$this->_name))
		 				 ->joinLeft(array('p'=>'tbl_program'),'p.IdProgram=l.IdProgram',array('ArabicName'))
		 				 ->joinLeft(array('def'=>'tbl_definationms'),'l.LandscapeType = def.idDefinition',array('DefinitionDesc'))
		 				 ->joinLeft(array('tink'=>'tbl_intake'),'tink.IdIntake = l.IdStartSemester',array('IntakeDesc'))
		 				 ->where("l.IdLandscape = ?",$idlandscape);

		 				
		$larrResult = $lobjDbAdpt->fetchRow($lstrSelect);
		return $larrResult;
     }
     
	public function addData($data)
    {    	     
        $id =$this->insert($data);        
        return $id;
    }
     
     
	public function updateData($data,$id) {			
    	  $this->update($data,"IdLandscape = '".$id."'");    	
	}
	
	public function updateDefault($data,$programId,$type,$intake) {	
		
    	  $this->update($data,"IdProgram ='.$programId.' AND LandscapeType = '".$type."' AND IdStartSemester = '".$intake."'");    	
	}
	
	
	public function getActiveLandscape($IdProgram,$idIntake) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter(); 

		$select = $this->select()	
						->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active','lan.ProgramDescription'))			
						->where('lan.IdProgram = ?',$IdProgram)
						//->where('lan.IdStartSemester = ?',$idIntake)
						->where('lan.Active = ?',1)
						->order("lan.Default");
		
		$larrResult = $lobjDbAdpt->fetchAll($select);
		return $larrResult;
	}
	
	public function getAllActiveLandscape($IdProgram=null) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter(); 

		$select = $this->select()	
						->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active','lan.ProgramDescription'))			
						
						->where('lan.Active = ?',1)
						->order("lan.Default");
		if ($IdProgram!=null ) $select->where('lan.IdProgram = ?',$IdProgram);
		$larrResult = $lobjDbAdpt->fetchAll($select);
		return $larrResult;
		
	}
	public function getLandscape($IdProgram) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter();
	
		$select = $this->select()
		->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.ProgramDescription'))
		->where('lan.IdProgram = ?',$IdProgram)
		->order("lan.Default");
	
		$larrResult = $lobjDbAdpt->fetchAll($select);
		return $larrResult;
	
	}
	
	public function getAllLandscape($IdProgram) {
		$lobjDbAdpt = Zend_Db_Table::getDefaultAdapter(); 

		$select = $this->select()	
						->join(array('lan' => 'tbl_landscape'),array('lan.IdLandscape','lan.Active','lan.ProgramDescription'))			
						->where('lan.IdProgram = ?',$IdProgram)
						->order("lan.Default");
		
		$larrResult = $lobjDbAdpt->fetchAll($select);
		return $larrResult;
		
	}	


}
?>
