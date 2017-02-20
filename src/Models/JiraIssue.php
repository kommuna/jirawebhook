<?php
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraIssue
{
    protected $self;
    protected $key;
    protected $issueType;
    protected $priority;
    protected $assignee;
    protected $status;
    protected $summary;

    protected $issueComments;

    /**
     * Parsing JIRA issue $data
     * 
     * @param null $data
     * 
     * @return JiraIssue
     * 
     * @throws JiraWebhookDataException
     */
    public static function parse($data = null)
    {
        $issueData = new self;

        if ($data === null) {
            return $issueData;
        }

        if (!isset($data['self'])) {
            throw new JiraWebhookDataException('JIRA issue self URL does not exist!');
        }

        if (!isset($data['key'])) {
            throw new JiraWebhookDataException('JIRA issue key does not exist!');
        }

        $issueData->setSelf($data['self']);
        $issueData->setKey($data['key']);

        if (!isset($data['fields'])) {
            throw new JiraWebhookDataException('JIRA issue fields does not exist!');
        }

        $issueFields = $data['fields'];

        if (!isset($issueFields['issuetype']['name'])) {
            throw new JiraWebhookDataException('JIRA issue type does not exist!');
        }

        $issueData->setIssueType($issueFields['issuetype']['name']);

        if (!isset($issueFields['priority']['name'])) {
            throw new JiraWebhookDataException('JIRA issue priority does not exist!');
        }

        $issueData->setPriority($issueFields['priority']['name']);
        
        $issueData->setAssignee($issueFields['assignee']['name']);
        $issueData->setStatus($issueFields['status']['name']);
        $issueData->setSummary($issueFields['summary']);

        $issueData->setIssueComments(JiraIssueComments::parse($data['fields']['comment']));

        return $issueData;
    }

    /**
     * Check JIRA issue priority field
     * 
     * @return bool
     */
    public function isPriorityBlocker()
    {
        return $this->getPriority() === 'Blocker';
    }

    /**
     * Check JIRA issue type field
     * 
     * @return bool
     */
    public function isTypeOprations()
    {
        return $this->getIssueType() === 'Operations';
    }

    /**
     * Check JIRA issue type field
     * 
     * @return bool
     */
    public function isTypeUrgentBug()
    {
        return $this->getIssueType() === 'Urgent bug';
    }

    /**
     * Check JIRA issue status field
     * 
     * @return bool|int
     */
    public function isStatusResolved()
    {
        // This is cause in devadmin JIRA status 'Resolved' has japanese symbols
        return strpos($this->getStatus(), 'Resolved');
    }

    /**************************************************/

    /**
     * @param $self
     */
    public function setSelf($self)
    {
        $this->self = $self;
    }

    /**
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param $issueType
     */
    public function setIssueType($issueType)
    {
        $this->issueType = $issueType;
    }

    /**
     * @param $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @param $assignee
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @param $issueComments
     */
    public function setIssueComments($issueComments)
    {
        $this->issueComments = $issueComments;
    }

    /**************************************************/

    /**
     * @return mixed
     */
    public function getSelf()
    {
        return $this->self;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getIssueType()
    {
        return $this->issueType;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return mixed
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return mixed
     */
    public function getIssueComments()
    {
        return $this->issueComments;
    }
}