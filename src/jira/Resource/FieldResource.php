<?php

namespace LuckyTeam\Jira\Resource;

class FieldResource extends ApiResource
{
    public function all()
    {
        $this->jsonBodyContent(
            $this->get('rest/api/2/field')
        );
    }
}
