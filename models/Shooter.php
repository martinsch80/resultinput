<?php
/**
 * Created by schranzli.
 * User: Martin.
 * Date: 2023-07-11
 * Time: 20:25
 */

class Shooter implements DatabaseService
{
    const TABLE_NAME = "tlsb_shooter";
    const COLUMN_ID = "id";
    const COLUMN_NAME = "name";
    const COLUMN_PASSNR = "pass_nr";
    const COLUMN_VEREIN = "verein";
    const COLUMN_GENDER = "geschlecht";
    const COLUMN_BIRTHDATE = "geburtsdatum";
    const COLUMN_SIGNIN = 'sign_in';
    const COLUMN_LASTUPDATE = 'last_update';

    private $id;
    private $name;
    private $passNr;
    private $verein;
    private $gender;
    private $birthdate;
    private $armbrust;
    private $gk;
    private $feuerstutzen;
    private $feuerpistole;
    private $fp;
    private $kk;
    private $lg;
    private $lp;
    private $lp5;
    private $vorderlader;
    private $zimmerstutzen;
    private $zf;
    private $signIn;
    private $lastEdit;

    private $errors;

    public function __construct($id, $name, $passNr, $verein, $gender, $birthdate, 
        $armbrust, $gk, $feuerstutzen, $feuerpistole, $fp, $kk, $lg, $lp, $lp5, $vorderlader,
        $zimmerstutzen, $zf, $signIn, $lastEdit)
    {
        $this->id = $id;
        $this->name = $name;
        $this->passNr = $passNr;
        $this->verein = $verein;
        $this->gender = $gender;
        $this->birthdate = $birthdate;
        $this->armbrust = $armbrust;
        $this->gk = $gk;
        $this->feuerstutzen = $feuerstutzen;
        $this->feuerpistole = $feuerpistole;
        $this->fp = $fp;
        $this->kk = $kk;
        $this->lg = $lg;
        $this->lp = $lp;
        $this->lp5 = $lp5;
        $this->vorderlader = $vorderlader;
        $this->zimmerstutzen = $zimmerstutzen;
        $this->zf = $zf;
        $this->signIn = $signIn;
        $this->lastEdit = $lastEdit;

        $this->errors = [];
    }


   
    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' ORDER BY name ASC';
        $stmt=$db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$dataForDuration = Project::getDuration();

        Database::disconnect();

        foreach ($data as $obj) {
            $list[] = Shooter::dataToObject($obj);
        }

        return $list;
    }

    public static function getAllByPassNr($passNr){
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '. self::COLUMN_PASSNR.' like :passNr ORDER BY name ASC';
        $stmt=$db->prepare($sql);
        $passNr = "$passNr%";
        $stmt->bindParam(':passNr', $passNr);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$dataForDuration = Project::getDuration();

        Database::disconnect();

        foreach ($data as $obj) {
            $list[] = self::dataToObject($obj);
        }

        return $list;
    }

    public static function getAllByPassNrAnd($passNr,){
        $list = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '.self::TABLE_NAME.' WHERE '. self::COLUMN_PASSNR.' like :passNr ORDER BY name ASC';
        $stmt=$db->prepare($sql);
        $passNr = "$passNr%";
        $stmt->bindParam(':passNr', $passNr);
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
        return new Shooter($obj[self::COLUMN_ID], $obj[self::COLUMN_NAME], $obj[self::COLUMN_PASSNR], 
        $obj[self::COLUMN_VEREIN], $obj[self::COLUMN_GENDER], $obj[self::COLUMN_BIRTHDATE], $obj['Armbrust'], 
        $obj['GK'], $obj['Feuerstutzen'], $obj['Feuerpistole'], $obj['FP'],
        $obj['KK'], $obj['LG'], $obj['LP'] , $obj['LP5'], $obj['Feuerpistole'], 
        $obj['Zimmerstutzen'], $obj['ZF'], $obj[self::COLUMN_SIGNIN], $obj[self::COLUMN_LASTUPDATE]);
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
    public function getPassNr()
    {
        return $this->passNr;
    }

    /**
     * @param mixed $passNr
     */
    public function setPassNr($passNr)
    {
        $this->passNr = $passNr;
    }

    /**
     * @return mixed
     */
    public function getVerein()
    {
        return $this->verein;
    }

    /**
     * @param mixed $verein
     */
    public function setVerein($verein)
    {
        $this->verein = $verein;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
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