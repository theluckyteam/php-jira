<?php
/**
 * Created by PhpStorm.
 * User: mironov
 * Date: 15.07.18
 * Time: 9:51
 */

namespace LuckyTeam\Jira\Entity;


class MocklyIssue extends ReadonlyIssue
{
    public function __construct($properties = [])
    {
        $mocklyProperties = [
            'id' => 1,
            'self' => 'https://path.to.jira/rest/api/2/issue/1',
            'key' => 'EXAMPLE-1',
            'fields' => [
                'summary' => 'The example of task in Jira',
                'project' => [
                    'id' => 10300,
                    'self' => 'https://path.to.jira/rest/api/2/project/10300',
                    'key' => 'PROJECT',
                    'name' => 'Project of Jira',
                    'avatarUrls' => [
                        '16x16' => 'https://path.to.jira/secure/projectavatar?size=xsmall&pid=10300&avatarId=10205',
                        '24x24' => 'https://path.to.jira/secure/projectavatar?size=small&pid=10300&avatarId=10205',
                        '32x32' => 'https://path.to.jira/secure/projectavatar?size=medium&pid=10300&avatarId=10205',
                        '48x48' => 'https://path.to.jira/secure/projectavatar?pid=10300&avatarId=10205',
                    ],
                    'projectCategory' => [
                        'id' => 10300,
                        'self' => 'https://path.to.jira/rest/api/2/projectCategory/10300',
                        'name' => 'Project of Jira category',
                        'description' => 'Description project of Jira category',
                    ],
                ],
                'timetracking' => [
                    'originalEstimate' => '2h',
                    'remainingEstimate' => '1h',
                    'timeSpent' => '1h',
                    'originalEstimateSeconds' => 7200,
                    'remainingEstimateSeconds' => 3600,
                    'timeSpentSeconds' => 3600,
                ],
                'description' => 'The example of description in Jira',
                'status' => [
                    'self' => 'https://path.to.jira/rest/api/2/status/4',
                    'description' => 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.',
                    'iconUrl' => 'https://path.to.jira/images/icons/statuses/reopened.png',
                    'name' => 'Reopened',
                    'id' => 4,
                    'statusCategory' => [
                        'self' => 'https://jira.svyaznoy.ru/rest/api/2/statuscategory/2',
                        'id' => 2,
                        'key' => 'new',
                        'colorName' => 'blue-gray',
                        'name' => 'New',
                    ],
                ],
                'labels' => ['example'],
                'issuetype' => [
                    'self' => 'https://path.to.jira/rest/api/2/issuetype/3',
                    'id' => '3',
                    'description' => 'A task that needs to be done.',
                    'iconUrl' => 'https://path.to.jira/images/icons/issuetypes/task.png',
                    'name' => 'Task',
                    'subtask' => false,
                ],
                'priority' => [
                    'self' => 'https://path.to.jira/rest/api/2/priority/3',
                    'iconUrl' => 'https://path.to.jira/images/icons/priorities/major.png',
                    'name' => 'Major',
                    'id' => '3',
                ],
                'issuelinks' => [
                    [
                        'id' => 10,
                        'self' => 'https://path.to.jira/rest/api/2/issueLink/10',
                        'type' => [
                            'id' => '10000',
                            'name' => 'Blocks',
                            'inward' => 'is blocked by',
                            'outward' => 'blocks',
                            'self' => 'https://path.to.jira/rest/api/2/issueLinkType/10000',
                        ],
                        'inwardIssue' => [
                            'id' => 2,
                            'key' => 'EXAMPLE-2',
                            'self' => 'https://path.to.jira/rest/api/2/issue/2',
                            'fields' => [
                                'summary' => 'Demonstrate the relationship between tasks',
                            ],
                        ],
                    ]
                ],
                'assignee' => [
                    'self' => 'https://path.to.jira/rest/api/2/user?username=ypihtarev',
                    'name' => 'username',
                    'emailAddress' => 'username@example.ru',
                    'avatarUrls' =>
                        array (
                            '16x16' => 'https://path.to.jira/secure/useravatar?size=xsmall&ownerId=ypihtarev&avatarId=11906',
                            '24x24' => 'https://path.to.jira/secure/useravatar?size=small&ownerId=ypihtarev&avatarId=11906',
                            '32x32' => 'https://path.to.jira/secure/useravatar?size=medium&ownerId=ypihtarev&avatarId=11906',
                            '48x48' => 'https://path.to.jira/secure/useravatar?ownerId=ypihtarev&avatarId=11906',
                        ),
                    'displayName' => 'Display Username',
                    'active' => true,
                ],
                'reporter' => [
                    'self' => 'https://path.to.jira/rest/api/2/user?username=ypihtarev',
                    'name' => 'username',
                    'emailAddress' => 'username@example.ru',
                    'avatarUrls' =>
                        array (
                            '16x16' => 'https://path.to.jira/secure/useravatar?size=xsmall&ownerId=ypihtarev&avatarId=11906',
                            '24x24' => 'https://path.to.jira/secure/useravatar?size=small&ownerId=ypihtarev&avatarId=11906',
                            '32x32' => 'https://path.to.jira/secure/useravatar?size=medium&ownerId=ypihtarev&avatarId=11906',
                            '48x48' => 'https://path.to.jira/secure/useravatar?ownerId=ypihtarev&avatarId=11906',
                        ),
                    'displayName' => 'Display Username',
                    'active' => true,
                ],
            ],
        ];

        parent::__construct(array_merge_recursive($mocklyProperties, $properties));
    }
}
