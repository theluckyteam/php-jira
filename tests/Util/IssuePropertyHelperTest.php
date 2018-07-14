<?php

use LuckyTeam\Jira\Util\IssuePropertyHelper as Property;
use PHPUnit\Framework\TestCase;

class IssuePropertyHelperTest extends TestCase
{
    public function testGetId()
    {
        $issue = [
            'id' => 1
        ];

        $this->assertEquals(1, Property::get($issue, 'id'));
    }

    public function testGetSummary()
    {
        $issue = [
            'fields' => [
                'summary' => 'summary-value',
            ],
        ];

        $this->assertEquals('summary-value', Property::get($issue, 'summary'));
        $this->assertNull(Property::get([], 'summary'));
    }

    public function testGetLinks()
    {
        $issueLinks = [
            'id' => '26407',
            'self' => 'https://path.to.jira/rest/api/2/issueLink/26407',
            'type' => [
                'id' => '10000',
                'name' => 'Blocks',
                'inward' => 'is blocked by',
                'outward' => 'blocks',
                'self' => 'https://path.to.jira/rest/api/2/issueLinkType/10000',
            ],
            'inwardIssue' => [
                'id' => '40138',
                'key' => 'TASK-1760',
                'self' => 'https://path.to.jira/rest/api/2/issue/40138',
                'fields' => [
                    'summary' => 'Demonstrate the relationship between tasks',
                    'status' => [],
                    'priority' => [],
                    'issuetype' => [],
                ],
            ],
        ];

        $issue = [
            'fields' => [
                'issuelinks' => $issueLinks,
            ],
        ];

        $this->assertTrue(is_array(Property::get($issue, 'issuelinks')));
        $this->assertEquals($issueLinks, Property::get($issue, 'issuelinks'));
    }

    public function testGetP()
    {
        $issueProject = [
            'self' => 'https://path.to.jira/rest/api/2/project/10300',
            'id' => '10300',
            'key' => 'PROJECT',
            'name' => 'Project of Jira',
            'avatarUrls' => [
                '16x16' => 'https://path.to.jira/secure/projectavatar?size=xsmall&pid=10300&avatarId=10205',
                '24x24' => 'https://path.to.jira/secure/projectavatar?size=small&pid=10300&avatarId=10205',
                '32x32' => 'https://path.to.jira/secure/projectavatar?size=medium&pid=10300&avatarId=10205',
                '48x48' => 'https://path.to.jira/secure/projectavatar?pid=10300&avatarId=10205',
            ],
            'projectCategory' => [
                'id' => '10300',
                'self' => 'https://path.to.jira/rest/api/2/projectCategory/10300',
                'name' => 'Project of Jira category',
                'description' => 'Description project of Jira category',
            ],
        ];

        $issue = [
            'id' => '39772',
            'self' => 'https://path.to.jira/rest/api/2/issue/39772',
            'key' => 'PROJECT-100',
            'fields' => [
                'project' => $issueProject,
            ],
        ];

        $this->assertTrue(Property::exists($issue, 'project'));
        $this->assertTrue(is_array(Property::get($issue, 'project')));
        $this->assertEquals($issueProject, Property::get($issue, 'project'));
    }
}
