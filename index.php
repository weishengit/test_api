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
        <h1>Pagination</h1><button id="reload-btn">*</button><span id="reload"></span>
    </header>
    <div id="snackbar"></div>
    <main>
        <table>
            <thead>
                <tr id="table-header">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Updated</th>
                </tr>
            </thead>
            <tbody id="table-body">
                
            </tbody>
        </table>
        <hr>
        <div id="pagination">
            <span id="total-count"></span>
            <button id="first-page"> << </button>
            <button id="prev-page"> Prev </button>
            <input id="current-page" type="text" style="width: 3%;">
            <span id="page-count"></span>
            <button id="next-page"> Next </button>
            <button id="last-page"> >> </button>
            <button id="jump-page"> Jump </button>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>