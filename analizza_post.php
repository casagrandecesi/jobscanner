<?php
session_start();
$allPosts = $_SESSION['allPosts'];
echo "<h1>Ecco i post!</h1>";
echo json_encode($allPosts, JSON_PRETTY_PRINT);
echo "<h1>Tutto fatto!</h1>";