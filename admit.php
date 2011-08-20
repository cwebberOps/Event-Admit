<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?PHP

    include_once 'conf.php';
    include_once 'lib/class.student.php';
    include_once 'lib/class.admit.php';


    if (isset($_POST['sid'])) {
        try {
            $student = new student($_POST['sid']);
            $admit = new admit($student);
            $admit->decision();
        } catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Admit</title>
        <script type="text/javascript">

            function focusIt() {
              var sid = document.getElementById("sid"); 
              sid.focus();
              sid.select(); 
            }

            var backColor = new Array(); // don't change this
            backColor[0] = '#FFFFFF';
            backColor[1] = '#FF0000';
            backColor[2] = '#00FF00';
            backColor[3] = '#0000FF';
            backColor[4] = '#FFFF00';
            backColor[5] = '#CCCC00';
            backColor[6] = '#00CC00';
            backColor[7] = '#00CCCC';



            function changeBG(whichColor){
            document.bgColor = backColor[whichColor];
            }

            function loadIt() {
                changeBG(<?PHP if (isset($admit)) {echo $admit->color;} else {echo '0';}; ?>);
                focusIt();
            }

            onload = loadIt;

        </script>
    </head>

    <body>
        
        <div id='admit_box' style='float:left;clear: both;'>
                <form name="admit" action="admit.php" method="post">
                        <input type="text" name="sid" id="sid"  /> <input type="submit" name="Submit" />
                </form>
        </div>

        <div style="float:left; clear: both;">
            <?PHP
            if (isset($admit)) {
                echo "<h2>" . $admit->text . "</h2>";
                if (file_exists("img/" . $_POST['sid'] . "a.jpeg")) {
                    echo "<img src='img/" . $_POST['sid'] . "a.jpeg'>";
                }
            }
            ?>
        </div>

    </body>
</html>
