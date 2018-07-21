<?php

namespace LuckyTeam\Jira\Util;

class IssuePropertyHelper
{
    private static $properties = [
        'id' => 1,
        'key' => 1,
        'summary' => 1,
        'project' => 1,
        'issuelinks' => 1,
        'timetracking' => 1,
        'issuetype' => 1,
        'priority' => 1,
        'description' => 1,
        'status' => 1,
        'labels' => 1,
        'comment' => 1,
        'assignee' => 1,
        'reporter' => 1,
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
                case 'project':
                case 'issuelinks':
                case 'timetracking':
                case 'issuetype':
                case 'priority':
                case 'description':
                case 'status':
                case 'labels':
                case 'comment':
                case 'assignee':
                case 'reporter':
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
            case 'project':
            case 'issuelinks':
            case 'timetracking':
            case 'issuetype':
            case 'priority':
            case 'description':
            case 'status':
            case 'labels':
            case 'comment':
            case 'assignee':
            case 'reporter':
                if (isset($issue['fields'][$property])) {
                    return $issue['fields'][$property];
                }
                break;
        }

        return null;
    }
}
