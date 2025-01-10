<?php

namespace oneid\jwt_auth\JWTAuth;

use InvalidArgumentException;

class UserInfo {
    var $userId, $name;
    var $username, $email, $mobile;
    var $customAttributes;

    function __construct($userId, $name)
    {
        if (!isset($userId) || check_invalid_string($userId)){
            throw new InvalidArgumentException("userId must not be empty");
        }
        if (!isset($name) || check_invalid_string($name)){
            throw new InvalidArgumentException("name must not be empty");
        }

        $this->userId = trim($userId);
        $this->name = trim($name);
    }

    function setUsername($username): UserInfo {
        $this->username = $username;
        return $this;
    }

    function setEmail($email): UserInfo {
        $this->email = $email;
        return $this;
    }

    function setMobile($mobile): UserInfo {
        $this->mobile = $mobile;
        return $this;
    }

    function setCustomAttributes($customAttributes): UserInfo
    {
        $this->customAttributes = $customAttributes;
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
        $result = array(CLAIM_SUBJECT=> $this->userId);
        if (isset($this->name)){
            $result[CLAIM_NAME] = $this->name;
        }
        if (isset($this->username)){
            $result[CLAIM_PREFERRED_USERNAME] = $this->username;
        }
        if (isset($this->email)){
            $result[CLAIM_EMAIL] = $this->email;
        }
        if (isset($this->mobile)){
            $result[CLAIM_PHONE_NUMBER] = $this->mobile;
        }
        if (isset($this->customAttributes) && count($this->customAttributes)){
            foreach ($this->customAttributes as $item=> $value){
                $result[$item] = $value;
            }
        }
        return $result;
    }
}
