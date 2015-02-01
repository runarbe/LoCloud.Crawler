<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author runarbe
 */
class db {

    /**
     * Database connection
     * @var Mysqli
     */
    public static $conn = null;

    /**
     * Name of databases
     * @var String 
     */
    public static $db = null;

    /**
     * Name of database user
     * @var String 
     */
    public static $db_usr = null;

    /**
     * Password of database user
     * @var String
     */
    public static $db_pwd = null;

    /**
     * Close the datbase connection if open
     */
    public static function close() {
        if (db::$conn !== null) {
            db::$conn->close();
            db::$conn = null;
        }
        return;
    }

    /**
     * Open a new database connection
     * @param string $pDb
     * @param string $pUsr
     * @param string $pPwd
     * @return boolean True on success, false on error
     * @todo Defaults to database running on same machine.
     */
    public static function open() {

        db::$conn = @mysqli_connect("localhost",
                        LoCloudCondfig::DB_USR,
                        LoCloudCondfig::DB_PWD,
                        LoCloudCondfig::DB
                ) or ( die("Could not connect to database. Please verify the application configuration and that MySQL server is running."));

        return db::isSuccess("Open database connection");
    }

    /**
     * Returns the database object if open, otherwise opens it and then returns it-
     * @return mysqli MySQLi database object
     */
    public static function getDb() {
        if (db::$conn === null) {
            db::open();
        }
        return db::$conn;
    }

    /**
     * Escape a string for inclusion into an SQL statement
     * @param string $pString Unescaped string
     * @return string Escaped string
     */
    public static function escape($pString) {
        $pString = mb_convert_encoding($pString, "UTF-8");
        return mysqli_escape_string(db::getDb(),
                $pString);
    }

    /**
     * Executes an SQL query and returns a boolean flag indicating whether the operation succeded
     * @param string $pSql
     * @return boolean True on success, false on error
     */
    public static function execute($pSql) {
        $db = db::getDb();
        mysqli_query($db,
                $pSql);
        return db::isSuccess($pSql);
    }

    /**
     * Executes a query and returns the first value of the first row of the query results
     * @param string $pSql
     * @return mixed|null Mixed value on success or null on error
     * @todo Differentiate between null value in result and null value on error|
     */
    public static function executeScalar($pSql) {
        $mRes = mysqli_query(db::getDb(),
                $pSql);

        if (db::isSuccess($pSql)) {
            $mArray = mysqli_fetch_array($mRes);
            if (count($mArray) > 0) {
                return $mArray[0];
            } else {
                return null; // Consider a NaN value instead
            }
        } else {
            return null; // Consider a NaN value instead
        }
    }

    /**
     * Check whether a result resource contains rows
     * @param mysqli_result $pRes
     * @return boolean True if rows, false if no rows
     */
    public static function hasRows($pRes) {
        if ($pRes != false && mysqli_num_rows($pRes) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the number of affected rows for the last operation
     * @return integer
     */
    public static function affectedRows() {
        return mysqli_affected_rows(db::getDb());
    }

    /**
     * Get the next row from the batch
     * @param mysqli_result $pRes
     * @return mixed[]|false
     */
    public static function getNextRow($pRes) {
        return mysqli_fetch_assoc($pRes);
    }

    /**
     * Checks if the last operation of the database was successful and returns a boolean flag indicating so
     * @return boolean True on success, false on error
     */
    public static function isSuccess($pSql) {
        if (mysqli_errno(db::getDb()) == 0) {
            return true;
        } else {
            log::write($pSql);
            log::write(mysqli_error(db::getDb()),
                    true);
            return false;
        }
    }

    /**
     * Returns the last auto-increment number generated in the database
     * @return integer
     */
    public static function getLastInsertID() {
        return mysqli_insert_id(db::getDb());
    }

    /**
     * Executes an SQL query and returns the result or a boolean flag on error
     * @param string $pSql
     * @return mysqli_result|boolean Result on success, false on error
     */
    public static function query($pSql) {
        $res = mysqli_query(db::getDb(),
                $pSql);
        if (db::isSuccess($pSql)) {
            return $res;
        } else {
            return false;
        }
    }

}
