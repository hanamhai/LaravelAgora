<?php
namespace App\Services;

use AgoraRTC\RtcTokenBuilder;

class AgoraService
{
    public function getRtcToken(string $appId, string $appCertificate, string $channel): string
    {
        $tokenBuilder = new RtcTokenBuilder();

        $tokenBuilder->setAppId($appId);
        $tokenBuilder->setAppCertificate($appCertificate);
        $tokenBuilder->setChannelName($channel);
        $tokenBuilder->setUid(0);

        return $tokenBuilder->generateToken();
    }
}
?>