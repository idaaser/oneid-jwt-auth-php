<?php

namespace oneid\jwt_auth\JWTAuth;

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

class Signer{
    var $privateKey, $issuer, $loginUrl, $tokenKey, $lifetime;

    /**
     * constructor
     *
     * @param string $privateKey 私钥
     * @param string $issuer 发起登录方的应用标识
     * @param string $loginBaseUrl OneID JWT认证源页面提供的登录链接
     * @param int $token_lifetime 免登链接中token有效期
     */
    function __construct($privateKey, $issuer, $loginBaseUrl, $token_lifetime=TOKEN_EXPIRE_SECOND)
    {
        if (!isset($privateKey) || check_invalid_string($privateKey)){
            throw new InvalidArgumentException("private_key must not be empty");
        }
        $normalizedKey = self::normalizePrivateKey($privateKey);
        $parsedKey = openssl_pkey_get_private($normalizedKey);
        if (!$parsedKey){
            throw new InvalidArgumentException("private_key is invalid");
        }

        if (!isset($issuer) || check_invalid_string($issuer)){
            throw new InvalidArgumentException("issuer must not be empty");
        }
        if (!isset($loginBaseUrl) || check_invalid_string($loginBaseUrl)){
            throw new InvalidArgumentException("loginUrl must not be empty");
        }

        $baseUrl = $loginBaseUrl;
        if (strpos($baseUrl, APP_TYPE_PARAM) !== false){
            $baseUrl = str_replace(APP_TYPE_PARAM, "test", $baseUrl);
        }
        if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("loginUrl is invalid");
        }
        if ($token_lifetime <= 0 || $token_lifetime > 300){
            throw new InvalidArgumentException("lifetime must be greater than 0 and less than or equal to 300 seconds");
        }

        $this->privateKey = $parsedKey;
        $this->issuer = $issuer;
        $this->loginUrl = $loginBaseUrl;
        $this->tokenKey = DEFAULT_TOKEN_KEY;
        $this->lifetime = $token_lifetime;
    }

    /**
     * 初始化JWT认证签发器
     *
     * @param string $keyFilePath 私钥文件路径
     * @param string $issuer 发起登录方的应用标识
     * @param string $loginBaseUrl OneID JWT认证源页面提供的登录链接
     * @param int $token_lifetime 免登链接中token有效期
     * @return Signer
     */
    public static function newSignerFromKeyFile($keyFilePath, $issuer, $loginBaseUrl, $token_lifetime=TOKEN_EXPIRE_SECOND): Signer
    {
        $privateKeyRaw = file_get_contents($keyFilePath);
        return new static($privateKeyRaw, $issuer, $loginBaseUrl, $token_lifetime);
    }

    private function generateToken($userInfo): string{
        // 1、参数校验
        if (!$userInfo instanceof UserInfo){
            throw new InvalidArgumentException("invalid userInfo");
        }
        $userInfo->validate();
        // 2、使用私钥生成签名
        return $this->generateTokenWithClaims($userInfo->asClaims());
    }


    /**
     * 为指定用户创建一个免登应用的url
     *
     * @param UserInfo $user 免登用户的信息
     * @param string $app 免登应用的唯一标识
     * @param array $params 表示自定义的key/value键值对(以query param的方式追加到免登链接之后)
     * @return string 免登链接
     */
    public function newLoginURL($user, $app, $params): string
    {
        if (!isset($app) || check_invalid_string($app)) {
            throw new InvalidArgumentException("issuer must not be empty");
        }

        $token = $this->generateToken($user);

        $baseUrl = $this->loginUrl;
        if (strpos($baseUrl, APP_TYPE_PARAM) !== false){
            $baseUrl = str_replace(APP_TYPE_PARAM, $app, $baseUrl);
        }
        // 2.2、参数部分
        $queryParams = array($this->tokenKey=>$token);
        $parsedURL = parse_url($baseUrl);
        parse_str($parsedURL['query'], $paramArray);
        $queryParams = array_merge($queryParams, $paramArray);
        if ($params !== null){
            if (!is_array($params)){
                throw new InvalidArgumentException("invalid params");
            }
            foreach ($params as $key => $value){
                if (is_string($key) && $key != "" && is_string($value) && $value != "" ){
                    $queryParams[$key] = $value;
                }
            }
        }
        $parsedURL['query'] = http_build_query($queryParams);
        return http_build_url($parsedURL);
    }

    private function generateTokenWithClaims($claims): string{
        // 1、参数校验
        if ($claims == null || !is_array($claims)){
            throw new InvalidArgumentException("invalid claims");
        }
        // 2、payload封装
        $payload = $this->generate_standard_claims();
        $payload += $claims;

        // 3、使用私钥生成签名
        return JWT::encode($payload, $this->privateKey, DEFAULT_ALGORITHM);
    }

    private static function normalizePrivateKey($privateKey): string
    {
        $privateKey = trim($privateKey);
        $prefix = "-----BEGIN PRIVATE KEY-----";
        $suffix = "-----END PRIVATE KEY-----";
        if (substr($privateKey, 0, strlen($prefix)) === $prefix){
            $privateKey = substr($privateKey, strlen($prefix));
            $privateKey = substr($privateKey, 0, strlen($privateKey)-strlen($suffix));
            $privateKey = trim($privateKey);
        }

        return $prefix."\r\n".$privateKey."\r\n".$suffix;
    }

    private function generate_standard_claims(): array{
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
