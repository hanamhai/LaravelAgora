<?php 

include("../src/RtcTokenBuilder.php");

if(!isset($_POST['appId'])){
    $err = 'appId is required';
    return $err;
}
if(!isset($_POST['appCertificate'])){
    $err = 'appCertificate is required';
    return $err;
}
if(!isset($_POST['channelName'])){
    $err = 'channelName is required';
    return $err;
}

$appID = $_POST['appId'];
$appCertificate = $_POST['appCertificate'];
$channelName = $_POST['channelName'];
$uid = Null;
$uidStr = Null;
$role = RtcTokenBuilder::RoleAttendee;
$expireTimeInSeconds = 3600;
$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

$token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
$returnToken =  $token . PHP_EOL;

$token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
$returnTokens['tokenuth'] =   $token . PHP_EOL;

$jsonToken =  json_encode($returnToken) ;
// return $jsonToken;
echo $returnToken;
?>