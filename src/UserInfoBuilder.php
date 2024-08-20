<?php
namespace oneid\jwt_auth;

class UserInfoBuilder {
    var $userInfo;

    function __construct($userId)
    {
        $this->userInfo = new UserInfo($userId);
    }

    function setName($name): UserInfoBuilder
    {
        $this->userInfo->name = $name;
        return $this;
    }

    function setPreferredUsername($preferredUsername): UserInfoBuilder
    {
        $this->userInfo->preferredUsername = $preferredUsername;
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

    function setExtension($extension): UserInfoBuilder
    {
        $this->userInfo->extension = $extension;
        return $this;
    }

    function build(): UserInfo
    {
        return $this->userInfo;
    }
}