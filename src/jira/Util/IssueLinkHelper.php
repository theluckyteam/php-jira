<?php

namespace LuckyTeam\Jira\Util;

/**
 * Class IssueLinkHelper
 * @package LuckyTeam\Jira\Util
 */
class IssueLinkHelper
{
    /**
     * Returns name of link from Issue link
     *
     * @param array $issueLink An array of Issue link
     *
     * @return string Name of link
     */
    public static function getLinkName($issueLink)
    {
        $linkName = null;

        if (isset($issueLink['outwardIssue'])) {
            if (isset($issueLink['outwardIssue']['key'], $issueLink['type']['outward'])) {
                $linkName = $issueLink['type']['outward'];
            }
        } elseif (isset($issueLink['inwardIssue'], $issueLink['type']['inward'])) {
            if (isset($issueLink['inwardIssue']['key'])) {
                $linkName = $issueLink['type']['inward'];
            }
        }

        return $linkName;
    }

    /**
     * Returns key of linked Issue from Issue link
     *
     * @param array $issueLink An array of Issue link
     *
     * @return string Key of linked Issue
     */
    public static function getLinkedIssueKey($issueLink)
    {
        $linkedIssueKey = null;

        if (isset($issueLink['outwardIssue'])) {
            if (isset($issueLink['outwardIssue']['key'], $issueLink['type']['outward'])) {
                $linkedIssueKey = $issueLink['outwardIssue']['key'];
            }
        } elseif (isset($issueLink['inwardIssue'], $issueLink['type']['inward'])) {
            if (isset($issueLink['inwardIssue']['key'])) {
                $linkedIssueKey = $issueLink['inwardIssue']['key'];
            }
        }

        return $linkedIssueKey;
    }
}
