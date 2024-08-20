<?php

namespace oneid\jwt_auth;

use InvalidArgumentException;

class JwtConfig{
    var $privateKey, $issuer, $loginUrl, $tokenParam;

    function __construct($privateKey, $issuer, $loginUrl="", $tokenParam=DEFAULT_TOKEN_PARAM)
    {
        $this->privateKey = $privateKey;
        $this->issuer = $issuer;
        $this->loginUrl = $loginUrl;
        $this->tokenParam = $tokenParam;
    }

    function validate(){
        if (!isset($this->privateKey) || check_invalid_string($this->privateKey)){
            throw new InvalidArgumentException("invalid private_key");
        }
        if (!isset($this->issuer) || check_invalid_string($this->issuer)){
            throw new InvalidArgumentException("invalid issuer");
        }
    }

    function asClaim(): array{
        $currentTime = time();
        return array(ATTRIBUTE_ISSUER => $this->issuer,
            ATTRIBUTE_IAT_TIME => $currentTime,
            ATTRIBUTE_EXPIRE_TIME => $currentTime + TOKEN_EXPIRE_SECOND);
    }
}