<?php
/**
 * Class that pars JIRA webhook data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
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
     * Webhook issue event human readable description
     *
     * @var
     */
    protected $issueEventDescription;

    /**
     * JiraWebhook\Models\JiraUser
     *
     * @var
     */
    protected $user;

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

        $webhookData->validate($data);

        $webhookData->setTimestamp($data['timestamp']);
        $webhookData->setWebhookEvent($data['webhookEvent']);
        $webhookData->setIssueEvent($data['issue_event_type_name']);
        $webhookData->setIssueEventDescription($data['issue_event_type_name']);
        $webhookData->setUser(JiraUser::parse($data['user']));
        $webhookData->setIssue($data['issue']);

        return $webhookData;
    }

    /**
     * @param $data
     * @throws JiraWebhookDataException
     */
    public function validate($data)
    {
        if (empty($data['webhookEvent'])) {
            throw new JiraWebhookDataException('JIRA webhook event not set!');
        }

        if (empty($data['issue_event_type_name'])) {
            throw new JiraWebhookDataException('JIRA issue event type not set!');
        }

        if (empty($data['issue'])) {
            throw new JiraWebhookDataException('JIRA issue not set!');
        }
    }

    /**
     * Check if JIRA issue event is issue commented
     * 
     * @return bool
     */
    public function isIssueCommented()
    {
        return $this->issueEvent === 'issue_commented';
    }

    /**
     * Check if JIRA issue event is issue assigned
     * 
     * @return bool
     */
    public function isIssueAssigned()
    {
        return $this->issueEvent === 'issue_assigned';
    }

    /**
     * Get array of channel labels that referenced in comment
     *
     * @param $string
     *
     * @return mixed
     */
    public static function getReferencedLabels($string)
    {
        preg_match_all("/#([A-Za-z0-9]*)/", $string, $matches);
        return $matches[1];
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
     * @param $issueEvent
     */
    public function setIssueEventDescription($issueEvent)
    {
        $event_descriptions = [
            'issue_created'   => "A new issue was created",
            'issue_commented' => "A new comment was added",
            'issue_updated'   => "The issue was updated",
            'issue_assigned'  => "The issue was assigned",
        ];
        $this->issueEventDescription = isset($event_descriptions[$issueEvent]) ? $event_descriptions[$issueEvent] : '';
    }

    /**
     * Sets a more descriptive event description, based on the flow of the code
     *
     * @param string $description
     */
    public function overrideIssueEventDescription($description)
    {
        $this->issueEventDescription = $description;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
    public function getIssueEventDescription()
    {
        return $this->issueEventDescription;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getIssue()
    {
        return $this->issue;
    }
}