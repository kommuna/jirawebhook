<?php
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraWebhookData
{
    private $rawData;

    private $timestamp;
    private $webhookEvent;
    private $issueEvent;

    private $issue;

    public static function parse($data = null)
    {
        $webhookData = new self;
        
        if ($data === null) {
            return $webhookData;
        }
        
        $webhookData->setRawData($data);

        if (!isset($data['webhookEvent'])) {
            throw new JiraWebhookDataException('JIRA webhook event does not exist!');
        }

        if (!isset($data['issue_event_type_name'])) {
            throw new JiraWebhookDataException('JIRA issue event type does not exist!');
        }

        $webhookData->setTimestamp($data['timestamp']);
        $webhookData->setWebhookEvent($data['webhookEvent']);
        $webhookData->setIssueEvent($data['issue_event_type_name']);

        if (!isset($data['issue'])) {
            throw new JiraWebhookDataException('JIRA issue event type does not exist!');
        }

        if (!isset($data['issue']['fields'])) {
            throw new JiraWebhookDataException('JIRA issue fields does not exist!');
        }

        $webhookData->setIssue($data['issue']);

        return $webhookData;
    }
    
    public function isIssueCommented()
    {
        return $this->issueEvent === 'issue_commented';
    }

    public function isIssueAssigned()
    {
        return $this->issueEvent === 'issue_assigned';
    }

    /**************************************************/

    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function setWebhookEvent($webhookEvent)
    {
        $this->webhookEvent = $webhookEvent;
    }

    public function setIssueEvent($issueEvent)
    {
        $this->issueEvent = $issueEvent;
    }
    
    public function setIssue($issueData)
    {
        $this->issue = JiraIssue::parse($issueData);
    }

    /**************************************************/

    public function getRawData()
    {
        return $this->rawData;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getWebhookEvent()
    {
        return $this->webhookEvent;
    }

    public function getIssueEvent()
    {
        return $this->issueEvent;
    }
    
    public function getIssue()
    {
        return $this->issue;
    }
}