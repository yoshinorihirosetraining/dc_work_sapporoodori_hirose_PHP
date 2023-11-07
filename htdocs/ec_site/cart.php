<?php

require_once '../../include/model/ec_site_model.php';

if ($_SESSION['state'] == 'admin') {
    header('Location: admin.php');
} else if ($_SESSION['state'] != 'login') {
    header('Location: index.php');
}



include_once '../../include/view/cart_view.php';
