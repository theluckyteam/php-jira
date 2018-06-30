<?php

use LuckyTeam\Jira\Entity\ReadonlyIssue as Issue;
use PHPUnit\Framework\TestCase;

class ReadonlyIssueTest extends TestCase
{
    public function testCreate()
    {
        $issue = new Issue([]);

        $this->assertInstanceOf(Issue::class, $issue);
    }
}
