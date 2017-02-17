<?php
namespace JiraWebhook;

use JiraWebhook\Models\JiraWebhookData;

abstract class JiraWebhookDataConverter
{
    abstract public function convert(JiraWebhookData $data);
}