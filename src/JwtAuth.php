<?php
namespace oneid\jwt_auth;

use Firebase\JWT\JWT;
use InvalidArgumentException;

class JwtAuth{
    static function generateTokenWithUserInfo($jwtConfig, $userInfo): string{
        // 1、参数校验
        if (!$userInfo instanceof UserInfo){
            throw new InvalidArgumentException("invalid userInfo");
        }
        $userInfo->validate();
        // 2、使用私钥生成签名
        return self::generateTokenWithClaims($jwtConfig, $userInfo->asClaims());
    }

    static function generateTokenWithClaims($jwtConfig, $claims): string{
        // 1、参数校验
        if (!$jwtConfig instanceof JwtConfig){
            throw new InvalidArgumentException("invalid jwtConfig");
        }
        $jwtConfig->validate();
        if ($claims == null || !is_array($claims)){
            throw new InvalidArgumentException("invalid claims");
        }
        // 2、payload封装
        $payload = $jwtConfig->asClaim();
        $payload += $claims;
        // 3、使用私钥生成签名
        return JWT::encode($payload, $jwtConfig->privateKey, DEFAULT_ALGORITHM);
    }

    static function generateLoginUrlWithUserInfo($jwtConfig, $userInfo, $app=null, $params=null): string{
        // 1、参数校验
        if (!$userInfo instanceof UserInfo){
            throw new InvalidArgumentException("invalid userInfo");
        }
        $userInfo->validate();
        // 2、生成url
        return self::generateLoginUrlWithClaims($jwtConfig, $userInfo->asClaims(), $app, $params);
    }

    static function generateLoginUrlWithClaims($jwtConfig, $claims, $app=null, $params=null): string{
        // 1、生成token
        $token = self::generateTokenWithClaims($jwtConfig, $claims);

        // 2、拼接loginUrl
        if (!isset($jwtConfig->loginUrl) || $jwtConfig->loginUrl == null || !is_string($jwtConfig->loginUrl) || strlen(trim($jwtConfig->loginUrl)) == 0){
            throw new InvalidArgumentException("invalid login_url");
        }
        // 2.1、url部分
        $baseUrl = $jwtConfig->loginUrl;
        if (strpos($baseUrl, APP_TYPE_PARAM) !== false){
            if (check_invalid_string($app)){
                throw new InvalidArgumentException("invalid app");
            }
            $baseUrl = str_replace(APP_TYPE_PARAM, $app, $baseUrl);
        }
        // 2.2、参数部分
        $queryParams = array($jwtConfig->tokenParam=>$token);
        $pos = strpos($baseUrl, "?");
        if ($pos !== false){
            parse_str(substr($baseUrl, $pos + 1), $existParams);
            $baseUrl = substr($baseUrl, $pos);
            $queryParams = array_merge($queryParams, $existParams);
        }
        if ($params !== null){
            if (!is_array($params)){
                throw new InvalidArgumentException("invalid params");
            }
            $queryParams = array_merge($queryParams, $params);
        }
        return $baseUrl."?".http_build_query($queryParams);
    }
}

function check_invalid_string($param): bool{
    return $param == null || !is_string($param) || strlen(trim($param)) == 0;
}