<?php
require('includes/config.php');

$user->logout();
session_destroy();

header('Location: login.php');