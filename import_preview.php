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
            if ($_FILES['import_file']['error'] == 0) {
                $inital = new import('file');
                $inital->from_file($_FILES['import_file']['tmp_name'], $_POST['file_type'], $_POST['import_dest']);
                $inital->process();

                $preview = new import('stage');
                $preview->preview($_POST['import_dest']);
            } else {
                echo "Error: The file didn't upload correctly.";
            }
        ?>
        <p><strong>Total:</strong> </p>
        <form name="import_complete" action="import_complete.php" method="POST">
            <input type="hidden" name="import_dest" value="<?php echo $_POST['import_dest']; ?>">
            <input name="cancel" type="button" value="Go Back"> <input name="submit" value="Submit" type="submit">
        </form>
    </body>
</html>
