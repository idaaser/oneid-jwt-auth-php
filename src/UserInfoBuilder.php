<?php
namespace oneid\jwt_auth;

class UserInfoBuilder {
    var $userInfo;

    function __construct($userId, $name)
    {
        $this->userInfo = new UserInfo($userId, $name);
    }

    function setUsername($username): UserInfoBuilder
    {
        $this->userInfo->username = $username;
        return $this;
    }

    function setEmail($email): UserInfoBuilder
    {
        $this->userInfo->email = $email;
        return $this;
    }

    function setMobile($mobile): UserInfoBuilder
    {
        $this->userInfo->mobile = $mobile;
        return $this;
    }

    function setCustomAttributes($customAttributes): UserInfoBuilder
    {
        $this->userInfo->customAttributes = $customAttributes;
        return $this;
    }

    function build(): UserInfo
    {
        return $this->userInfo;
    }
}