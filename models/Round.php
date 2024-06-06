<?php
/**
 * Created by schranzli.
 * User: Martin.
 * Date: 2023-07-11
 * Time: 20:25
 */

class Round implements DatabaseService
{
    const TABLE_NAME = "tlsb_round";
    const COLUMN_ID = "round_id";
    const COLUMN_ROUND = "round";
    const COLUMN_START = "start";
    const COLUMN_STOP = "stop";
    const COLUMN_DISCIPLINE = "discipline";
    const COLUMN_ACTIVE = "active";
    const COLUMN_DISTRICT = "district";
    const COLUMN_USEHOBBY = "use_hobby";

    private $id;
    private $round;
    private $start;
    private $stop;
    private $discipline;
    private $active;
    private $district;
    private $useHobby;

    private $errors;

    public function __construct($id, $round, $start, $stop,
        $discipline, $active, $district, $useHobby)
    {
        $this->id = $id;
        $this->round = $round;
        $this->start = $start;
        $this->stop = $stop;
        $this->discipline = $discipline;
        $this->active = $active;
        $this->district = $district;
        $this->useHobby = $useHobby;

        $this->errors = [];
    }


   
    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' ORDER BY '.self::COLUMN_ROUND.' ASC';
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

    public static function getAllByDistrictAndDiscipline($district, $discipline)
    {
        // TODO: Implement getAll() method.
        $list = [];

        $districts = $district;
        if($discipline->getDistricts() != null) $districts = $discipline->getDistricts();
       
        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '. self::COLUMN_DISTRICT.' in ('.$districts.') AND '. self::COLUMN_DISCIPLINE.' = :disciplineId AND active = 1 ORDER BY '.self::COLUMN_ROUND.' ASC';
        $stmt=$db->prepare($sql);
        $stmt->bindParam(':disciplineId', $discipline->getId());
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
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::COLUMN_ID.' = ?';
        $stmt=$db->prepare($sql);
        $stmt->execute(array($id));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);


        Database::disconnect();

        $obj =  self::dataToObject($data);

        return $obj;
    }

    public static function dataToObject($obj){
        return new ROUND($obj[self::COLUMN_ID], $obj[self::COLUMN_ROUND], $obj[self::COLUMN_START], $obj[self::COLUMN_STOP],
            $obj[self::COLUMN_DISCIPLINE], $obj[self::COLUMN_ACTIVE], $obj[self::COLUMN_DISTRICT], $obj[self::COLUMN_USEHOBBY] );
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
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @param mixed $round
     */
    public function setRound($round)
    {
        $this->round = $round;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $season
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * @param mixed $weapon
     */
    public function setStop($stop)
    {
        $this->stop = $stop;
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
