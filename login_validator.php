<?php
session_start();
$user = $_POST["username"];
$passwd = $_POST["password"];

$user = filter_var($user, FILTER_SANITIZE_STRING);
$passwd= filter_var($passwd, FILTER_SANITIZE_STRING);

$forbidden_chars = array(
   "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&","#", "*", "(", ")" , "|", "~", "`", "{", "}", "%", "+" ,"-", chr(0));

$replace_chars = array(
  'áéíóúäëïöüàèìòùñ ',
  'aeiouaeiouaeioun_'
);

for( $i=0 ; $i < strlen($user) ; $i++ ) {
  $sane_char = $source_char = $str[$i];
  if ( in_array( $source_char, $forbidden_chars ) ) {
    $sane_char = "_";
    $saneUsr .= $sane_char;
    continue;
  }
  $pos = strpos( $replace_chars[0], $source_char);
  if ( $pos !== false ) {
    $sane_char = $replace_chars[1][$pos];
    $saneUsr .= $sane_char;
    continue;
  }
  if ( ord($source_char) < 32 || ord($source_char) > 128 ) {
    // Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales
    $sane_char = "_";
    $saneUsr .= $sane_char;
    continue;
  }
  // Si ha pasado todos los controles, aceptamos el carácter
  $saneUsr .= $sane_char;
}

for( $i=0 ; $i < strlen($passwd) ; $i++ ) {
	$sane_char = $source_char = $str[$i];
	if ( in_array( $source_char, $forbidden_chars ) ) {
	  $sane_char = "_";
	  $sanePwd .= $sane_char;
	  continue;
	}
	$pos = strpos( $replace_chars[0], $source_char);
	if ( $pos !== false ) {
	  $sane_char = $replace_chars[1][$pos];
	  $sanePwd .= $sane_char;
	  continue;
	}
	if ( ord($source_char) < 32 || ord($source_char) > 128 ) {
	  // Todos los caracteres codificados por debajo de 32 o encima de 128 son especiales
	  $sane_char = "_";
	  $sanePwd .= $sane_char;
	  continue;
	}
	// Si ha pasado todos los controles, aceptamos el carácter
	$sanePwd .= $sane_char;
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
