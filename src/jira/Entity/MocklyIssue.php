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
            ],
        ];

        parent::__construct(array_merge_recursive($mocklyProperties, $properties));
    }
}
