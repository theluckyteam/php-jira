<?php

error_reporting(E_ALL);

use LuckyTeam\Jira\Entity\MocklyIssue;
use LuckyTeam\Jira\Util\IssuePropertyHelper as Property;
use PHPUnit\Framework\TestCase;

class IssuePropertyHelperTest extends TestCase
{
    public function testGetsId()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'id'));
        $this->assertInternalType('int', Property::get($issueArray, 'id'));
        $this->assertEquals($issueArray['id'], Property::get($issueArray, 'id'));
    }

    public function testGetsSummary()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'summary'));
        $this->assertInternalType('string', Property::get($issueArray, 'summary'));
        $this->assertEquals($issueArray['fields']['summary'], Property::get($issueArray, 'summary'));
        $this->assertEquals($issueArray['fields']['summary'], $issue->getSummary());
        $this->assertNull(Property::get([], 'summary'));
    }

    public function testGetsLinks()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'issuelinks'));
        $this->assertInternalType('array', Property::get($issueArray, 'issuelinks'));
        $this->assertEquals($issueArray['fields']['issuelinks'], Property::get($issueArray, 'issuelinks'));
        $this->assertEquals($issueArray['fields']['issuelinks'], $issue->getLinks());
    }

    public function testGetsProject()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'project'));
        $this->assertInternalType('array', Property::get($issueArray, 'project'));
        $this->assertEquals($issueArray['fields']['project']['id'], Property::get($issueArray, 'project')['id']);
        $this->assertEquals($issueArray['fields']['project'], $issue->getProject());
    }

    public function testGetsTimeTracking()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'timetracking'));
        $this->assertInternalType('array', Property::get($issueArray, 'timetracking'));
        $this->assertEquals($issueArray['fields']['timetracking'], Property::get($issueArray, 'timetracking'));
        $this->assertEquals($issueArray['fields']['timetracking'], $issue->getTimeTracking());
    }

    public function testGetsIssueType()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'issuetype'));
        $this->assertInternalType('array', Property::get($issueArray, 'issuetype'));
        $this->assertEquals($issueArray['fields']['issuetype'], Property::get($issueArray, 'issuetype'));
        $this->assertEquals($issueArray['fields']['issuetype'], $issue->getIssueType());
    }

    public function testGetsPriority()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'priority'));
        $this->assertInternalType('array', Property::get($issueArray, 'priority'));
        $this->assertEquals($issueArray['fields']['priority'], Property::get($issueArray, 'priority'));
        $this->assertEquals($issueArray['fields']['priority'], $issue->getPriority());
    }

    public function testGetsStatus()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'status'));
        $this->assertInternalType('array', Property::get($issueArray, 'status'));
        $this->assertEquals($issueArray['fields']['status'], Property::get($issueArray, 'status'));
        $this->assertEquals($issueArray['fields']['status'], $issue->getStatus());
    }

    public function testGetsLabels()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'labels'));
        $this->assertInternalType('array', Property::get($issueArray, 'labels'));
        $this->assertEquals($issueArray['fields']['labels'], Property::get($issueArray, 'labels'));
        $this->assertEquals($issueArray['fields']['labels'], $issue->getLabels());
    }

    public function testGetsAssignee()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'assignee'));
        $this->assertInternalType('array', Property::get($issueArray, 'assignee'));
        $this->assertEquals($issueArray['fields']['assignee'], Property::get($issueArray, 'assignee'));
        $this->assertEquals($issueArray['fields']['assignee'], $issue->getAssignee());
    }

    public function testGetsReporter()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'reporter'));
        $this->assertInternalType('array', Property::get($issueArray, 'reporter'));
        $this->assertEquals($issueArray['fields']['reporter'], Property::get($issueArray, 'reporter'));
        $this->assertEquals($issueArray['fields']['reporter'], $issue->getReporter());
    }

    public function testGetsDescription()
    {
        $issue = new MocklyIssue();
        $issueArray = $issue->toArray();
        $this->assertTrue(Property::exists($issueArray, 'description'));
        $this->assertInternalType('string', Property::get($issueArray, 'description'));
        $this->assertEquals($issueArray['fields']['description'], Property::get($issueArray, 'description'));
        $this->assertEquals($issueArray['fields']['description'], $issue->getDescription());
    }
}
