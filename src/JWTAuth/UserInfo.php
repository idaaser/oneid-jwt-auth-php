<?php

namespace oneid\jwt_auth\JWTAuth;

use InvalidArgumentException;

/**
 * 用户信息
 */
class UserInfo {
    /**
     * 必填: 用户唯一标识
     * @var string
     */
    var $id;
    /**
     * 必填: 用户显示名
     * @var string
     */
    var $name;
    /**
     * 建议填写: 用户登录名，1-64个英文字符或数字，用户登录名、邮箱、手机号三者必须提供一个
     * @var string
     */
    var $username;
    /**
     * 选填: 邮箱，用户登录名、邮箱、手机号三者必须提供一个
     * @var string
     */
    var $email;
    /**
     * 选填: 手机号，用户登录名、邮箱、手机号三者必须提供一个
     * @var string
     */
    var $mobile;
    /**
     * 其他需要放到id_token里的属性
     * @var array
     */
    var $extension;

    /**
     * constructor
     *
     * @param string $id 用户唯一标识
     * @param string $name 用户显示名
     */
    function __construct($id, $name)
    {
        if (!isset($id) || check_invalid_string($id)){
            throw new InvalidArgumentException("id must not be empty");
        }
        if (!isset($name) || check_invalid_string($name)){
            throw new InvalidArgumentException("name must not be empty");
        }

        $this->id = trim($id);
        $this->name = trim($name);
    }

    /**
     * 设置用户登录名
     *
     * @param string $username 用户登录名，1-64个英文字符或数字，用户登录名、邮箱、手机号三者必须提供一个
     * @return $this
     */
    function setUsername($username): UserInfo {
        if (!isset($username) || check_invalid_string($username)){
            throw new InvalidArgumentException("username must not be empty");
        }
        $this->username = trim($username);
        return $this;
    }

    /**
     * 设置邮箱
     *
     * @param string $email 邮箱
     * @return $this
     */
    function setEmail($email): UserInfo {
        if (!isset($email) || check_invalid_string($email)){
            throw new InvalidArgumentException("email must not be empty");
        }
        $this->email = trim($email);
        return $this;
    }

    /**
     * 设置手机号
     *
     * @param string $mobile 手机号
     * @return $this
     */
    function setMobile($mobile): UserInfo {
        if (!isset($mobile) || check_invalid_string($mobile)){
            throw new InvalidArgumentException("mobile must not be empty");
        }
        $this->mobile = trim($mobile);
        return $this;
    }

    /**
     * 设置其他属性
     *
     * @param array $extension 属性数据
     * @return $this
     */
    function setExtension($extension): UserInfo
    {
        $this->extension = $extension;
        return $this;
    }

    function validate() {
        if ((!isset($this->username) || check_invalid_string($this->username)) &&
            (!isset($this->email) || check_invalid_string($this->email)) &&
            (!isset($this->mobile) || check_invalid_string($this->mobile))){
            throw new InvalidArgumentException("username/email/mobile must not all be empty");
        }
    }

    function asClaims(): array{
        $result = array(CLAIM_SUBJECT=> $this->id);
        if (isset($this->name)){
            $result[CLAIM_NAME] = $this->name;
        }
        if (isset($this->username)){
            $result[CLAIM_PREFERRED_USERNAME] = $this->username;
        }
        if (isset($this->email)){
            $result[CLAIM_EMAIL] = trim($this->email);
        }
        if (isset($this->mobile)){
            $result[CLAIM_PHONE_NUMBER] = trim($this->mobile);
        }
        if (isset($this->extension) && count($this->extension)){
            foreach ($this->extension as $item=> $value){
                $result[$item] = $value;
            }
        }
        return $result;
    }
}
