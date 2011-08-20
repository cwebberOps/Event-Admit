<?php

/**
 * student is an object that defines used to contain all the
 *
 * @author cwebber
 */

//require_once 'class.db.php';

class student {
    /**
     * @var int Six digit number representing a single student
     */
    var $id;

    function student($sid) {

        if (preg_match("/^[0-9]{6}\z/", $sid)) {
            $this->id = $sid;
        } else {
            throw new Exception($sid . " is not a valid Student ID number.");
        }

    }

    /**
     * is_enrolled() returns true if a student is on the enrolled list.
     */
    function is_enrolled() {
       $db = new db();

//       foreach ($db->get_enrolled() as $student) {
//           if ($student['id'] == $this->id) {
//               return true;
//           }
//       }
//
       return $db->sid_present($this->id, "enrolled");

    }

    /**
     * is_admin_hold() returns true if student is on the enrolled list.
     */
    function is_admin_hold() {
       $db = new db();
//
//       foreach ($db->get_admin_hold() as $student) {
//           if ($student == $this->id) {
//               return true;
//           }
//       }
//
//       return false;
       return $db->sid_present($this->id, "admin");
    }

    /**
     * paid() returns true if the student is on the enrolled list.
     */
    function paid() {
       $db = new db();

//       foreach ($db->get_paid() as $student) {
//           if ($student == $this->id) {
//               return true;
//           }
//       }
//
//       return false;
       return $db->sid_present($this->id, "paid");
    }

    function guest() {
       $db = new db();

       return $db->sid_present($this->id, "guest");

    }

	
    /**
     * name() returns the name of the student.
     */
    function name() {
        
        $db = new db();
        $name = '';

       $query = $db->query("SELECT first, last FROM enrolled WHERE sid = '" . $this->id . "';");
       if ($student = $db->row($query)) {
           $name = $student['first'] . " " . $student['last'];
       }

       return $name;
    }

    /**
     * pic() returns the filename of the image.
     */
    function pic() {
        return $this->id . ".jpg";
    }
}
?>
