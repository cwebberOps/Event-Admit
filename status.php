<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Status</title>
    </head>
    <body>
        <?php
            require "lib/class.db.php";
            $db = new db();

            $enrolled_result = $db->query("SELECT COUNT(id) AS total FROM log WHERE status = 'enrolled';");
            $enrolled = $db->row($enrolled_result);

            $paid_result = $db->query("SELECT COUNT(id) AS total FROM log WHERE status = 'paid';");
            $paid = $db->row($paid_result);

            $admin_result = $db->query("SELECT COUNT(id) AS total FROM log WHERE status = 'admin';");
            $admin = $db->row($admin_result);

            $notenrolled_result = $db->query("SELECT COUNT(id) AS total FROM log WHERE status = 'notenrolled';");
            $notenrolled = $db->row($notenrolled_result);

        ?>
        <h1>Admitted</h1>
        <table border="2">
            <tr><th>Pre Paid</th><td><?PHP echo $paid['total']; ?></td></tr>
            <tr><th>At The Door</th><td><?PHP echo $enrolled['total']; ?></td></tr>
            <tr><th>TOTAL</th><td><?PHP echo $paid['total'] + $enrolled['total']; ?></td></tr>
            
        </table>

    </body>
</html>
