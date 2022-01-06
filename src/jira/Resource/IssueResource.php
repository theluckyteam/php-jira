<?php

namespace LuckyTeam\Jira\Resource;

class IssueResource extends ApiResource
{
    public function search($query)
    {
        $this->jsonBodyContent(
            $this->post('rest/api/2/search', [
                'json' => $query,
            ])
        );
    }
}
