<?php

/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-16
 * Time: 11:31
 */

require_once "models/DatabaseService.php";

class User implements DatabaseService
{
    const TABLE_NAME = "tlsb_usr";
    const COLUMN_ID = "usr_id";
    const COLUMN_NAME = "usr_guild";
    const COLUMN_PASSWORD = "usr_password";
    const COLUMN_RIGHT = "usr_right";
    const COLUMN_CODE = "usr_code";
    const COLUMN_ACTIVE = "active";
    const COLUMN_STATE = 'usr_state';

    private $id;
    private $name;
    private $password;
    private $right;
    private $usrCode;
    private $active;
    private $usrState;

    private $errors;

    public function __construct()
    {
        $this->id = null;
        $this->name = "";
        $this->password = "";
        $this->right = null;
        $this->usrCode = null;
        $this->active = null;
        $this->usrState = null;
        $this->errors = [];
    }

    public function save()
    {
        if($this->getId() != null && $this->getId() > 0)
        {
            $this->update();
        }
        else {
            $this->id = $this->create();
        }
    }
    public function checkCredentials() //for Login
    {
        $dbUsers = User::getByUserName($this->getName());
        if($dbUsers)
        {
            if(strcasecmp($this->getName(),$dbUsers[0]->getName()) == 0)
            {
                if(strcasecmp($this->getPassword(), $dbUsers[0]->getPassword()) == 0)
                {
                    $this->setId($dbUsers[0]->getId());
                    return true;
                }

                $this->errors['login'] = "Zugang verweigert! Passwort Falsch";
                return false;
            }

            $this->errors['login'] = "Zugang verweigert!";
            return false;
        }
        $this->errors['login'] = "Zugang verweigert!";
        return false;
    }

    public static function isLoggedIn()
    {
        if(isset($_SESSION['user'])) {
            $user = User::getCredentialsFromSession();
            if ($user->getId() == $_SESSION['user'])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }

    public static function getByUserName($usrName)
    {
        $matchesByEmail = [];

        $db = Database::connect();
        $sql = 'SELECT '. self::COLUMN_ID .','. self::COLUMN_NAME .', '.self::COLUMN_PASSWORD.' FROM '.self::TABLE_NAME.' WHERE '. self::COLUMN_NAME .' = :name AND '.self::COLUMN_ACTIVE.'=1';
        $stmt = $db->prepare($sql);        
        $stmt->bindParam(':name', $usrName);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $obj)
        {
            $user = new User();
            $user->setId($obj[self::COLUMN_ID]);
            $user->setName($obj[self::COLUMN_NAME]);
            $user->setPassword($obj[self::COLUMN_PASSWORD]);
            $matchesByEmail[] = $user;
        }
        return $matchesByEmail;
    }


    public function validateNameEmail()
    {
        return $this->validateEmail() & $this->validateName();
    }


    public function validateNameEmailOptionalPassword()
    {
        return $this->validateNameEmail() & $this->validateOptionalPassword();
    }

    public function validateName()
    {
        if(empty($this->name))
        {
            $this->errors['name'] = "Name darf nicht leer sein!";
            return false;
        }
        if(!preg_match("/^[a-zA-Z ]*$/", $this->name))
        {
            $this->errors['name'] = "Name darf nur Zeichen von oder A-Z oder Leerzeichen bestehen";
            return false;
        }
        else
        {
            return true;
        }
    }

    public function validate()
    {
        return $this->validatePassword() & $this->checkCredentials();
    }

    public function validatePassword()
    {
        if(empty($this->password))
        {
            $this->errors['password'] = "Passwort darf nicht leer sein!";
            return false;
        }
        if(!preg_match("/^[a-zA-Z0-9 ]*$/", $this->password))
        {
            $this->errors['password'] = "Passwort darf nur Zeichen von 0-9 oder A-Z enthalten";
            return false;
        }
        else
        {
            return true;
        }
    }

    public function validateOptionalPassword()
    {
return true;
    }

    public function validateEmail()
    {
        if(empty($this->email))
        {
            $this->errors['email'] = "Email darf nicht leer sein!";
            return false;
        }
        if(!preg_match("/^[^@]+@[^\.]+\..+$/", $this->email))
        {
            $this->errors['email'] = "Ihre Eingabe entspricht keiner Email Adresse";
            return false;
        }
        else
        {
            return true;
        }
    }
    public function saveCredentialsToSession()
    {
        if(isset($_SESSION['user']))
        {
            $this->errors['alreadyLogged'] = "Sie sind bereits eingeloggt!";
            return false;
        }
        else
        {
            $userForSession = serialize($this);
            
            $_SESSION['user'] =$this->getId();
            return true;
        }
    }
    public static function getCredentialsFromSession()
    {
        if(isset($_SESSION['user']))
        {
            $user = self::get($_SESSION['user']);
            return $user;
        }
        else
        {
            return false;
        }

    }

    public static function deleteCredentialsFromSession()
    {
        unset($_SESSION['user']);
        unset($_SESSION['saison']);
        unset($_SESSION['verein']);
    }

    public function update()
    {
        $db = Database::connect();
        $sql = 'UPDATE '. self::TABLE_NAME .' SET '. self::COLUMN_NAME.' = :name, '.self::COLUMN_PASSWORD.' = :password WHERE '. self::COLUMN_ID .' = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $this->getId());
        $stmt->bindParam(':name', $this->getName());
        $stmt->bindParam(':password', $this->getPassword());
        $stmt->execute();
        Database::disconnect();
    }

   

    public function create()
    {
        // TODO: Implement create() method.
        $db = Database::connect();
        $sql = 'INSERT INTO '. self::TABLE_NAME .' (name, passwort) values (:name, :password)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $this->getName());
        $stmt->bindParam(':password', $this->getPassword());
        $stmt->execute();

        $id =$db->lastInsertId();

        Database::disconnect();

        return $id;
    }

    public static function delete($id)
    {
        // TODO: Implement delete() method.
        $db = Database::connect();
        $sql = 'DELETE FROM '. self::TABLE_NAME .' WHERE '. self::COLUMN_ID .'= :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        Database::disconnect();
    }


    public static function getAll()
    {
        $users = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM '. self::TABLE_NAME .' ORDER BY '.self::COLUMN_NAME.' ASC';
        $stmt=$db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Database::disconnect();

        foreach ($data as $obj)
        {
            $users[] = self::creatObj($obj);
        }

        return $users;
    }

    private static function createObj($obj){
        $user = new User();
        $user->setId($obj[self::COLUMN_ID]);
        $user->setName($obj[self::COLUMN_NAME]);       
        $user->setRight($obj[self::COLUMN_RIGHT]);
        $user->setUsrCode($obj[self::COLUMN_CODE]);
        $user->setActive($obj[self::COLUMN_ACTIVE]);
        $user->setUsrState($obj[self::COLUMN_STATE]);
        return $user;
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $db = Database::connect();
        $sql = 'SELECT * FROM '. self::TABLE_NAME .' WHERE '. self::COLUMN_ID .' = :id';
        $stmt=$db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        Database::disconnect();

        $user = self::createObj($data);

        return $user;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $password
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

      /**
     * @return mixed
     */
    public function getRightAsString()
    {        
        switch ($this->right) {
            case 0:
                $rightasString = "Mannschaftsführer";
                break;
            case 1:
                $rightasString = "Bezirkssportleiter";
                break;
            default:
                $rightasString = "Administrator";
                break;
        }
        return $rightasString;
    }

    /**
     * @return mixed
     */
    public function getUsrCode()
    {
        return $this->usrCode;
    }

    /**
     * @param mixed $usrCode
     */
    public function setUsrCode($usrCode)
    {
        $this->usrCode = $usrCode;
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
    public function getUsrState()
    {
        return $this->usrState;
    }

    /**
     * @param mixed $usrState
     */
    public function setUsrState($usrState)
    {
        $this->usrState = $usrState;
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