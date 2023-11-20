<?php

require_once '../../include/model/logout_model.php';

session_start();

$_SESSION = array();
header('Location: index.php');
exit();

include_once '../../include/view/logout_view.php';
