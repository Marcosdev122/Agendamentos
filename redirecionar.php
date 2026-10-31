<?php

session_start();

if($_SESSION['role'] == 'medico'){
    header("Location: medico.php");
}
elseif($_SESSION['role'] == 'admin'){
    header("Location: admin.php");
}
else{
    header("Location: principal.php");
}