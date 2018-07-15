<?php

error_reporting(E_ALL);

use LuckyTeam\Jira\Entity\MocklyIssue;
use LuckyTeam\Jira\Util\IssuePropertyHelper as Property;
use PHPUnit\Framework\TestCase;

class IssuePropertyHelperTest extends TestCase
{
    public function testGetId()
    {
        $issueArray = (new MocklyIssue())->toArray();
        $this->assertTrue(Property::exists($issueArray, 'id'));
        $this->assertInternalType('int', Property::get($issueArray, 'id'));
        $this->assertEquals($issueArray['id'], Property::get($issueArray, 'id'));
    }

    public function testGetSummary()
    {
        $issueArray = (new MocklyIssue())->toArray();
        $this->assertTrue(Property::exists($issueArray, 'summary'));
        $this->assertInternalType('string', Property::get($issueArray, 'summary'));
        $this->assertEquals($issueArray['fields']['summary'], Property::get($issueArray, 'summary'));
        $this->assertNull(Property::get([], 'summary'));
    }

    public function testGetLinks()
    {
        $issueArray = (new MocklyIssue())->toArray();
        $this->assertTrue(Property::exists($issueArray, 'issuelinks'));
        $this->assertInternalType('array', Property::get($issueArray, 'issuelinks'));
        $this->assertEquals($issueArray['fields']['issuelinks'], Property::get($issueArray, 'issuelinks'));
    }

    public function testGetProject()
    {
        $issueArray = (new MocklyIssue())->toArray();
        $this->assertTrue(Property::exists($issueArray, 'project'));
        $this->assertInternalType('array', Property::get($issueArray, 'project'));
        $this->assertEquals($issueArray['fields']['project']['id'], Property::get($issueArray, 'project')['id']);
    }
}
