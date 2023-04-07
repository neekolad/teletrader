<?php






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>TeleTrader</title>

    <style>
        body {
            height: 100vh;
            background-color: #eee;
        }
    </style>
</head>
<body>
    <nav class="navbar bg-dark navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#"></a>
        </div>
      </nav>
    <div class="container h-100">
        <!-- Content here -->
        <div class="container">
        <h2>List of reports</h2>
          <div class="col-6">
          <div>
             <table class="table table-striped table-bordered text-center">
              <thead>
                <tr>
                  <th>Report Name</td>
                  <th>Action</td>
                </tr>
              </thead>
              <tbody>
              <tr>
                <td>Report 1</td>
                <td><a href="extract_excel.php" class="btn btn-primary">Download</button></td>
              </tr>
              <tr>
                <td>Report 2</td>
                <td><a href="extract_xml.php" class="btn btn-primary">Download</button></td>
              </tr>
              </tbody>
              
             </table>
            </div>
          </div>
            
            
        </div>
        

      </div>
      <footer class="sticky-bottom bg-dark">
        <div class="container">
          <span class="text-light">Nikola Despic - 064 668 71 60 - neekolad@gmail.com</span>
        </div>
      </footer>
</body>
</html>