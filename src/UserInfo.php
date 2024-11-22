<?php

namespace oneid\jwt_auth;

use InvalidArgumentException;

class UserInfo {
    var $userId, $name;
    var $username, $email, $mobile;
    var $customAttributes;

    function __construct($userId, $name)
    {
        $this->userId = $userId;
        $this->name = $name;
    }

    function validate() {
        if (!isset($this->userId) || check_invalid_string($this->userId)){
            throw new InvalidArgumentException("userId must not be empty");
        }
        if (!isset($this->name) || check_invalid_string($this->name)){
            throw new InvalidArgumentException("name must not be empty");
        }
        if ((!isset($this->username) || check_invalid_string($this->username)) &&
            (!isset($this->email) || check_invalid_string($this->email)) &&
            (!isset($this->mobile) || check_invalid_string($this->mobile))){
            throw new InvalidArgumentException("preferred_user_name/email/mobile must not all empty");
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