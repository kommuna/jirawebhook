<?php
/**
 * This file has class that parse and store issue comments data from JIRA
 *
 * In this file issue comments data from JIRA parsed and stored in properties
 * by methods
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraIssueComments
{
    /**
     * Contains array of JiraWebhook\Models\JiraIssueComment
     * 
     * @var array
     */
    protected $comments = [];

    protected $maxResults;
    protected $total;
    protected $startAt;

    /**
     * Parsing JIRA issue comments $data
     *
     * @param null $data
     *
     * @return JiraIssueComments
     *
     * @throws JiraWebhookDataException
     */
    public static function parse($data = null)
    {
        $issueCommentsData = new self;

        if (!isset($data['comments'])) {
            throw new JiraWebhookDataException('JIRA issue comments does not exist!');
        }

        foreach ($data['comments'] as $key => $comment) {
            $issueCommentsData->setComment($key, $comment);
        }

        $issueCommentsData->setMaxResults($data['maxResults']);
        $issueCommentsData->setTotal($data['total']);
        $issueCommentsData->setStartAt($data['startAt']);

        return $issueCommentsData;
    }

    /**
     * Set parsed single comment
     *
     * @param $key     array keu
     * @param $comment comment data
     *
     * @throws JiraWebhookDataException
     */
    public function setComment($key, $comment)
    {
        $this->comments[$key] = JiraIssueComment::parse($comment);
    }

    /**
     * @param $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    /**
     * @param $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @param $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**************************************************/

    /**
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Get object of last comment
     * 
     * @return mixed
     */
    public function getLastComment()
    {
        return end($this->comments);
    }

    /**
     * Get author name of last comment
     * 
     * @return mixed
     */
    public function getLastCommenterName()
    {
        return $this->getLastComment()->getAuthor()->getName();
    }

    /**
     * Get body (text) of last comment
     * 
     * @return mixed
     */
    public function getLastCommentBody()
    {
        return $this->getLastComment()->getBody();
    }
}