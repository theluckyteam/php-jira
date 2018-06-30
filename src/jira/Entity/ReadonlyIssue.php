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

    public function getKey(): string
    {
        return $this->getProperty('key');
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
}
