<?php

use oneid\jwt_auth\JWTAuth\UserInfo;
use PHPUnit\Framework\TestCase;

class UserInfoTest extends TestCase {
    public function testEmptyUserID() {
        $this->expectException(\InvalidArgumentException::class);
        $userInfo = new UserInfo(" ", "name");
    }

    public function testNullUserID()
    {
        $this->expectException(\InvalidArgumentException::class);
        $userInfo = new UserInfo(null, "name");
    }

    public function testNullName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $userInfo = new UserInfo("id", null);
    }

    public function testEmptyName(){
        $this->expectException(\InvalidArgumentException::class);
        $userInfo = new UserInfo("id", " ");
    }

    public function testTrimmedUserIDAndName(){
        $user = new UserInfo(" id ", "\rname\n");
        $this->assertEquals('id', $user->userId);
        $this->assertEquals('name', $user->name);
    }

    public function testAllEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $user = new UserInfo(" id ", "\rname\n");
        $user->validate();
    }

    public function testValidUser()
    {
        $this->expectNotToPerformAssertions();
        $user = new UserInfo("id", "name");
        $user->setUsername("username");
        $user->validate();
    }

    public function testAttr()
    {
        try {
            $user = new UserInfo("id", "name");
            $user = $user->setUsername("username")
                ->setEmail("test@test.com")
                ->setMobile("+86 13213458923")
                ->setCustomAttributes(array("foo" => "bar"));
            $user->validate();
            $this->assertEquals("username", $user->username);
            $this->assertEquals("test@test.com", $user->email);
            $this->assertEquals("+86 13213458923", $user->mobile);
            $this->assertEquals("bar", $user->customAttributes["foo"]);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}