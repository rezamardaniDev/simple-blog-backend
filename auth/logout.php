<?php

session_start();
require '../functions/helpers.php';
session_destroy();

redirect('/auth/login.php');