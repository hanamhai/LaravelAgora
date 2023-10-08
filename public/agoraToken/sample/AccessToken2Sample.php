<?php
include("../src/AccessToken2.php");

$appID = "f3c1e9db103a4dc380355a2ab5d200fd";
$appCertificate = "6145c304d6ec41d48f7b6fb16b43fab3";
$channelName = "agora3003";
$uid = 2882341273;
$uidStr = "2882341273";
$expireTimeInSeconds = 600;

// $token = RtmTokenBuilder::buildToken($appID, $appCertificate, $user, $role, $privilegeExpiredTs);
$accessToken = new AccessToken2($appID, $appCertificate, $expireTimeInSeconds);

// grant rtc privileges
$serviceRtc = new ServiceRtc($channelName, $uid);
$serviceRtc->addPrivilege($serviceRtc::PRIVILEGE_JOIN_CHANNEL, $expireTimeInSeconds);
$accessToken->addService($serviceRtc);

// grant rtm privileges
$serviceRtm = new ServiceRtm($uidStr);
$serviceRtm->addPrivilege($serviceRtm::PRIVILEGE_LOGIN, $expireTimeInSeconds);
$accessToken->addService($serviceRtm);

// grant chat privileges
$serviceChat = new ServiceChat($uidStr);
$serviceChat->addPrivilege($serviceChat::PRIVILEGE_USER, $expireTimeInSeconds);
$accessToken->addService($serviceChat);

$token = $accessToken->build();
echo 'Token with RTC, RTM, CHAT privileges: ' . $token . PHP_EOL;

?>
