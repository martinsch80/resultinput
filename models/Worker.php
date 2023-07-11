<?php

/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-16
 * Time: 11:31
 */

require_once "models/DatabaseService.php";

class Worker implements DatabaseService
{
    private $id;
    private $name;
    private $email;
    private $password;

    private $projectId;

    private $errors;


    public function __construct($id, $name, $email, $password, $projectId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;

        $this->projectId = $projectId;
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
        if(Worker::getByEmail($this->getEmail()))
        {
            if(strcasecmp($this->getEmail(),Worker::getByEmail($this->getEmail())[0]->getEmail()) == 0)
            {
                if(strcasecmp($this->getPassword(), Worker::getByEmail($this->getEmail())[0]->getPassword()) == 0)
                {

                    return true;
                }

                $this->errors['login'] = "Zugang verweigert!";
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
            $worker = Worker::getCredentialsFromSession();
            if ($worker->checkCredentials())
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

    public static function getByEmail($email)
    {
        $matchesByEmail = [];

        $db = Database::connect();
        $sql = 'SELECT email, passwort FROM tbl_worker WHERE email = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $obj)
        {
            $matchesByEmail[] = new Worker(null, "", $obj['email'], $obj['passwort'], null);
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
        return $this->validateEmail() & $this->validatePassword() & $this->checkCredentials();
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
            
            $_SESSION['user'] = $userForSession;
            return true;
        }
    }
    public static function getCredentialsFromSession()
    {
        if(isset($_SESSION['user']))
        {
            $userFromSession = unserialize($_SESSION['user']);
            return new Worker(null, "", $userFromSession->getEmail(), $userFromSession->getPassword(), null);
        }
        else
        {
            return false;
        }

    }

    public static function deleteCredentialsFromSession()
    {
        unset($_SESSION['user']);
    }

    public function update()
    {
        $db = Database::connect();
        $sql = 'UPDATE tbl_worker SET name = ?, email = ?, passwort = ?, p_id = ? WHERE w_id = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->getName(), $this->getEmail(), $this->getPassword(), $this->getProjectId(), $this->getId()));
        Database::disconnect();
    }

    public static function updateProject($id, $pid)
    {
        $db = Database::connect();
        $sql = 'UPDATE tbl_worker SET p_id = ? WHERE w_id = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($pid, $id));
        Database::disconnect();
    }

    public function create()
    {
        // TODO: Implement create() method.
        $db = Database::connect();
        $sql = 'INSERT INTO tbl_worker (name, email, passwort, p_id) values (?,?,?,?)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->getName(), $this->getEmail(), $this->getPassword(), $this->getProjectId()));

        $id =$db->lastInsertId();

        Database::disconnect();

        return $id;
    }

    public static function delete($id)
    {
        // TODO: Implement delete() method.
        $db = Database::connect();
        $sql = 'DELETE FROM tbl_worker WHERE w_id=?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        Database::disconnect();
    }

    public static function deleteRefToProject($id)
    {

        $db = Database::connect();
        $sql = "UPDATE tbl_worker SET p_id=? WHERE w_id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(NULL, $id));
        Database::disconnect();
    }

    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $workers = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM tbl_worker ORDER BY name ASC';
        $stmt=$db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Database::disconnect();

        foreach ($data as $obj)
        {
            $workers[] = new Worker($obj['w_id'], $obj['name'], $obj['email'], $obj['passwort'], $obj['p_id']);
        }

        return $workers;
    }

    public static function getAllWithoutProject()
    {
        // TODO: Implement getAll() method.
        $workers = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM tbl_worker WHERE p_id is ?';
        $stmt=$db->prepare($sql);
        $stmt->execute(array(NULL));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Database::disconnect();

        foreach ($data as $obj)
        {
            $workers[] = new Worker($obj['w_id'], $obj['name'], $obj['email'], $obj['passwort'], $obj['p_id']);
        }

        return $workers;
    }
    public static function getAllInProject($pid)
    {
        // TODO: Implement getAll() method.
        $workers = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM tbl_worker WHERE p_id=?';
        $stmt=$db->prepare($sql);
        $stmt->execute(array($pid));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Database::disconnect();

        foreach ($data as $obj)
        {
            $workers[] = new Worker($obj['w_id'], $obj['name'], $obj['email'], $obj['passwort'], $obj['p_id']);
        }

        return $workers;
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $db = Database::connect();
        $sql = 'SELECT * FROM tbl_worker WHERE w_id = ?';
        $stmt=$db->prepare($sql);
        $stmt->execute(array($id));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        Database::disconnect();

        $worker = new Worker($data['w_id'], $data['name'], $data['email'], $data['passwort'], $data['p_id']);

        return $worker;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
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