<?php
namespace JiraWebhook;

use JiraWebhook\Models\JiraWebhookData;

abstract class JiraWebhookDataConverter
{
    /**
     * Convert $data into message
     *
     * @param JiraWebhookData $data - data from JIRA webhook
     * @return mixed
     */
    abstract public function convert(JiraWebhookData $data);
}