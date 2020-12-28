<?php
define("RECAPTCHA_V3_SECRET_KEY", '6LcKgPsUAAAAAOBGECITM_likZT0uqCNi8OdUgeB');

if (isset($_POST['correo']) && $_POST['correo']) {

    $email = filter_var($_POST['correo'], FILTER_SANITIZE_STRING);
} else {
    // set error message and redirect back to form...
    echo "mal";
    var_dump($_POST);
    echo $_POST['correo'];
    exit;
}

$token = $_POST['token'];
$action = $_POST['action'];

// call curl to POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$arrResponse = json_decode($response, true);

// verify the response
if($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
    // valid submission
    // go ahead and do necessary stuff
} else {
    // spam submission
    // show error message
}