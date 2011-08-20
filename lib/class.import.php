<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author cwebber
 */
class import {

    /**
     *
     * @var file path to the file being uploaded
     */
    var $file;

    /**
     *
     * @var string Type of file. excel, cvs
     */
    var $file_type;

    /**
     *
     * @var string Dest of import. admin, enrolled, paid
     */
    var $import_dest;

    /**
     * @var string Type of import. file, stage
     */
     var $import_type;

    function import ($import_type) {
        $this->import_type = $import_type;
    }
    
    function from_file($file, $file_type, $import_dest) {
        
        $this->file = $file;
        $this->file_type = $file_type;
        $this->import_dest = $import_dest;

    }

    function from_stage($import_dest) {

        $this->import_dest = $import_dest;

    }

    function preview($import_dest) {
        $db = new db();
        switch ($import_dest) {
            case "enrolled":
                $list = $db->query("SELECT sid, last, first FROM stage WHERE dest = '" . $import_dest . "';");
                echo "<table>\n";
                echo "<tr><th>Student ID</th><th>Last Name</th><th>First Name</th></tr>\n";
                while ($row = $db->row($list)) {
                    echo "<tr><td>" . $row['sid'] . "</td><td>" . $row['last'] . "</td><td>" . $row['first'] . "</td></tr>\n";
                }
                echo "</table>\n";
            break;
			
            case "guest":
            case "admin":
            case "paid":
                $list = $db->query("SELECT sid FROM stage WHERE dest = '" . $import_dest . "';");
                echo "<table>\n";
                echo "<tr><th>Student ID</th></tr>\n";
                while ($row = $db->row($list)) {
                    echo "<tr><td>" . $row['sid'] . "</td></tr>\n";
                }
                echo "</table>\n";
            break;

        }
    }


    function process() {
        $db = new db();

        switch ($this->import_type) {
            case "file":
                $db->clear_stage($this->import_dest);
                switch ($this->import_dest) {
                    case "enrolled":
                        switch ($this->file_type) {
                            case "xls":
                                require_once 'lib/PHPExcel/IOFactory.php';
                                $excel = PHPExcel_IOFactory::load($this->file);
                                $worksheet = $excel->setActiveSheetIndex(0);

                                $i = 1;
                                while (preg_match("/^[0-9]{6}\z/", $worksheet->getCell('A' . $i)->getValue()) || ($i < 5)) {
                                    if (preg_match("/^[0-9]{6}\z/", $worksheet->getCell('A' . $i)->getValue())) {
                                       $db->stage_enrolled_row($worksheet->getCell('A' . $i)->getValue(), $worksheet->getCell('B' . $i)->getValue(), $worksheet->getCell('C' . $i)->getValue());
                                    }
                                    $i++;
                                }

                            break;

                            case "csv":
                            break;
                        }

                    break;

                    case "guest":
					case "admin":
                    case "paid":
                        switch ($this->file_type) {
                            case "xls":
                                require_once 'lib/PHPExcel/IOFactory.php';
                                $excel = PHPExcel_IOFactory::load($this->file);
                                $worksheet = $excel->setActiveSheetIndex(0);

                                $i = 1;
                                while (preg_match("/^[0-9]{6}\z/", $worksheet->getCell('A' . $i)->getValue()) || ($i < 5)) {
                                    if (preg_match("/^[0-9]{6}\z/", $worksheet->getCell('A' . $i)->getValue())) {
                                       $db->stage_std_row($worksheet->getCell('A' . $i)->getValue(),$this->import_dest);
                                    }
                                    $i++;
                                }

                            break;

                            case "csv":
                            break;
                        }

                    break;

                }
            break;

            case "stage":
                switch ($this->import_dest) {
                    case "enrolled":
                        $db->clear_dest($this->import_dest);
                        $db->query("INSERT INTO " . $this->import_dest . " (sid, last, first) SELECT sid, last, first FROM stage WHERE dest = '" . $this->import_dest . "';");
                        echo "Complete";
                    break;
					case "guest":
                    case "admin":
                    case "paid":
                        $db->clear_dest($this->import_dest);
                        $db->query("INSERT INTO " . $this->import_dest . " (sid) SELECT sid FROM stage WHERE dest = '" . $this->import_dest . "' GROUP BY sid;");
                        echo "Complete";
                    break;
                }
            break;
        }
    }

}
?>
