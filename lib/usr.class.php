<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of userClass
 *
 * @author runarbe
 */
class usr {

    /**
     * User ID
     * @var Integer 
     */
    public $id;

    /**
     * Type of user
     * @var usrType One of 'admin', 'user' 
     */
    public $utype;

    /**
     * User name
     * @var String 
     */
    public $usr;

    /**
     * Password
     * @var String
     */
    public $pwd;

    /**
     * Array of group IDs for groups that the user belongs to
     * @var integer[] 
     */
    public $groups;

    /**
     * Constructor
     * @param string $pUsr
     * @param string $pPwd
     * @param integer $pID
     * @param usrType $pUtype
     */
    public function __construct($pUsr,
            $pPwd,
            $pID = null,
            $pUtype = usrType::user) {
        $this->usr = $pUsr;
        $this->pwd = $pPwd;
        $this->id = $pID;
        $this->utype = $pUtype;

        if (is_numeric($this->id)) {
            $this->groups = $this->getGroups();
        }
    }

    /**
     * Delete the current object
     * @return boolean True on success, false on error
     */
    public function delete() {
        if (is_numeric($this->id)) {
            db::execute(sprintf("DELETE FROM usr WHERE id = %s;",
                            $this->id));
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update the current user
     * @return boolean True on success, false on error
     */
    public function update() {
        if (is_numeric($this->id)) {
            return db::execute(sprintf("UPDATE usr SET usr='%s', pwd='%s', utype='%s' WHERE id=%s;",
                                    $this->usr,
                                    $this->pwd,
                                    $this->utype,
                                    $this->id));
        }
    }

    /**
     * Get the currently authenticated user
     * @return usr|false
     */
    public static function getAuthUsr() {
        if (isset($_SESSION["usr"])) {
            return $_SESSION["usr"];
        } else {
            return false;
        }
    }

    /**
     * Set the currently authenticated user
     * @param usr $pUsr
     * @return boolean Returns true
     */
    public static function setAuthUsr($pUsr) {
        $_SESSION["usr"] = $pUsr;
        return true;
    }

    /**
     * Array of values for creating user
     * @param array $pArray An associative array on the form ["usr"=>"", "pwd"=>""];
     * @return usr|boolean Return a usr object for the newly created user
     */
    public static function insert($pUsr,
            $pPwd,
            $pUtype = usrType::user) {
        if (db::execute(sprintf("INSERT INTO usr (usr, pwd, utype) VALUES ('%s','%s', '%s');",
                                $pUsr,
                                $pPwd,
                                $pUtype))) {
            $mNewId = db::getLastInsertID();
            return usr::select($mNewId);
        } else {
            return false;
        }
    }

    /**
     * Return the IDs of groups that the user belongs to
     * @return integer[]
     */
    public function getGroups() {
        $mGrpIDs = array();
        if (is_numeric($this->id)) {
            $mRes = db::query(
                            sprintf(
                                    "SELECT g.id FROM grp g LEFT JOIN member m ON (m.grp_id = g.id) WHERE m.usr_id = %s",
                                    $this->id));
            if ($mRes != false) {
                while ($mRow = mysqli_fetch_assoc($mRes)) {
                    $mGrpIDs[] = $mRow[0];
                }
            }
        }
        return $mGrpIDs;
    }

    /**
     * Retrieve a user
     * @param integer $pID
     * @return usr|boolean A user on success or false on error
     */
    public static function select($pID) {
        $mRes = db::query(sprintf("SELECT id, usr, pwd, utype FROM usr WHERE id=%s;",
                                $pID));
        if ($mRes != false && mysqli_num_rows($mRes) == 1) {
            $mRow = mysqli_fetch_assoc($mRes);
            return new usr($mRow["usr"],
                    $mRow["pwd"],
                    $mRow["id"],
                    $mRow["utype"]);
        } else {
            return false;
        }
    }

    /**
     * Check if a user exists
     * @param String $pUsr
     * @return boolean True if exists, false if not
     */
    public static function exists($pUsr) {
        $mRes = db::executeScalar(sprintf("SELECT id FROM usr WHERE usr = '%s'",
                                $pUsr));
        if ($mRes == false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Authenticate a user
     * @param String $pUsr
     * @param String $pPwd
     * @return usr|boolean A user object on success, false on fail
     */
    public static function login($pUsr,
            $pPwd) {
        $res = db::query(sprintf("SELECT id FROM usr WHERE usr='%s' AND pwd='%s';",
                                $pUsr,
                                $pPwd));
        if (mysqli_num_rows($res) == 1) {
            $mRow = mysqli_fetch_assoc($res);
            $mUsr = usr::select($mRow["id"]);
            usr::setAuthUsr($mUsr);
            return $mUsr;
        } else {
            usr::setAuthUsr(null);
            return false;
        }
    }

    /**
     * Logs out the currently logged in user
     * @return boolean
     */
    public static function logout() {
        usr::setAuthUsr(null);
        return true;
    }

    /**
     * Require a user to be authenticated
     * @return boolean
     */
    public static function isAuth() {
        if (usr::getAuthUsr() != false) {
            return true;
        } else {
            return false;
        }
    }

}
