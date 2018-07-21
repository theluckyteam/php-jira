<?php

namespace LuckyTeam\Jira\Entity;

use LuckyTeam\Jira\Util\IssuePropertyHelper as Property;

class ReadonlyIssue
{
    /**
     * @var array An array Issue of Jira
     */
    private $properties;

    /**
     * Issue constructor
     *
     * @param array $properties Array of Jira issue
     */
    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function getId(): integer
    {
        return $this->getProperty('id');
    }

    public function getLinks()
    {
        return $this->getProperty('issuelinks');
    }

    public function getTimeTracking()
    {
        return $this->getProperty('timetracking');
    }

    public function getIssueType()
    {
        return $this->getProperty('issuetype');
    }

    public function getPriority()
    {
        return $this->getProperty('priority');
    }

    public function getDescription()
    {
        return $this->getProperty('description');
    }

    public function getStatus()
    {
        return $this->getProperty('status');
    }

    public function getLabels()
    {
        return $this->getProperty('labels');
    }

    public function getComments()
    {
        return $this->getProperty('comment');
    }

    public function getAssignee()
    {
        return $this->getProperty('assignee');
    }

    public function getReporter()
    {
        return $this->getProperty('reporter');
    }

    public function getKey(): string
    {
        return $this->getProperty('key');
    }

    public function getProject(): array
    {
        return $this->getProperty('project');
    }

    public function getSummary(): string
    {
        return $this->getProperty('summary');
    }

    /**
     * Returns value of property
     *
     * @param string $name Name of property
     * @return mixed Value of property
     *
     * @throws \Exception If unknown property
     */
    private function getProperty($name)
    {
        if (!Property::exists($this->properties, $name)) {
            throw new \Exception();
        }

        return Property::get($this->properties, $name);
    }

    public function toArray()
    {
        return $this->properties;
    }
}
