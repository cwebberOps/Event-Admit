<?php
/**
 * Deal with the database backend in a seamless way.
 *
 * @author cwebber
 */
class db {
    /**
     *
     * @var db_type Database Type
     */
    var $type;
    /**
     *
     * @var db_location
     */
    var $location;
    /**
     *
     * @var mysql_db_name
     */
    var $name;
    /**
     *
     * @var mysql_db_username
     */
    var $user;
    /**
     *
     * @var mysql_db_password
     */
    var $password;
    /**
     * @var database_connection
     */
    var $db;


    /**
     * create the db.
     */
    function db() {
        include 'conf.php';
        $this->type = $config_db_type;
        $this->location = $config_db_location;
        $this->name = $config_db_name;
        $this->user = $config_db_user;
        $this->password = $config_db_password;

        /**
         * switch on the type of db and then verify that all is good.
         * If it is not, thow an error.
         */
        switch ($this->type) {
            case 'sqlite':
                $this->db = new SQLite3($this->location);
            break;

            case 'mysql':
            break;

            default:
                throw new Exception($this->type . " is not a valid database type.");
            break;
        }
    }

    /**
     * Clear the database values
     */
    function clear() {

        switch ($this->type) {
            case 'sqlite':
            case 'mysql':
                $query[0] = 'DROP TABLE `enrolled`;';

                $query[1] = 'DROP TABLE `paid`;';

                $query[2] = 'DROP TABLE `admin`;';

                $query[3] = 'DROP TABLE `log`';

                $query[4] = 'DROP TABLE `stage`';

				$query[5] = 'DROP TABLE `guest`';

            break;

            default:
                throw new Exception($this->type . " is not a valid database type.");
            break;

        }

        foreach ($query as $drop) {
            $this->query($drop);
        }

        /**
         * Integrity Checking goes here
         */

    }

    /**
     * Initialize the database. Creates all the tables.
     */
    function initialize() {

        switch ($this->type) {
            case 'sqlite':
                $query[0] = 'CREATE  TABLE `enrolled` (
                  `sid` INTEGER PRIMARY KEY,
                  `first`  TEXT ,
                  `last` TEXT);';

                $query[1] = 'CREATE  TABLE `paid` (
                  `sid` INTEGER PRIMARY KEY);';

                $query[2] = 'CREATE  TABLE `admin` (
                  `sid` INTEGER PRIMARY KEY);';

                $query[3] = 'CREATE  TABLE `log` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `sid` TEXT,
                  `event` TEXT,
                  `status` TEXT,
                  `timestamp` TEXT);';
                  
                $query[4] = 'CREATE  TABLE `stage` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `sid` TEXT,
                  `last` TEXT,
                  `first` TEXT,
                  `dest` TEXT);';
				  
				$query[5] = 'CREATE  TABLE `guest` (
                  `sid` INTEGER PRIMARY KEY);';

				  
            break;

            case 'mysql':

                $query[0] = 'CREATE  TABLE IF NOT EXISTS `enrolled` (
                  `sid` INT NOT NULL ,
                  `first` VARCHAR(45) NULL ,
                  `last` VARCHAR(45) NULL ,
                  PRIMARY KEY (`sid`) );
                ENGINE = InnoDB;';

                $query[1] = 'CREATE  TABLE IF NOT EXISTS `paid` (
                  `sid` INT NOT NULL ,
                  PRIMARY KEY (`sid`) )
                ENGINE = InnoDB;';

                $query[2] = 'CREATE  TABLE IF NOT EXISTS `admin` (
                  `sid` INT NOT NULL ,
                  PRIMARY KEY (`sid`) )
                ENGINE = InnoDB;';

                $query[3] = 'CREATE  TABLE IF NOT EXISTS `log` (
                  `id` INT NOT NULL ,
                  `sid` VARCHAR(45) NULL ,
                  `status` VARCHAR(45) NULL,
                  `event` VARCHAR(45) NULL ,
                  `timestamp` DATETIME NULL ,
                  PRIMARY KEY (`id`) )
                ENGINE = InnoDB;';

            break;

            default:
                throw new Exception($this->type . " is not a valid database type.");
            break;

        }

        foreach ($query as $insert) {
            $this->query($insert);
        }
        
        /**
         * Integrity Checking goes here
         */
         
    }


    /**
     * Query the database
     *
     * @return db_result
     */
    function query ($query) {

        //echo "<br>\n" . $query;

        switch ($this->type) {
            case 'sqlite':
				//echo $query . "<br />\n";
                $result = $this->db->query($query);
                return $result;
            break;

            case 'mysql':
            break;

            default:
                throw new Exception($this->type . " is not a valid database type.");
            break;
        }
    }

    /**
     * Return a row from the current db handle
     *
     * @param db_handle Database Handle returned from $this->query()
     */
    function row($query_handle) {
        switch ($this->type) {
            case 'sqlite':
                return $query_handle->fetchArray();
            break;

            case 'mysql':
            break;
        }
    }

    function log_admit($sid, $status) {
        switch ($this->type) {
            case 'sqlite':
                $this->query("INSERT INTO log (sid, status, timestamp) VALUES ('" . sqlite_escape_string($sid) . "', '" . sqlite_escape_string($status) . "', datetime());");
                return 0;
            break;

            case 'mysql':
            break;
        }

    }

    function stage_std_row($sid,$dest) {

        switch ($this->type) {
            case 'sqlite':
                $this->query("INSERT INTO stage (sid, dest) VALUES ('" . sqlite_escape_string($sid) . "', '" . sqlite_escape_string($dest) . "');");
                return 0;
            break;

            case 'mysql':
            break;
        }


    }

    function stage_enrolled_row($sid, $last, $first) {

        switch ($this->type) {
            case 'sqlite':
                $this->query("INSERT INTO stage (sid, last, first, dest) VALUES ('" . sqlite_escape_string($sid) . "', '" . sqlite_escape_string($last) . "', '" . sqlite_escape_string($first) . "', 'enrolled');");
                return 0;
            break;

            case 'mysql':
            break;
        }

    }

    function clear_stage($dest) {
        $this->query("DELETE FROM stage WHERE dest = '" . $dest . "';");
    }

    function clear_dest($dest) {
        $this->query("DELETE FROM " . $dest . ";");
    }

    function sid_present($sid,$table) {
        switch ($this->type) {
            case 'sqlite':
                $query = $this->query("SELECT sid FROM " . sqlite_escape_string($table) . " WHERE sid = '" . sqlite_escape_string($sid) . "';");
                if ($row = $this->row($query)) {
                    return true;
                } else {
                    return false;
                }

            break;

            case 'mysql':
            break;
        }

    }

    function log_stats() {
        
    }

}
?>
