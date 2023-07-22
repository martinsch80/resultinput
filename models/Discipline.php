<?php
/**
 * Created by schranzli.
 * User: Martin.
 * Date: 2023-07-11
 * Time: 20:25
 */

class Discipline implements DatabaseService
{
    const TABLE_NAME = "tlsb_discipline";
    const COLUMN_ID = "disc_id";
    const COLUMN_NAME = "disc_name";
    const COLUMN_WEAPON = "weapon";
    const COLUMN_SEASON = "season_state";
    const COLUMN_PSIZE = "s_t";
    const COLUMN_RESULT_RANGE = "result_range";
    const COLUMN_ZEHNTEL = "zehntel";

    private $id;
    private $name;
    private $weapon;
    private $season;
    private $psize;
    private $resultRange;
    private $ziroOne;

    private $errors;

    public function __construct($id, $name, $weapon, $season, $psize, $resultRange, $ziroOne)
    {
        $this->id = $id;
        $this->name = $name;
        $this->weapon = $weapon;
        $this->season = $season;
        $this->psize = $psize;
        $this->resultRange = $resultRange;
        $this->ziroOne = $ziroOne;

        $this->errors = [];
    }


   
    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' ORDER BY '.self::COLUMN_SEASON.' ASC';
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

    public static function getBySeason($season)
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '.self::COLUMN_SEASON.' = :season AND active = 1 ORDER BY position ASC, '.self::COLUMN_SEASON.' ASC';
        $stmt=$db->prepare($sql);
        $stmt->bindParam(':season', $season);
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
        return new Discipline(
            $obj[self::COLUMN_ID], 
            $obj[self::COLUMN_NAME], 
            $obj[self::COLUMN_WEAPON], 
            $obj[self::COLUMN_SEASON],
            $obj[self::COLUMN_PSIZE],
            $obj[self::COLUMN_RESULT_RANGE],
            $obj[self::COLUMN_ZEHNTEL]
         );
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param mixed $season
     */
    public function setSeason($season)
    {
        $this->season = $season;
    }

    /**
     * @return mixed
     */
    public function getWeapon()
    {
        return $this->weapon;
    }

    /**
     * @param mixed $weapon
     */
    public function setWeapon($weapon)
    {
        $this->weapon = $weapon;
    }

    /**
     * @return mixed
     */
    public function getPsize()
    {
        return $this->psize;
    }

    /**
     * @param mixed $psize
     */
    public function setPsize($psize)
    {
        $this->psize = $psize;
    }

     /**
     * @return mixed
     */
    public function getResultRange()
    {
        return $this->resultRange;
    }

    /**
     * @param mixed $resultRange
     */
    public function setResultRange($resultRange)
    {
        $this->resultRange = $resultRange;
    }

     /**
     * @return mixed
     */
    public function getZiroOne()
    {
        return $this->ziroOne;
    }

    /**
     * @param mixed $ziroOne
     */
    public function setZiroOne($ziroOne)
    {
        $this->ziroOne = $ziroOne;
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