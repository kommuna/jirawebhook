<?php
namespace JiraWebhook;

use JiraWebhook\Models\JiraWebhookData;

interface JiraWebhookDataConverter
{
    /**
     * Convert $data into message
     *
     * @param JiraWebhookData $data parsed data from JIRA webhook
     * 
     * @return mixed
     */
    public function convert(JiraWebhookData $data);
}