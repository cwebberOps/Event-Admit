<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * admit is the class used to do the actual admiting
 *
 * @author cwebber
 */
class admit {
    
    /**
     * @var object Student Object
     */
     var $student;

     /**
      * @var text Decision Text
      */
     var $text;

     /**
      * @var text Status
      */
     var $status;

     /**
      * @var int color code for the JS
      */
     var $color;


    /**
     * Start with a student...
     */

    function admit ($student) {
        $this->student = $student;
    }

    /**
     * seen() is used to determine if we have seen this student before;
     */
    function seen() {
        $db = new db();
        return $db->sid_present($this->student->id, "log");
    }

    /**
     * decision is used to let us know the decision.
     */
    function decision() {
        
        if (!($this->seen())) {
            if ($this->student->is_enrolled()) {

                if ($this->student->is_admin_hold()) {
                    if ($this->student->paid()) {
						if ($this->student->guest()) {
						   $this->text = $this->student->name() . " has purchased a ticket and a guest pass but needs to See Admin.";
						   $this->status = 'admin_paid_guest';
						   $this->color = 6;
					    } else {
						   $this->text = $this->student->name() . " has purchased a ticket but needs to See Admin.";
						   $this->status = 'admin_paid';
						   $this->color = 5;
						}
                    } else {
                       $this->text = "Please Have " . $this->student->name() . " See Admin.";
                       $this->status = 'admin';
                       $this->color = 1;
                    }
                } else {
                    if ($this->student->paid()) {
						if ($this->student->guest()) {
							$this->text = "Please Admit " . $this->student->name() . " and their guest.";
							$this->status = 'paid_guest';
							$this->color = 7;
						} else {
							$this->text = "Please Admit " . $this->student->name();
							$this->status = 'paid';
							$this->color = 2;
						}
                    } else {
                        $this->text = "Please Have " . $this->student->name() . " Purchase a Ticket.";
                        $this->status = 'enrolled';
                        $this->color = 3;
                    }
                }

            } else {
                $this->text = "Please Have This Student See Admin.";
                $this->status = 'notenrolled';
                $this->color = 1;
            }
        } else {
            $this->text = "This Student Has Been Seen Before.";
            $this->status = 'seen';
            $this->color = 4;
        }

        $this->record();
    }

    /**
     * record() is used to record the decision.
     */
    function record() {

        $db = new db();
        $db->log_admit($this->student->id, $this->status);
        return true;


    }

}
?>
