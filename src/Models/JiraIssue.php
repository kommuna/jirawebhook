<?php
namespace JiraWebhook\Models;

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

    public static function parse($data = null)
    {
        $issueData = new self;

        if ($data === null) {
            return $issueData;
        }
        
        $issueFields = $data['fields'];

        $issueData->setSelf($data['self']);
        $issueData->setKey($data['key']);
        $issueData->setIssueType($issueFields['issuetype']['name']);
        $issueData->setPriority($issueFields['priority']['name']);
        $issueData->setAssignee($issueFields['assignee']['name']);
        $issueData->setStatus($issueFields['status']['name']);
        $issueData->setSummary($issueFields['summary']);
        
        $issueData->setIssueComments(JiraIssueComments::parse($data['fields']['comment']));

        return $issueData;
    }

    public function isPriorityBlocker()
    {
        return $this->getPriority() === 'Blocker';
    }

    public function isTypeOprations()
    {
        return $this->getIssueType() === 'Operations';
    }

    public function isTypeUrgentBug()
    {
        return $this->getIssueType() === 'Urgent bug';
    }

    public function isStatusResolved()
    {
        // This is cause in devadmin JIRA status 'Resolved' has japanese symbols
        return strpos($this->getStatus(), 'Resolved');
    }

    /**************************************************/

    public function setSelf($self)
    {
        $this->self = $self;
    }
    
    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setIssueType($issueType)
    {
        $this->issueType = $issueType;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }
    
    public function setIssueComments($issueComments)
    {
        $this->issueComments = $issueComments;
    }

    /**************************************************/

    public function getSelf()
    {
        return $this->self;
    }
    
    public function getKey()
    {
        return $this->key;
    }

    public function getIssueType()
    {
        return $this->issueType;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getAssignee()
    {
        return $this->assignee;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getIssueComments()
    {
        return $this->issueComments;
    }
}