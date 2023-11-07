<?php

require_once '../../include/model/ec_site_model.php';

session_start();

if ($_SESSION['state'] == 'admin') {
    header('Location: admin.php');
} else if ($_SESSION['state'] != 'login') {
    header('Location: index.php');
}

var_dump($_SESSION);

include_once '../../include/view/login_view.php';
