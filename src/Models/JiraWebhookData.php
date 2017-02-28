<?php
/**
 * This file is part of JiraWebhook.
 *
 * @credits https://github.com/kommuna
 * @author  chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraWebhookData
{
    /**
     * Decoded raw data
     * 
     * @var
     */
    protected $rawData;

    /**
     * Webhook timestamp
     *
     * @var
     */
    protected $timestamp;

    /**
     * Webhook event
     *
     * @var
     */
    protected $webhookEvent;

    /**
     * Webhook issue event
     *
     * @var
     */
    protected $issueEvent;

    /**
     * JiraWebhook\Models\JiraIssue
     * 
     * @var
     */
    protected $issue;

    /**
     * Parsing JIRA webhook $data 
     * 
     * @param null $data
     * 
     * @return JiraWebhookData
     * 
     * @throws JiraWebhookDataException
     */
    public static function parse($data = null)
    {
        $webhookData = new self;
        
        if (!$data) {
            return $webhookData;
        }
        
        $webhookData->setRawData($data);

        if (empty($data['webhookEvent'])) {
            throw new JiraWebhookDataException('JIRA webhook event does not exist!');
        }

        if (empty($data['issue_event_type_name'])) {
            throw new JiraWebhookDataException('JIRA issue event type does not exist!');
        }

        $webhookData->setTimestamp($data['timestamp']);
        $webhookData->setWebhookEvent($data['webhookEvent']);
        $webhookData->setIssueEvent($data['issue_event_type_name']);

        if (empty($data['issue'])) {
            throw new JiraWebhookDataException('JIRA issue event type does not exist!');
        }

        $webhookData->setIssue($data['issue']);

        return $webhookData;
    }

    /**
     * Check JIRA issue event is issue commented
     * 
     * @return bool
     */
    public function isIssueCommented()
    {
        return $this->issueEvent === 'issue_commented';
    }

    /**
     * Check JIRA issue event is issue assigned
     * 
     * @return bool
     */
    public function isIssueAssigned()
    {
        return $this->issueEvent === 'issue_assigned';
    }

    /**************************************************/

    /**
     * Set raw array, decoded from JIRA webhook
     * 
     * @param $rawData
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * @param $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param $webhookEvent
     */
    public function setWebhookEvent($webhookEvent)
    {
        $this->webhookEvent = $webhookEvent;
    }

    /**
     * @param $issueEvent
     */
    public function setIssueEvent($issueEvent)
    {
        $this->issueEvent = $issueEvent;
    }

    /**
     * Set parsed JIRA issue data as JiraWebhook\Models\JiraIssue
     * 
     * @param $issueData
     * 
     * @throws JiraWebhookDataException
     */
    public function setIssue($issueData)
    {
        $this->issue = JiraIssue::parse($issueData);
    }

    /**************************************************/

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return mixed
     */
    public function getWebhookEvent()
    {
        return $this->webhookEvent;
    }

    /**
     * @return mixed
     */
    public function getIssueEvent()
    {
        return $this->issueEvent;
    }

    /**
     * @return mixed
     */
    public function getIssue()
    {
        return $this->issue;
    }
}