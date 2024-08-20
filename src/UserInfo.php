<?php

namespace oneid\jwt_auth;

use InvalidArgumentException;

class UserInfo {
    var $userId;
    var $name, $preferredUsername, $email, $mobile;
    var $extension;

    function __construct($userId)
    {
        $this->userId = $userId;
    }

    function validate() {
        if (!isset($this->userId) || check_invalid_string($this->userId)){
            throw new InvalidArgumentException("userId must not be empty");
        }
        if ((!isset($this->preferredUsername) || check_invalid_string($this->preferredUsername)) &&
            (!isset($this->email) || check_invalid_string($this->email)) &&
            (!isset($this->mobile) || check_invalid_string($this->mobile))){
            throw new InvalidArgumentException("preferred_user_name/email/mobile must not all empty");
        }
    }

    function asClaims(): array{
        $result = array(ATTRIBUTE_SUBJECT => $this->userId);
        if (isset($this->name)){
            $result[ATTRIBUTE_NAME] = $this->name;
        }
        if (isset($this->preferredUsername)){
            $result[ATTRIBUTE_PREFERRED_USERNAME] = $this->preferredUsername;
        }
        if (isset($this->email)){
            $result[ATTRIBUTE_EMAIL] = $this->email;
        }
        if (isset($this->mobile)){
            $result[ATTRIBUTE_PHONE_NUMBER] = $this->mobile;
        }
        if (isset($this->extension) && count($this->extension)){
            foreach ($this->extension as $item=>$value){
                $result[$item] = $value;
            }
        }
        return $result;
    }
}