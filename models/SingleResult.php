<?php
/**
 * Created by schranzli.
 * User: Martin.
 * Date: 2023-07-11
 * Time: 20:25
 */

class SingelResult implements DatabaseService
{
    const TABLE_NAME = "tlsb_single_result";
    const COLUMN_ID = "result_id";
    const COLUMN_ROUNDID = "round_id";
    const COLUMN_HOBBY = "hobby";
    const COLUMN_NUMBER = "p_number";
    const COLUMN_RESULT = "p_result";
    const COLUMN_ITEN = "p_i_zehner";
    const COLUMN_SEASON = "season";
    const COLUMN_CHANGEDATE = "change_date";
    const COLUMN_USERID = "usr_id";
    const COLUMN_SEASIONSTATE = "season_state";
    const COLUMN_DISCIPLINE = "discipline";

    

    private $id;
    private $roundId;
    private $hobby;
    private $number;
    private $result;
    private $iTen;
    private $season;
    private $changeDate;
    private $userId;
    private $seasonState;
    private $discipline;

    private $errors;

    public function __construct( $id, $roundId, 
            $hobby, $number, $result, $iTen,
            $season, $changeDate, $userId, $seasonState, $discipline
    )
    {
        $this->id = $id;
        $this->roundId = $roundId;
        $this->hobby = $hobby;
        $this->number = $number;
        $this->result = $result;
        $this->iTen = $iTen;
        $this->season = $season;
        $this->changeDate = $changeDate;
        $this->userId = $userId;
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

    public static function getBySeasonAndRoundIdAndUserCode($season, $roundId, $userCode)
    {
        // TODO: Implement getAll() method.
        $list = [];
        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::COLUMN_SEASON.' = :season AND '.self::COLUMN_ROUNDID.' = :roundId AND '.self::COLUMN_NUMBER.' like :userCode ORDER BY '.self::COLUMN_RESULT.' DESC';
        $stmt=$db->prepare($sql);
        
        $stmt->bindParam(':season', $season);
        $stmt->bindParam(':roundId', $roundId);
        $userCode = "$userCode%";
        $stmt->bindParam(':userCode', $userCode);
        
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
        return new SingelResult(
            $obj[self::COLUMN_ID],
            $obj[self::COLUMN_ROUNDID],
            $obj[self::COLUMN_HOBBY],
            $obj[self::COLUMN_NUMBER],
            $obj[self::COLUMN_RESULT],
            $obj[self::COLUMN_ITEN],
            $obj[self::COLUMN_SEASON],
            $obj[self::COLUMN_CHANGEDATE],
            $obj[self::COLUMN_USERID],
            $obj[self::COLUMN_SEASIONSTATE],
            $obj[self::COLUMN_DISCIPLINE]
        );
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
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $id
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
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