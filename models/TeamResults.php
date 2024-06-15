<?php
/**
 * Created by schranzli.
 * User: Martin.
 * Date: 2023-07-11
 * Time: 20:25
 */

class TeamResults implements DatabaseService
{
    const TABLE_NAME = "tlsb_team_result";
    const COLUMN_ID = "result_id";
    const COLUMN_ROUNDID = "round_id";
    const COLUMN_HOMETEAMID = "hometeam_id";
    const COLUMN_HOMETEAMCOUNT = "hometeam_count";
    const COLUMN_HOMETEAMRESULT = "hometeam_result";
    const COLUMN_HOMETEAMPOINTS = "hometeam_points";
    const COLUMN_T1P1NUMBER = "t1_p1_number";
    const COLUMN_T1P1RESULT = "t1_p1_result";
    const COLUMN_T1P1ITEN = "t1_p1_i_zehner";
    const COLUMN_T1P2NUMBER = "t1_p2_number";
    const COLUMN_T1P2RESULT = "t1_p2_result";
    const COLUMN_T1P2ITEN = "t1_p2_i_zehner";
    const COLUMN_T1P3NUMBER = "t1_p3_number";
    const COLUMN_T1P3RESULT = "t1_p3_result";
    const COLUMN_T1P3ITEN = "t1_p3_i_zehner";
    const COLUMN_T1P4NUMBER = "t1_p4_number";
    const COLUMN_T1P4RESULT = "t1_p4_result";
    const COLUMN_T1P4ITEN = "t1_p4_i_zehner";
    const COLUMN_T1P5NUMBER = "t1_p5_number";
    const COLUMN_T1P5RESULT = "t1_p5_result";
    const COLUMN_T1P5ITEN = "t1_p5_i_zehner";
    const COLUMN_GUESTTEAMID = "guestteam_id";
    const COLUMN_GUESTEAMCOUNT = "guestteam_count";
    const COLUMN_GUESTEAMRESULT = "guestteam_result";
    const COLUMN_GUESTEAMPOINTS = "guestteam_points";
    const COLUMN_T2P1NUMBER = "t2_p1_number";
    const COLUMN_T2P1RESULT = "t2_p1_result";
    const COLUMN_T2P1ITEN = "t2_p1_i_zehner";
    const COLUMN_T2P2NUMBER = "t2_p2_number";
    const COLUMN_T2P2RESULT = "t2_p2_result";
    const COLUMN_T2P2ITEN = "t2_p2_i_zehner";
    const COLUMN_T2P3NUMBER = "t2_p3_number";
    const COLUMN_T2P3RESULT = "t2_p3_result";
    const COLUMN_T2P3ITEN = "t2_p3_i_zehner";
    const COLUMN_T2P4NUMBER = "t2_p4_number";
    const COLUMN_T2P4RESULT = "t2_p4_result";
    const COLUMN_T2P4ITEN = "t2_p4_i_zehner";
    const COLUMN_T2P5NUMBER = "t2_p5_number";
    const COLUMN_T2P5RESULT = "t2_p5_result";
    const COLUMN_T2P5ITEN = "t2_p5_i_zehner";
    const COLUMN_SEASON = "season";
    const COLUMN_CHANGEDATE = "change_date";
    const COLUMN_USERID = "usr_id";
    const COLUMN_CREATIONDATE = "creation_date";
    const COLUMN_CREATIONUSERID = "creation_usr_id";
    const COLUMN_SEASIONSTATE = "season_state";
    const COLUMN_DISCIPLINE = "discipline";

    

    private $id;
    private $roundId;
    private $homeTeamId;
    private $homeTeamCount;
    private $homeTeamResult;
    private $homeTeamPoints;
    private $t1P1Number;
    private $t1P1Result;
    private $t1P1ITen;
    private $t1P2Number;
    private $t1P2Result;
    private $t1P2ITen;
    private $t1P3Number;
    private $t1P3Result;
    private $t1P3ITen;
    private $t1P4Number;
    private $t1P4Result;
    private $t1P4ITen;
    private $t1P5Number;
    private $t1P5Result;
    private $t1P5ITen;
    private $guestTeamId;
    private $guestTeamCount;
    private $guestTeamResult;
    private $guestTeamPoints;
    private $t2P1Number;
    private $t2P1Result;
    private $t2P1ITen;
    private $t2P2Number;
    private $t2P2Result;
    private $t2P2ITen;
    private $t2P3Number;
    private $t2P3Result;
    private $t2P3ITen;
    private $t2P4Number;
    private $t2P4Result;
    private $t2P4ITen;
    private $t2P5Number;
    private $t2P5Result;
    private $t2P5ITen;    
    private $season;
    private $changeDate;
    private $userId;
    private $creationDate;
    private $creationUserId;
    private $seasonState;
    private $discipline;

    private $errors;

    public function __construct( $id, $roundId, 
            $homeTeamId, $homeTeamCount, $homeTeamResult, $homeTeamPoints, $t1P1Number, $t1P1Result, $t1P1ITen, $t1P2Number, $t1P2Result, $t1P2ITen, $t1P3Number, $t1P3Result, $t1P3ITen, $t1P4Number, $t1P4Result, $t1P4ITen, $t1P5Number, $t1P5Result, $t1P5ITen, 
            $guestTeamId, $guestTeamCount, $guestTeamResult, $guestTeamPoints, $t2P1Number, $t2P1Result, $t2P1ITen, $t2P2Number, $t2P2Result, $t2P2ITen, $t2P3Number, $t2P3Result, $t2P3ITen, $t2P4Number, $t2P4Result, $t2P4ITen, $t2P5Number, $t2P5Result, $t2P5ITen,
            $season, $changeDate, $userId, $creationDate, $creationUserId, $seasonState, $discipline
    )
    {
        $this->id = $id;
        $this->roundId = $roundId;
        $this->homeTeamId = $homeTeamId;
        $this->homeTeamCount = $homeTeamCount;
        $this->homeTeamResult = $homeTeamResult;
        $this->homeTeamPoints = $homeTeamPoints;
        $this->t1P1Number = $t1P1Number;
        $this->t1P1Result = $t1P1Result;
        $this->t1P1ITen = $t1P1ITen;
        $this->t1P2Number = $t1P2Number;
        $this->t1P2Result = $t1P2Result;
        $this->t1P2ITen = $t1P2ITen;
        $this->t1P3Number = $t1P3Number;
        $this->t1P3Result = $t1P3Result;
        $this->t1P3ITen = $t1P3ITen;
        $this->t1P4Number = $t1P4Number;
        $this->t1P4Result = $t1P4Result;
        $this->t1P4ITen = $t1P4ITen;
        $this->t1P5Number = $t1P5Number;
        $this->t1P5Result = $t1P5Result;
        $this->t1P5ITen = $t1P5ITen;
        $this->guestTeamId = $guestTeamId;
        $this->guestTeamCount = $guestTeamCount;
        $this->guestTeamResult = $guestTeamResult;
        $this->guestTeamPoints = $guestTeamPoints;
        $this->t2P1Number = $t2P1Number;
        $this->t2P1Result = $t2P1Result;
        $this->t2P1ITen = $t2P1ITen;
        $this->t2P2Number = $t2P2Number;
        $this->t2P2Result = $t2P2Result;
        $this->t2P2ITen = $t2P2ITen;
        $this->t2P3Number = $t2P3Number;
        $this->t2P3Result = $t2P3Result;
        $this->t2P3ITen = $t2P3ITen;
        $this->t2P4Number = $t2P4Number;
        $this->t2P4Result = $t2P4Result;
        $this->t2P4ITen = $t2P4ITen;
        $this->t2P5Number = $t2P5Number;
        $this->t2P5Result = $t2P5Result;
        $this->t2P5ITen = $t2P5ITen;
        $this->season = $season;
        $this->changeDate = $changeDate;
        $this->userId = $userId;
        $this->creationDate = $creationDate;
        $this->creationUserId = $creationUserId;
        $this->seasonState = $seasonState;
        $this->discipline = $discipline;

        $this->errors = [];
    }


   
    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' ORDER BY '.self::COLUMN_ROUNDID.' ASC';
        $stmt=$db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$dataForDuration = Project::getDuration();

        Database::disconnect();

        foreach ($data as $obj) {
            $list[] = self::dataToObject($obj);
        }

        return $list;
    }

    public static function getTable($discipline){
        if(strtolower($discipline->getSeason()) == "s") {
            return self::TABLE_NAME . "_s";
        }
        return self::TABLE_NAME;
    }

    public static function getBySeasonAndTeamIdAndRoundId($discipline, $season, $roundId, $teamId)
    {
        // TODO: Implement getAll() method.
        $list = [];
        //Round_id = 31 AND SEASON = '2022 / 2023' AND (hometeam_id = 171 OR guestteam_id = 171)

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::getTable($discipline).' WHERE '.self::COLUMN_SEASON.' = :season AND '.self::COLUMN_ROUNDID.' = :roundId AND ('.self::COLUMN_HOMETEAMID.' = :teamId or '.self::COLUMN_GUESTTEAMID.' = :teamId) ORDER BY '.self::COLUMN_ROUNDID.' ASC';
        $stmt=$db->prepare($sql);
        
        $stmt->bindParam(':season', $season);
        $stmt->bindParam(':roundId', $roundId);
        $stmt->bindParam(':teamId', $teamId);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$dataForDuration = Project::getDuration();

        Database::disconnect();

        foreach ($data as $obj) {
            $list[] = self::dataToObject($obj);
        }

        return $list;
    }

    public function update(){
        // not Implemented;

        $db = Database::connect();
        $sql = 'UPDATE `'. self::getTabel($discipline).'` SET ';

        `hometeam_count`=4, 
        `hometeam_result`=1576.5, 
        `hometeam_points`=2, 
        `t1_p1_number`='70420022', 
        `t1_p1_result`=404.2, 
        `t1_p2_number`='70420001', 
        `t1_p2_result`=401.3, 
        `t1_p3_number`='70420006', 
        `t1_p3_result`=381.6, 
        `t1_p4_number`='70420011', 
        `t1_p4_result`=389.4, 
        `t1_p5_number`=NULL, 
        `t1_p5_result`=0, 
        
        `guestteam_id`=0, 
        `guestteam_count`=1, 
        `guestteam_result`=0, 
        `guestteam_points`=0, 
        `t2_p1_number`=NULL, 
        `t2_p1_result`=0, 
        `t2_p2_number`=NULL, 
        `t2_p2_result`=0, 
        `t2_p3_number`=NULL, 
        `t2_p3_result`=0, 
        `t2_p4_number`=NULL, 
        `t2_p4_result`=0, 
        `t2_p5_number`=NULL, 
        `t2_p5_result`=0, 
        
        `season`='2016 / 2017', 
        `change_date`='2016-11-16 22:47:54', 
        `usr_id`=20, 
        `creation_date`='2016-10-10 09:10:42', 
        `creation_usr_id`=26, 
        `t1_p1_i_zehner`=0, 
        `t1_p2_i_zehner`=0, 
        `t1_p3_i_zehner`=0, 
        `t1_p4_i_zehner`=0, 
        `t1_p5_i_zehner`=0, 
        `t2_p1_i_zehner`=0, 
        `t2_p2_i_zehner`=0, 
        `t2_p3_i_zehner`=0, 
        `t2_p4_i_zehner`=0, 
        `t2_p5_i_zehner`=0, 
        `season_state`='W', 
        `discipline`=NULL 
        
        WHERE `result_id`=9;

        $stmt=$db->prepare($sql);
        
        
        $stmt->bindParam(':changeDate', $this->changeDate);
        $stmt->bindParam(':userId', $this->userId);
       
        $stmt->execute();
        Database::disconnect();
    }
    public function create(){
        // not Implemented;
    }
    public static function delete($id){
        // not Implemented;
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::COLUMN_ID.' = :id';
        $stmt=$db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);


        Database::disconnect();

        $obj =  self::dataToObject($data);

        return $obj;
    }

    public static function dataToObject($obj){
        return new TeamResults(
            $obj[self::COLUMN_ID],
            $obj[self::COLUMN_ROUNDID],
            $obj[self::COLUMN_HOMETEAMID],
            $obj[self::COLUMN_HOMETEAMCOUNT],
            $obj[self::COLUMN_HOMETEAMRESULT],
            $obj[self::COLUMN_HOMETEAMPOINTS],
            $obj[self::COLUMN_T1P1NUMBER],
            $obj[self::COLUMN_T1P1RESULT],
            $obj[self::COLUMN_T1P1ITEN],
            $obj[self::COLUMN_T1P2NUMBER],
            $obj[self::COLUMN_T1P2RESULT],
            $obj[self::COLUMN_T1P2ITEN],
            $obj[self::COLUMN_T1P3NUMBER],
            $obj[self::COLUMN_T1P3RESULT],
            $obj[self::COLUMN_T1P3ITEN],
            $obj[self::COLUMN_T1P4NUMBER],
            $obj[self::COLUMN_T1P4RESULT],
            $obj[self::COLUMN_T1P4ITEN],
            $obj[self::COLUMN_T1P5NUMBER],
            $obj[self::COLUMN_T1P5RESULT],
            $obj[self::COLUMN_T1P5ITEN],
            $obj[self::COLUMN_GUESTTEAMID],
            $obj[self::COLUMN_GUESTEAMCOUNT],
            $obj[self::COLUMN_GUESTEAMRESULT],
            $obj[self::COLUMN_GUESTEAMPOINTS],
            $obj[self::COLUMN_T2P1NUMBER],
            $obj[self::COLUMN_T2P1RESULT],
            $obj[self::COLUMN_T2P1ITEN],
            $obj[self::COLUMN_T2P2NUMBER],
            $obj[self::COLUMN_T2P2RESULT],
            $obj[self::COLUMN_T2P2ITEN],
            $obj[self::COLUMN_T2P3NUMBER],
            $obj[self::COLUMN_T2P3RESULT],
            $obj[self::COLUMN_T2P3ITEN],
            $obj[self::COLUMN_T2P4NUMBER],
            $obj[self::COLUMN_T2P4RESULT],
            $obj[self::COLUMN_T2P4ITEN],
            $obj[self::COLUMN_T2P5NUMBER],
            $obj[self::COLUMN_T2P5RESULT],
            $obj[self::COLUMN_T2P5ITEN],
            $obj[self::COLUMN_SEASON],
            $obj[self::COLUMN_CHANGEDATE],
            $obj[self::COLUMN_USERID],
            $obj[self::COLUMN_CREATIONDATE],
            $obj[self::COLUMN_CREATIONUSERID],
            $obj[self::COLUMN_SEASIONSTATE],
            $obj[self::COLUMN_DISCIPLINE]
        );
    }

    public function setData($homeTeamShooters, $homeTeamResults,  $guastTeamShooters, $guastTeamResults){
        for ($i = 0; $i < count($homeTeamShooters); $i++) {
            $this->setHomeTeamShooter($i+1, $homeTeamShooters[$i]);
            $this->setHomeTeamResult($i+1, $homeTeamResults[$i]);
        }

        for ($i = 0; $i < count($guastTeamShooters); $i++) {
            $this->setGuestTeamShooter($i+1, $guastTeamShooters[$i]);
            $this->setGuestTeamResult($i+1, $guastTeamResults[$i]);
        }
        //var_dump($this);
    }   

    private function setHomeTeamShooter($snumber, $shooterNr){
        switch ($snumber) {
            case 1:
                $this->t1P1Number = $shooterNr;
                break;
            case 2:
                $this->t1P2Number = $shooterNr;
                break;
            case 3:
                $this->t1P3Number = $shooterNr;
                break;
            case 4:
                $this->t1P4Number = $shooterNr;
                break;
            case 5:
                $this->t1P5Number = $shooterNr;
                break;
            default:
                # code...
                break;
        } 
    }

    private function setHomeTeamResult($snumber, $result){
        switch ($snumber) {
            case 1:
                $this->t1P1Result = $result;
                break;
            case 2:
                $this->t1P2Result = $result;
                break;
            case 3:
                $this->t1P3Result = $result;
                break;
            case 4:
                $this->t1P4Result = $result;
                break;
            case 5:
                $this->t1P5Result = $result;
                break;
            default:
                # code...
                break;
        } 
    }

    private function setGuestTeamShooter($snumber, $shooterNr){
        switch ($snumber) {
            case 1:
                $this->t2P1Number = $shooterNr;
                break;
            case 2:
                $this->t2P2Number = $shooterNr;
                break;
            case 3:
                $this->t2P3Number = $shooterNr;
                break;
            case 4:
                $this->t2P4Number = $shooterNr;
                break;
            case 5:
                $this->t2P5Number = $shooterNr;
                break;
            default:
                # code...
                break;
        } 
    }

    private function setGuestTeamResult($snumber, $result){
        switch ($snumber) {
            case 1:
                $this->t2P1Result = $result;
                break;
            case 2:
                $this->t2P2Result = $result;
                break;
            case 3:
                $this->t2P3Result = $result;
                break;
            case 4:
                $this->t2P4Result = $result;
                break;
            case 5:
                $this->t2P5Result = $result;
                break;
            default:
                # code...
                break;
        } 
    }



    public function getShooterNr($index, $teamId){
        if($teamId == $this->homeTeamId){
            $ids = [$this->t1P1Number, $this->t1P2Number, $this->t1P3Number, $this->t1P4Number, $this->t1P5Number];
        }
        else{
            $ids = [$this->t2P1Number, $this->t2P2Number, $this->t2P3Number, $this->t2P4Number, $this->t2P5Number];
        }
        return $ids[$index -1];
    }

    public function getShooterResult($index, $teamId){
        if($teamId == $this->homeTeamId){
            $ids = [$this->t1P1Result, $this->t1P2Result, $this->t1P3Result, $this->t1P4Result, $this->t1P5Result];
        }
        else{
            $ids = [$this->t2P1Result, $this->t2P2Result, $this->t2P3Result, $this->t2P4Result, $this->t2P5Result];
        }
        return $ids[$index -1];
    }

    public function getTeamResult($teamId){
        if($teamId == $this->homeTeamId){
            return $this->homeTeamResult;
        }
        else{
            return $this->guestTeamResult;
        }        
    }

    public function validate(){
        return false;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getHomeTeamId()
    {
        return $this->homeTeamId;
    }

    /**
     * @param mixed $homeTeamId
     */
    public function setHomeTeamId($homeTeamId)
    {
        $this->homeTeamId = $homeTeamId;
    }

    
    /**
     * @return mixed
     */
    public function getGuestTeamId()
    {
        return $this->guestTeamId;
    }

    /**
     * @param mixed $guestTeamId
     */
    public function setGuestTeamId($guestTeamId)
    {
        $this->guestTeamId = $guestTeamId;
    }

    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }




}