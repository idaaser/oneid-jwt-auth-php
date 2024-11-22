# oneid-jwt-auth-php

OneID JWT auth sdk for PHP
> 支持PHP版本 >= 7

## 使用步骤
### 集成SDK
使用 `composer`依赖管理
```php
composer require idaaser/oneid-jwt-auth-php
```

### 使用SDK
> 使用案例参考：test/idp_test.php
1. 引入依赖：
```php
require_once __DIR__."/../vendor/autoload.php";

use oneid\jwt_auth\UserInfoBuilder;
use oneid\jwt_auth\JwtConfig;
use oneid\jwt_auth\JwtAuth;
```
2. 初始化配置
```php
$issuer = "oneid-jinrruan";
$loginUrl = "https://oauth2.eid-6.account.tencentcs.com/v1/sso/jwtp/1102878596482998272/1151383032381308928/kit/{app_type}";
$jwtConfig = new JwtConfig($primaryKey, $issuer, $loginUrl);
```
3. 生成免登url：
- 通过用户信息UserInfo生成(UserInfo中userId与name为必传参数，preferredUsername、email、mobile三个属性至少存在一个)
```php
$builder = new UserInfoBuilder("user_id-123", "jinrruan");
$extension = array("code"=>"1234", "state"=>"4321", "otherParam"=>"other");
$userInfo = $builder->setPreferredUsername("haha")
    ->setMobile("13007149***")
    ->setEmail("123@qq.com")
    ->setCustomAttributes($extension)->build();

$param = array("code"=>"12+3@?4", "state"=>"43+21", "otherParam"=>"other");
echo JwtAuth::generateLoginUrlWithUserInfo($jwtConfig, $userInfo, App_Tencent_Meeting, $param).PHP_EOL;
```