<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <?php
        switch ($_POST['import_dest']) {
            case "enrolled":
                $title = "Enrolled Students";
            break;
            case "paid":
                $title = "Students that have Paid";
            break;
            case "admin":
                $title = "Students that are on Admin Hold";
            break;
            case "guest":
                $title = "Students that have Paid for a Guest Pass";
            break;
        }

    ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <?php
            require_once 'lib/class.db.php';
            require_once 'lib/class.import.php';
            echo "<h1>" . $title . "</h1>\n";
            if (isset($_POST['import_dest'])) {
                $final = new import('stage');
                $final->from_stage($_POST['import_dest']);
                $final->process();
            }
        ?>
		<br />
		<a href="import.php">Import Another List</a><br />
		<a href="index.php">Done</a>
    </body>
</html>
