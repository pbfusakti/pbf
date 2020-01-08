<?php
class App_Model_General_DbTable_Studentsemesterstatus extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'tbl_studentsemesterstatus';
    protected $_primary = "idstudentsemsterstatus";


    public function addData($data)
    {
        $id = $this->insert($data);
        return $id;
    }

    public function updateData($data, $id)
    {
        $this->update($data, $this->_primary . ' = ' . (int)$id);
    }

    public function deleteData($id)
    {
        $this->delete($this->_primary . ' =' . (int)$id);
    }


    public function getRegisteredBlock($registrationId)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name))
            ->joinleft(array('b' => 'tbl_landscapeblock'), 'b.idblock=s.IdBlock', array('blockname', 'semester_level' => 'semester'))
            ->joinleft(array('sm' => 'tbl_semestermaster'), 'sm.IdSemesterMaster=s.IdSemesterMain', array('SemesterMainName', 'SemesterMainCode', 'IdSemesterMaster'))
            ->where('s.IdStudentRegistration = ?', $registrationId)
            ->order("s.idstudentsemsterstatus")
            ->group('s.IdBlock');

        $result = $db->fetchAll($sql);

        //print_r($result);
        return $result;
    }


    public function getRegisteredSemester($registrationId)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name))
            ->joinleft(array('sm' => 'tbl_semestermaster'), 'sm.IdSemesterMaster=s.IdSemesterMain', array('SemesterMainName', 'SemesterMainCode', 'IdSemesterMaster'))
            ->where('s.IdStudentRegistration = ?', $registrationId)
            ->order("sm.SemesterMainStartDate Desc")
            ->group('s.IdSemesterMain');

        $result = $db->fetchAll($sql);
        return $result;
    }

    public function getIdPrevSemesterStatus($registrationId, $Level = null)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name), array('id' => 'idstudentsemsterstatus', 'Level'))
            ->where('s.IdStudentRegistration = ?', $registrationId);
            //->order('s.Level DESC');
        if ($Level != null) $sql->where('s.Level =?', $Level);
//echo $sql; 
        $result = $db->fetchRow($sql);
       // echo var_dump($result);exit;
        return $result;
    }

    public function getIdSemesterStatus($registrationId, $Level = null)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name), array('id' => 'idstudentsemsterstatus', 'Level'))
            ->where('s.IdStudentRegistration = ?', $registrationId)
            ->order('s.Level DESC');
        if ($Level != null) $sql->where('s.Level =?', $Level);

        $result = $db->fetchRow($sql);
        return $result;
    }

    public function updateRegistrationData($data, $id)
    {
        $this->update($data, 'IdStudentRegistration = ' . $id);
    }

    public function deleteRegisterData($id)
    {
        $this->delete('IdStudentRegistration =' . (int)$id);
    }


    public function getSemesterInfo($idstudentsemsterstatus = 0)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name))
            ->joinleft(array('sm' => 'tbl_semestermaster'), 'sm.IdSemesterMaster=s.IdSemesterMain', array('SemesterMainName', 'SemesterMainCode', 'IdSemesterMaster'))
            ->where('s.idstudentsemsterstatus = ?', $idstudentsemsterstatus);

        $result = $db->fetchRow($sql);
        return $result;
    }

    public function getRegisteredSemesterBlock($registrationId, $idSemester)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name))
            ->joinleft(array('b' => 'tbl_landscapeblock'), 'b.idblock=s.IdBlock', array('blockname', 'semester_level' => 'semester'))
            ->joinleft(array('sm' => 'tbl_semestermaster'), 'sm.IdSemesterMaster=s.IdSemesterMain', array('SemesterMainName', 'SemesterMainCode', 'IdSemesterMaster'))
            ->where('s.IdStudentRegistration = ?', $registrationId)
            ->where('s.IdSemesterMain = ?', $idSemester)
            ->order("s.idstudentsemsterstatus");


        $result = $db->fetchAll($sql);

        //print_r($result);
        return $result;
    }

    public function getCountableRegisteredSemester($registrationId)
    {

        //utk dapatakan jumlah semester yg sudah pernah daftar, countable dan status completed atau register
        //jika dulu pernah daftar then quit/tangguh maka ia tidak dikira
        //jika semester tu not countable cth semster pembaikan maka ia tidak dikira naik level

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name))
            ->join(array('sm' => 'tbl_semestermaster'), 'sm.IdSemesterMaster=s.IdSemesterMain', array('SemesterMainName', 'SemesterMainCode', 'IdSemesterMaster'))
            ->where('s.IdStudentRegistration = ?', $registrationId)
            ->where('sm.IsCountable = 1')
            ->where('(s.studentsemesterstatus = 130 OR s.studentsemesterstatus = 229)') //130:Register 229:Completed
            ->order("s.idstudentsemsterstatus");

        $result = $db->fetchAll($sql);
        return $result;
    }

    public function isRegisteredSemesterStatus($IdStudentRegistration, $idSemester)
    {

        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()
            ->from(array('s' => $this->_name))
            ->where('s.IdStudentRegistration = ?', $IdStudentRegistration)
            ->where('s.IdSemesterMain = ?', $idSemester);

        $result = $db->fetchRow($sql);

        return $result;
    }

    public function checkSemesterCourseRegistration($idsemester, $progid,$activity=18)
    {


        $db = Zend_Db_Table::getDefaultAdapter();


        $select = $db->select()
            ->from(array('sm' => 'tbl_semestermaster'))
            ->from(array('tp' => 'tbl_program'))
            ->join(array('ac' => 'tbl_activity_calender'), 'ac.IdSemesterMain = sm.IdSemesterMaster')
            ->where('NOW()    BETWEEN CONCAT(ac.StartDate," ",ac.StartTime ) AND CONCAT(ac.EndDate," ",ac.EndTime)')
            ->where('ac.idActivity=?',$activity)
            ->where('sm.IdSemesterMaster = ?', $idsemester)
            ->where('ac.idProgram=?', $progid);
         

        $row = $db->fetchRow($select);
//echo var_dump($row);exit;
        if (!isset($row)) {
        	//get from global/university setup
            $sql = $db->select()
                ->from(array('sm' => 'tbl_semestermaster'))
                ->from(array('tp' => 'tbl_program'))
                ->join(array('ac' => 'tbl_activity_calender'), 'ac.IdSemesterMain = sm.IdSemesterMaster')
               ->where('NOW()    BETWEEN CONCAT(ac.StartDate," ",ac.StartTime ) AND CONCAT(ac.EndDate," ",ac.EndTime)')
                ->where('idActivity=?',$activity)
                ->where('ac.idProgram IS NULL')
                ->where('sm.IdSemesterMaster = ?', $idsemester);

            $row = $db->fetchRow($sql);
        }

        return $row;
    }


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

    public function registeredSemesters($IdStudentRegistration) {
        $studentRegistrationDB = new Registration_Model_DbTable_Studentregistration();
        $studentdetails = $studentRegistrationDB->fetchStudentHistoryDetails($IdStudentRegistration);

        $landscapeDb = new GeneralSetup_Model_DbTable_Landscape();
        $landscape = $landscapeDb->getData($studentdetails["IdLandscape"]);
        if($landscape["LandscapeType"]==43 || $landscape["LandscapeType"]==44) {
            //get total registered semester
            $semesters = $this->getRegisteredSemester($IdStudentRegistration);
        } else if ($landscape["LandscapeType"] == 45) {
            $semesters = $this->getRegisteredBlock($IdStudentRegistration);
        }

        return($semesters);
    }

    public function statuscount($IdStudentRegistration, $status) {
        $select = $this->select()
                ->where('IdStudentRegistration = ?', $IdStudentRegistration)
                ->where('studentsemesterstatus = ?', $status);

        $semesters = $this->fetchAll($select);
        return($semesters->count());
    }

    public function getStatus($IdStudentRegistration, $IdSemesterMain) {
        $select = $this->select()
            ->where('IdStudentRegistration = ?', $IdStudentRegistration)
            ->where('IdSemesterMain = ?', $IdSemesterMain)
            ;
        $semester_status = $this->fetchRow($select);
        if(!empty($semester_status)) {
            $Definition = new App_Model_General_DbTable_Definationms();
            $definition = $Definition->getData($semester_status->studentsemesterstatus);
            return($definition);
        } else {
            return false;
        }

    }
    
    public function getLevelMax($idstudent = 0,$idsemester)
    {
    
    	$db = Zend_Db_Table::getDefaultAdapter();
    
    	$semesters = $db->select()
    				->from(array('sm'=>'tbl_semestermaster'))
    				->where('sm.IdSemesterMaster=?',$idsemester);
    	$result = $db->fetchRow($semesters);
    	$semstart=$result['SemesterMainStartDate'];
    	
    	$semesters = $db->select()
    	->from(array('sm'=>'tbl_semestermaster'),array('IdSemesterMaster'))
    	->where('sm.IsCountable="1"')
    	->where('sm.SemesterMainStartDate<?',$semstart);
    	
    	$sql = $db->select()
    	->from(array('s' => $this->_name),array('Level'=>'max(Level)'))
    	//->joinleft(array('sm' => 'tbl_semestermaster'), 'sm.IdSemesterMaster=s.IdSemesterMain', array('SemesterMainName', 'SemesterMainCode', 'IdSemesterMaster'))
    	->where('s.IdStudentRegistration = ?', $idstudent)
    	->where('s.IdSemestermain in (?)',$semesters)
    	->group('s.IdStudentRegistration');
    	
    
    	$result = $db->fetchRow($sql);
    	return $result;
    }
    
	public function getsemcountable($idstudreg){
		$sql = "SELECT idstudentsemsterstatus,ay_code, semestermainname ,level  FROM `tbl_studentsemesterstatus` a
			inner join tbl_studentregistration as d on a.IdStudentRegistration=d.IdStudentRegistration
			inner join tbl_semestermaster as b on a.IdSemesterMain=b.IdSemesterMaster
			inner join tbl_academic_year as c on b.idacadyear=c.ay_id
			WHERE a.IdStudentRegistration = '$idstudreg' and IsCountable=1
			ORDER BY `ay_code`,semestercounttype ASC";

		$rows = $this->_db->fetchAll($sql);	
		return $rows;		
	}
	
	public function openquery($sql){
		return $this->_db->query($sql);		
	}    
}