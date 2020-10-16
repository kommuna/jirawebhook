<?php
/**
 * Class that parses JIRA worklog data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Thomas Hery thery@doghouse.agency
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraWorklog
{
    /**
     * JIRA worklog id
     *
     * @var string
     */
    protected $id;

    /**
     * JIRA worklog self URL
     *
     * @var
     */
    protected $self;

    /**
     * JIRA worklog issue id
     *
     * @var string
     */
    protected $issueId;

    /**
     * JIRA issue matching worklog
     *
     * @var JiraIssue
     */
    protected $issue;

    /**
     * JIRA worklog author
     *
     * @var JiraUser
     */
    protected $author;

    /**
     * JIRA worklog time spent in seconds
     *
     * @var int
     */
    protected $timeSpentSeconds;

    /**
     * JIRA worklog comment
     *
     * @var string
     */
    protected $comment;

    /**
     * JIRA worklog created date time
     *
     * @var string
     */
    protected $created;

    /**
     * JIRA worklog updated date time
     *
     * @var string
     */
    protected $updated;

    /**
     * JIRA worklog started date time
     *
     * @var string
     */
    protected $started;

    /**
     * Parsing JIRA worklog data
     *
     * @param null $data
     *
     * @return JiraWorklog
     */
    public static function parse($data = null)
    {
        $worklogData = new self;

        if (!$data) {
            return $worklogData;
        }

        $worklogData->validate($data);

        $worklogData->setId($data['id']);
        $worklogData->setSelf($data['self']);
        $worklogData->setIssueId($data['issueId']);
        $worklogData->setAuthor(JiraUser::parse($data['author']));
        $worklogData->setTimeSpentSeconds($data['timeSpentSeconds']);

        $worklogData->setComment($data['comment']);
        $worklogData->setCreatedDate($data['created']);
        $worklogData->setUpdatedDate($data['updated']);
        $worklogData->setStartedDate($data['started']);


        return $worklogData;
    }

    /**
     * Validates if the necessary parameters have been provided
     *
     * @param $data
     * @throws JiraWebhookDataException
     */
    public function validate($data)
    {
        if (empty($data['id'])) {
            throw new JiraWebhookDataException('JIRA worklog issue id does not exist!');
        }
        if (empty($data['issueId'])) {
            throw new JiraWebhookDataException('JIRA worklog id does not exist!');
        }
        if (empty($data['author'])) {
            throw new JiraWebhookDataException('JIRA worklog author does not exist!');
        }
        if (empty($data['timeSpentSeconds'])) {
            throw new JiraWebhookDataException('JIRA worklog time spent in sec does not exist!');
        }
    }

    /**
     * @param $id
     */
    public function setId($id)
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
     * @param $issueId
     */
    public function setIssueId($issueId)
    {
        $this->issueId = $issueId;
    }

    /**
     * @param $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @param $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param $created
     */
    public function setCreatedDate($created)
    {
        $this->created = $created;
    }

    /**
     * @param $created
     */
    public function setUpdatedDate($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @param $created
     */
    public function setStartedDate($started)
    {
        $this->started = $started;
    }

    /**
     * @param $timeSpentSeconds
     */
    public function setTimeSpentSeconds($timeSpentSeconds) {
        $this->timeSpentSeconds = $timeSpentSeconds;
    }

  /**
   * Assigns JiraIssue from raw API data.
   *
   * @param array $data
   *   Raw Jira issue data retrieved from API.
   *
   * @throws JiraWebhookDataException
   */
    public function setIssueFromData($data) {
        $this->issue = JiraIssue::parse($data);
    }

    /**************************************************/

    /**
     * @return string
     */
    public function getId()
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
    public function getIssueId()
    {
        return $this->issueId;
    }

    /**
     * @param JiraUser
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getUpdatedDate()
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getStartedDate()
    {
        return $this->started;
    }

    /**
     * @return int
     */
    public function gettimeSpentSeconds()
    {
        return $this->timeSpentSeconds;
    }

  /**
   * @return JiraIssue
   */
    public function getIssue()
    {
      return $this->issue;
    }

}