<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>

        <form name="import" enctype="multipart/form-data" method="POST" action="import_preview.php">
            <table>
                <tr>
                    <th>Type of Import</th>
                    <td>
                        <select name="import_dest">
                            <option value="enrolled">Enrolled Students</option>
                            <option value="paid">Students that have Paid</option>
                            <option value="admin">Students that are on Admin Hold</option>
                            <option value="guest">Students that have Paid for a Guest Pass</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>File</th>
                    <td><input type="file" name="import_file"></td>
                </tr>
                <tr>
                    <th>File Type</th>
                    <td>
                        <select name="file_type">
                            <option value="csv">CSV</option>
                            <option value="xls" selected>Excel</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input name="submit" value="Begin Import" type="submit">
        </form>

    </body>
</html>
