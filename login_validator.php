<?php
session_start();
$user = $_POST["username"];
$passwd = $_POST["password"];

// Remove HTML tags from string
$user = filter_var($user, FILTER_SANITIZE_STRING);
$passwd= filter_var($passwd, FILTER_SANITIZE_STRING);

$forbidden_chars = array(
   "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&","#", "*", "(", ")" , "|", "~", "`", "{", "}", "%", "+" ,"-", chr(0));

$replace_chars = array(
  'áéíóúäëïöüàèìòùñ ',
  'aeiouaeiouaeioun_'
);

for( $i=0 ; $i < strlen($user) ; $i++ ) {
  $sane_charusr = $source_charusr = $user[$i];
  if ( in_array( $source_charusr, $forbidden_chars ) ) {
    $sane_charusr = "_";
    $saneUsr .= $sane_charusr;
    continue;
  }
  $pos = strpos( $replace_charsusr[0], $source_charusr);
  if ( $pos !== false ) {
    $sane_charusr = $replace_charsusr[1][$pos];
    $saneUsr .= $sane_charusr;
    continue;
  }
  if ( ord($source_charusr) < 32 || ord($source_charusr) > 128 ) {
    // Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales
    $sane_charusr = "_";
    $saneUsr .= $sane_charusr;
    continue;
  }
  // Si ha pasado todos los controles, aceptamos el carácter
  $saneUsr .= $sane_charusr;
}

for( $i=0 ; $i < strlen($passwd) ; $i++ ) {
  $sane_charpwd = $source_charpwd = $passwd[$i];
  if ( in_array( $source_charpwd, $forbidden_chars ) ) {
    $sane_charpwd = "_";
    $sanePwd .= $sane_charpwd;
    continue;
  }
  $pos = strpos( $replace_charspwd[0], $source_charpwd);
  if ( $pos !== false ) {
    $sane_charpwd = $replace_charspwd[1][$pos];
    $sanePwd .= $sane_charpwd;
    continue;
  }
  if ( ord($source_charpwd) < 32 || ord($source_charpwd) > 128 ) {
    // Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales
    $sane_charpwd = "_";
    $saneUsr .= $sane_charpwd;
    continue;
  }
  // Si ha pasado todos los controles, aceptamos el carácter
  $sanePwd .= $sane_charpwd;
}


// if ($user == "Kathe" && $passwd == "quesito") {
// 	$_SESSION["session"] = true;
// 	$_SESSION["user"] = $user;
// 	header('location: ./logged_user.php');
// } else {
// 	header('location: ./login.php');
// }
include('./connDB/conn.php');
$obj = new Connection();
$conn = $obj->Conn();

$query = "SELECT * FROM users WHERE username='$saneUsr' AND password='$sanePwd'";
$response = $conn->prepare($query);
$response->execute();
$data = $response->fetchAll();
if (sizeof($data) > 0) {
	$_SESSION["session"] = true;
	$_SESSION["user"] = $saneUsr;
	header('location: ./logged_user.php');
} else {
	$_SESSION["incorrectData"] = true;
	header('location: ./login.php');
}
