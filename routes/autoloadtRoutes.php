<?php

// include_once "users.routes.php";

$files = scandir(__DIR__ . "/");
foreach ($files as $file) {
  if (!in_array($file, ['.', '..'])) {
    include_once $file;
  }
}
