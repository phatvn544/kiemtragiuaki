<?php
session_start();
session_destroy();
header("Location: students.php");
exit();
