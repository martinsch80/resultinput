<?php
/**
 * Created by schranzli.
 * User: Martin.
 * Date: 2023-07-11
 * Time: 20:25
 */

class Team implements DatabaseService
{
    const TABLE_NAME = "tlsb_team";
    const COLUMN_ID = "team_id";
    const COLUMN_NAME = "team_name";
    const COLUMN_CLASS = "team_class";
    const COLUMN_CODE = "code";
    const COLUMN_DISCIPLINE = "discipline";
    const COLUMN_ACTIVE = "active";
    const COLUMN_SEASON_STATE = "season_state";

    private $id;
    private $name;
    private $class;
    private $code;
    private $discipline;
    private $active;
    private $seasonState;

    private $errors;

    public function __construct($id, $name, $class, $code, $discipline, $active, $seasonState)
    {
        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
        $this->code = $code;
        $this->discipline = $discipline;
        $this->active = $active;
        $this->seasonState = $seasonState;

        $this->errors = [];
    }


   
    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' ORDER BY '.self::COLUMN_NAME.' ASC';
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

    public static function getByDisciplineAndCode($discipline, $code)
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '. self::COLUMN_DISCIPLINE .' = :discipline AND '. self::COLUMN_CODE .' = :code AND '. self::COLUMN_ACTIVE .' = 1 ORDER BY '.self::COLUMN_NAME.' ASC';
        $stmt=$db->prepare($sql);
        $stmt->bindParam(':discipline', $discipline);
        $stmt->bindParam(':code', $code);
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
        return new Team(
            $obj[self::COLUMN_ID], 
            $obj[self::COLUMN_NAME], 
            $obj[self::COLUMN_CLASS], 
            $obj[self::COLUMN_CODE],
            $obj[self::COLUMN_DISCIPLINE],
            $obj[self::COLUMN_ACTIVE],
            $obj[self::COLUMN_SEASON_STATE]
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
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

        /**
     * @return mixed
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * @param mixed $discipline
     */
    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;
    }

        /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getSeasonState()
    {
        return $this->seasonState;
    }

    /**
     * @param mixed $seasonState
     */
    public function setSeasonState($seasonState)
    {
        $this->seasonState = $seasonState;
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