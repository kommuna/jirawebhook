<?php
/**
 * Class that parses JIRA issue data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraIssue
{
    /**
     * JIRA issue id
     *
     * @var
     */
    protected $id;

    /**
     * JIRA issue self URL
     *
     * @var
     */
    protected $self;

    /**
     * JIRA issue key
     *
     * @var
     */
    protected $key;

    /**
     * JIRA issue url
     *
     * @var
     */
    protected $url;

    /**
     * JIRA issue type name
     *
     * @var
     */
    protected $issueTypeName;

    /**
     * JIRA issue project key
     *
     * @var
     */
    protected $projectKey;

    /**
     * JIRA issue project name
     *
     * @var
     */
    protected $projectName;

    /**
     * JIRA issue priority
     *
     * @var
     */
    protected $priorityName;

    /**
     * Array of JIRA issue labels
     *
     * @var
     */
    protected $labels;

    /**
     * JIRA issue colour, based on priority
     *
     * @var
     */
    //protected $colour;

    /**
     * JiraWebhook\Models\JiraUser
     *
     * @var
     */
    protected $assignee;

    /**
     * JIRA issue status
     *
     * @var
     */
    protected $statusName;

    /**
     * JIRA issue summary
     *
     * @var
     */
    protected $summary;

    /**
     * JiraWebhook\Models\JiraIssueComments
     *
     * @var
     */
    protected $issueComments;

  /**
   * All Jira issue fields
   *
   * @var array
   */
    protected $fields;

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

        if (!$data) {
            return $issueData;
        }

        $issueData->validate($data);

        $issueFields = $data['fields'];
        $issueData->setFields($issueFields);

        $issueData->setID($data['id']);
        $issueData->setSelf($data['self']);
        $issueData->setKey($data['key']);
        $issueData->setUrl($data['key'], $data['self']);
        $issueData->setIssueTypeName($issueFields['issuetype']['name']);
        $issueData->setProjectKey($issueFields['project']['key']);
        $issueData->setProjectName($issueFields['project']['name']);
        $issueData->setPriorityName($issueFields['priority']['name']);
        //$issueData->setColour($issueFields['priority']['name']);
        $issueData->setLabels($issueFields['labels']);
        $issueData->setAssignee(JiraUser::parse($issueFields['assignee']));
        $issueData->setStatusName($issueFields['status']['name']);
        $issueData->setSummary($issueFields['summary']);
        $issueData->setIssueComments(JiraIssueComments::parse($data['fields']['comment']));

        return $issueData;
    }

    /**
     * @param $data
     * @throws JiraWebhookDataException
     */
    public function validate($data)
    {
        if (empty($data['id'])) {
            throw new JiraWebhookDataException('JIRA issue id does not exist!');
        }

        if (empty($data['self'])) {
            throw new JiraWebhookDataException('JIRA issue self URL does not exist!');
        }

        if (empty($data['key'])) {
            throw new JiraWebhookDataException('JIRA issue key does not exist!');
        }

        if (empty($data['fields'])) {
            throw new JiraWebhookDataException('JIRA issue fields does not exist!');
        }

        if (empty($data['fields']['issuetype']['name'])) {
            throw new JiraWebhookDataException('JIRA issue type does not exist!');
        }

        if (empty($data['fields']['priority']['name'])) {
            throw new JiraWebhookDataException('JIRA issue priority does not exist!');
        }
    }

    /**
     * Check JIRA issue priority is Blocker
     *
     * @return bool
     */
    public function isPriorityBlocker()
    {
        return $this->getPriorityName() === 'Blocker';
    }

    /**
     * Check JIRA issue type is Operations
     *
     * @return bool
     */
    public function isTypeOperations()
    {
        return strpos($this->getIssueTypeName(), 'Operations') !== false;
    }

    /**
     * Check JIRA issue type is Urgent bug
     *
     * @return bool
     */
    public function isTypeUrgentBug()
    {
        return strpos($this->getIssueTypeName(), 'Urgent Bug') !== false;
    }

    /**
     * Check JIRA issue type is Server
     *
     * @return bool
     */
    public function isTypeServer()
    {
        return strpos($this->getIssueTypeName(), 'Server') !== false;
    }

    /**
     * Check JIRA issue status is Resolved
     *
     * @return bool
     */
    public function isStatusResolved()
    {
        // This is cause in devadmin JIRA status 'Resolved' has japanese symbols
        return strpos($this->getStatusName(), 'Resolved') !== false;
    }

    /**
     * Check if JIRA issue status is Closed
     *
     * @return bool|int
     */
    public function isStatusClosed()
    {
        return $this->getStatusName() === 'Closed';
    }

    /**************************************************/

    /**
     * @param $id
     */
    public function setID($id)
    {
        $this->id = $id;
    }

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
     * Sets the web based url of an issue
     *
     * @param $key
     * @param $self
     */
    public function setUrl($key, $self)
    {
        $url = parse_url($self);
        $this->url = $url['scheme'] . '://' . $url['host'] . '/browse/' . $key;
    }

    /**
     * @param $issueTypeName
     */
    public function setIssueTypeName($issueTypeName)
    {
        $this->issueTypeName = $issueTypeName;
    }

    /**
     * @param $projectKey
     */
    public function setProjectKey($projectKey)
    {
        $this->projectKey = $projectKey;
    }

    /**
     * @param $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }

    /**
     * @param $priorityName
     */
    public function setPriorityName($priorityName)
    {
        $this->priorityName = $priorityName;
    }

    /**
     * @param $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    /**
     * @param $priority
     */
    /*public function setColour($priority)
    {
        // These are the same colors used for priority indicators in Jira
        $priority_colors = [
            'Blocker' => '#d40100',
            'Highest' => '#ce0000',
            'High' => '#ea4444',
            'Medium' => '#ea7d24',
            'Low' => '#2a8735',
            'Lowest' => '#55a557'
        ];
        $this->colour = isset($priority_colors[$priority]) ? $priority_colors[$priority]: '#007AB8';
    }*/

    /**
     * @param $assignee
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    /**
     * @param $statusName
     */
    public function setStatusName($statusName)
    {
        $this->statusName = $statusName;
    }

    /**
     * @param $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * Set parsed JIRA issue comments data
     *
     * @param $issueComments
     */
    public function setIssueComments($issueComments)
    {
        $this->issueComments = $issueComments;
    }

    /**
     * Sets all issue fields to access extra info.
     *
     * @param array $fields
     */
    public function setFields($fields)
    {
      $this->fields = $fields;
    }

    /**************************************************/

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSelf()
    {
        return $this->self;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the key with a $modifier instead of a hyphen
     *
     * @param $modifier
     *
     * @return mixed
     */
    public function getModifiedKey($modifier = " ")
    {
        return str_replace("-", $modifier, $this->key);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getIssueTypeName()
    {
        return $this->issueTypeName;
    }

    /**
     * @return string
     */
    public function getProjectKey()
    {
        return $this->projectKey;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return string
     */
    public function getPriorityName()
    {
        return $this->priorityName;
    }

    /**
     * @return mixed
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return string
     */
    /*public function getColour()
    {
        return $this->colour;
    }*/

    /**
     * @return JiraUser
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return $this->statusName;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return JiraIssueComments
     */
    public function getIssueComments()
    {
        return $this->issueComments;
    }

    /**
     * @return array
     */
    public function getFields()
    {
      return $this->fields;
    }

}