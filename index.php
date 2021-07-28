<?php 
require 'core/Database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Api Test</title>
</head>
<body>
    <header>
        <h1>Pagination</h1>
    </header>
    <main>
        <p id="flash"></p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Updated</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <tr>
                    <td>1</td>
                    <td>Tae</td>
                    <td>2020</td>
                    <td>2021</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div class="pagenumber" id="pagination">

        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>