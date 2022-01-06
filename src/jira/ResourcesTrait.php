<?php

namespace LuckyTeam\Jira;

use LuckyTeam\Jira\Resource\FieldResource;
use LuckyTeam\Jira\Resource\IssueResource;

trait ResourcesTrait
{
    public function issues(): IssueResource
    {
        return new IssueResource($this->getClient());
    }

    public function fields(): FieldResource
    {
        return new FieldResource($this->getClient());
    }

    protected abstract function getClient(): CookieSessionClient;
}
