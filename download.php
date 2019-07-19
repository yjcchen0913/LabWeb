<?php
require_once 'config.php';

function curPageURL() {
    $pageURL = 'http';
    if (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
     $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
     $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
$id = parse_url(curPageURL(),PHP_URL_QUERY);

$query = "SELECT title FROM thesis WHERE id = $id";
$commit = mysqli_query($link,$query);

if(mysqli_num_rows($commit) > 0){
    $row = mysqli_fetch_assoc($commit);
    $file_url = "uploads/" .$row['title'];
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
    readfile($file_url); // do the double-download-dance (dirty but worky)

    $query_up = "UPDATE thesis SET download = download + 1 WHERE id = $id "; //update download times
    mysqli_query($link,$query_up);   
    
    header("Refresh:0");
}

?>