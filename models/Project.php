<?php
/**
 * Created by PhpStorm.
 * User: paddy.
 * Date: 2019-01-16
 * Time: 11:25
 */

class Project implements DatabaseService
{
    private $id;
    private $title;
    private $kickoff;

    private $duration;

    private $errors;

    public function __construct($id, $title, $kickoff, $duration)
    {
        $this->id = $id;
        $this->title = $title;
        $this->kickoff = $kickoff;

        $this->duration = $duration;

        $this->errors = [];
    }


    public function save()
    {
        if($this->validate())
        {
            if($this->getId() != null && $this->getId() > 0)
            {
                $this->update();
            }
            else
            {
                $this->id = $this->create();
            }
        }
    }
    public function validate()
    {
        return $this->validateTitle() & $this->validateKickoff();
    }

    public function validateTitle()
    {
        if(empty($this->title))
        {
            $this->errors['title'] = "Titel darf nicht leer sein!";
            return false;
        }
        if(!preg_match("/^[a-zA-Z0-9 ]*$/", $this->title))
        {
            $this->errors['title'] = "Titel darf nur Zeichen von 0-9 oder A-Z enthalten";
            return false;
        }
        else
        {
            return true;
        }
    }
    public function validateKickoff()
    {
        if(empty($this->kickoff))
        {
            $this->errors['kickoff'] = "Kick-Off Termin darf nicht leer sein!";
            return false;
        }
        else
        {
            return true;
        }
    }


    public function update() //TODO
    {
        $db = Database::connect();
        $sql = 'UPDATE tbl_project SET titel = ?, kickoff = ?, p_id = ? WHERE p_id = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->getTitle(), $this->getKickoff(), $this->getId(), $this->getId()));
        Database::disconnect();
    }

    public function create()//TODO
    {
        // TODO: Implement create() method.
        $db = Database::connect();
        $sql = 'INSERT INTO tbl_project (titel, kickoff) values (?,?)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->getTitle(), $this->getKickoff()));

        $id =$db->lastInsertId();
        Database::disconnect();

        return $id;
    }

    public static function delete($id)
    {
        // TODO: Implement delete() method.
        $db = Database::connect();

        Project::deleteRefsBeforeDelete($id);

        $sql = 'DELETE FROM tbl_project WHERE p_id=?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        Database::disconnect();
    }

    public static function deleteRefsBeforeDelete($id) //METHOD FOR DELETE
    {
        $db = Database::connect();
        $sql = 'UPDATE tbl_activity SET p_id= ? WHERE p_id=?';
        $stmt = $db->prepare($sql);
        $stmt->execute(array(null, $id));

        $sql = 'UPDATE tbl_worker SET p_id=? WHERE p_id=?';
        $stmt=$db->prepare($sql);
        $stmt->execute(array(null, $id));
        Database::disconnect();
    }

    public static function getAll()
    {
        // TODO: Implement getAll() method.
        $projects = [];

        $db = Database::connect();
        $sql = 'SELECT * FROM tbl_project ORDER BY titel ASC';
        $stmt=$db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //$dataForDuration = Project::getDuration();

        Database::disconnect();

        foreach ($data as $obj) {
            $projects[] = new Project($obj['p_id'], $obj['titel'], $obj['kickoff'], Project::getDuration($obj['p_id']));
        }

        return $projects;
    }

    public function getWorkersProject()
    {
        $workers = [];

        $db = Database::connect();
        $sql = "SELECT w.w_id, p.p_id, w.name, w.email FROM tbl_worker w inner join tbl_project p on p.p_id=w.p_id where p.p_id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->getId()));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $obj)
        {
            $workers[] = new Worker($obj['w_id'], $obj['name'],$obj['email'], null, $obj['p_id']);
        }
        //print_r($workers);
        return $workers;

        Database::disconnect();

    }


    public function getActivityProject() //TODO get the WORKER with JOIN
    {
        $activitys = [];

        $db = Database::connect();
        $sql = "SELECT a.a_id, a.dauer, a.datum, a.beschreibung, p.p_id, a.w_id from tbl_activity a inner join tbl_project p on p.p_id=a.p_id WHERE p.p_id=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($this->getId()));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($data as $obj)
        {
            $activitys[] = new Activity($obj['a_id'], $obj['datum'], $obj['dauer'], $obj['beschreibung'], $obj['w_id'], $obj['p_id']);
        }
        //print_r($workers);
        return $activitys;


        Database::disconnect();

    }

    public static function getDuration($id) //TODO SHOW SUM OF TIME
    {
        $db = Database::connect();
        $sql = "SELECT sum(a.dauer) as dauer FROM tbl_project p INNER JOIN tbl_activity a ON p.p_id = a.p_id WHERE p.p_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        $data = $stmt->fetch();

        return Project::parseDuration($data['dauer']);

        Database::disconnect();
    }
    public static function parseDuration($time) //TODO Algorthmus für Zahlen kleiner 30:00:00 z.B.
    {
        $hours = 0;
        $seconds = 0;
        $minutes = 0;

        if($time == null)
        {
            return "Keine Angabe";
        }

        if(strlen($time) == 2)
        {
            $seconds = $time;
        }
        else if(strlen($time) == 3)
        {
            $minutes = substr($time, 0,1);
            $seconds = substr($time, 2,2);
        }
        else if(strlen($time) == 4)
        {
            $minutes = substr($time, 0, 2);
            $seconds = substr($time, 3,2);
        }
        else if(strlen($time) == 5)
        {
            $hours = substr($time, 0,1);
            $minutes= substr($time, 2, 2);
            $seconds = substr($time, 4);
        }
        else if(strlen($time) == 6)
        {
            $hours = substr($time, 0,2);
            $minutes= substr($time, 3, 2);
            $seconds = substr($time, 5);
        }

        return "$hours"."h ". $minutes."m ".$seconds."s";
    }

    public static function get($id)
    {
        // TODO: Implement get() method.
        $db = Database::connect();
        $sql = 'SELECT * FROM tbl_project WHERE p_id = ?';
        $stmt=$db->prepare($sql);
        $stmt->execute(array($id));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);


        Database::disconnect();

        $project = new Project($data['p_id'], $data['titel'], $data['kickoff'], Project::getDuration($id));

        return $project;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getKickoff()
    {
        return $this->kickoff;
    }

    /**
     * @param mixed $kickoff
     */
    public function setKickoff($kickoff)
    {
        $this->kickoff = $kickoff;
    }

    /**
     * @return mixed
     */
    public function getDurati()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
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