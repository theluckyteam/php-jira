<?php

namespace LuckyTeam\Jira\Util;

class IssuePropertyHelper
{
    private static $properties = [
        'id' => 1,
        'key' => 1,
        'summary' => 1,
    ];

    public static function exists($issue, $property): bool
    {
        if (array_key_exists($property, self::$properties)) {
            switch ($property) {
                case 'id':
                case 'key':
                    return isset($issue[$property]);
                    break;

                case 'summary':
                    return isset($issue['fields'][$property]);
                    break;
            }
        }

        return false;
    }

    public static function get($issue, $property)
    {
        switch ($property) {
            case 'id':
            case 'key':
                if (isset($issue[$property])) {
                    return $issue[$property];
                }
                break;

            case 'summary':
                if (isset($issue['fields'][$property])) {
                    return $issue['fields'][$property];
                }
                break;
        }

        return null;
    }
}
