<?php

use LuckyTeam\Jira\Util\IssuePropertyHelper;
use PHPUnit\Framework\TestCase;

class IssuePropertyHelperTest extends TestCase
{
    public function testGetId()
    {
        $this->assertEquals(1, IssuePropertyHelper::get([
            'id' => 1
        ], 'id'));
    }

    public function testGetSummary()
    {
        $this->assertEquals('summary-value', IssuePropertyHelper::get([
            'fields' => [
                'summary' => 'summary-value',
            ]
        ], 'summary'));
        $this->assertNull(IssuePropertyHelper::get([], 'summary'));
    }
}
