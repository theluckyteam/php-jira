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
}
