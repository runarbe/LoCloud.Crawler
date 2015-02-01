<?php

/**
 * Methods that must be implemented by any class that represents a database table
 */
interface iTable {
        
    /**
     * A method that must be implemented to create a new object
     * @param Array $pArray
     * @return Object|Boolean Return the created object or false on error
     */
    public static function insert($pArray);
    
    /**
     * A method that must be implemented to retrieve an object
     * @param Integer $pID
     * @return Object|Boolean Return the object identified by the ID or false on error
     */
    public static function select($pID);
    
    /**
     * A method that must be implemented to update an existing object
     */
    public function update ();
    
    /**
     * A method that must be implemented to delete an existing object
     */
    public function delete();
}