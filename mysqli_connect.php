<?php

// Create connection

$dbc = new mysqli('localhost', 'nikola', '123123', 'project_db');

// Check connection
if (!$dbc->connect_errno != 0) {
    echo $dbc->connect_error;
  }
