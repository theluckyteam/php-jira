<?php

use LuckyTeam\Jira\Entity\CookieAuthSession;
use PHPUnit\Framework\TestCase;

class CookieAuthSessionTest extends TestCase
{
	public function testCreate()
    {
        $cookieName = 'cookie-name';
        $cookieValue = 'cookie-value';
        $authSession = new CookieAuthSession($cookieName, $cookieValue);

        $this->assertInstanceOf(CookieAuthSession::class, $authSession);
        $this->assertEquals($authSession->getName(), $cookieName);
        $this->assertEquals($authSession->getValue(), $cookieValue);
    }
}
