<?php

include 'database/connection.php';
include 'classes/users.php';
include 'classes/post.php';
include 'classes/admin.php';

global $pdo;

$loadFromUser = new User($pdo);
$loadFromPost = new Post($pdo);
$loadFromAdmin = new Admin($pdo);

define("BASE_URL", $localhost);