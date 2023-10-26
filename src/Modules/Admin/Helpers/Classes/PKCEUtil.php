<?php
namespace Modules\Admin\Helpers\Classes;
class PKCEUtil {
    public static function genCodeVerifier($length = 64) {
        return bin2hex(random_bytes($length));
    }

    public static function genCodeChallenge($codeVerifier) {
        $codeChallenge = self::base64_urlencode(hash('sha256', $codeVerifier, true));
        return $codeChallenge;
    }

    static function base64_urlencode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
