<?php

namespace LuckyTeam\Jira\Entity;

/**
 * Class CookieAuthSession
 * @package LuckyTeam\Jira\Entity
 */
class CookieAuthSession
{
    /**
     * @var string Value of cookie name
     */
    private $name;

    /**
     * @var string Value of cookie
     */
    private $value;

    /**
     * CookieAuthSession constructor
     *
     * @param string $name Value of cookie name
     * @param string $value Value of cookie
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Returns value of cookie name
     *
     * @return string Value of cookie name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns value of cookie name
     *
     * @return string Value of cookie
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
