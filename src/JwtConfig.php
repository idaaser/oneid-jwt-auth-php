<?php

namespace oneid\jwt_auth;

use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

class JwtConfig{
    var $privateKey, $issuer, $loginUrl, $tokenKey, $lifetime;

    function __construct($privateKey, $issuer, $lifetime=TOKEN_EXPIRE_SECOND, $loginUrl="",
                         $tokenKey=DEFAULT_TOKEN_KEY)
    {
        $this->privateKey = $privateKey;
        $this->issuer = $issuer;
        $this->loginUrl = $loginUrl;
        $this->tokenKey = $tokenKey;
        $this->lifetime = $lifetime;
    }

    function validate(){
        if (!isset($this->privateKey) || check_invalid_string($this->privateKey)){
            throw new InvalidArgumentException("invalid private_key");
        }
        if (!isset($this->issuer) || check_invalid_string($this->issuer)){
            throw new InvalidArgumentException("invalid issuer");
        }
        if ($this->lifetime <= 0 || $this->lifetime > 300) {
            throw new InvalidArgumentException("invalid lifetime");
        }
    }

    function asClaim(): array{
        $currentTime = time();
        $uuid4 = Uuid::uuid4();
        $uuid4_hex = $uuid4->getHex();
        return array(CLAIM_ISSUER=> $this->issuer,
            CLAIM_AUDIENCE=>App_Tencent_OneID,
            CLAIM_JWT_ID=>$uuid4_hex,
            CLAIM_ISSUE_AT=> $currentTime,
            CLAIM_EXPIRY=> $currentTime + $this->lifetime,
            );
    }
}