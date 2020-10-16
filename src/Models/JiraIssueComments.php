<?php
/**
 * Class that parses JIRA issue comments data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    /**
     * JIRA comments max results
     *
     * @var
     */
    protected $maxResults;

    /**
     * Total number of comments
     *
     * @var
     */
    protected $total;

    /**
     * JIRA comments start at
     *
     * @var
     */
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

        if (!$data) {
            return $issueCommentsData;
        }

        foreach ($data['comments'] as $key => $comment) {
            $issueCommentsData->setComment($key, JiraIssueComment::parse($comment));
        }

        $issueCommentsData->setMaxResults($data['maxResults']);
        $issueCommentsData->setTotal($data['total']);
        $issueCommentsData->setStartAt($data['startAt']);

        return $issueCommentsData;
    }

    /**
     * Set parsed single comment
     *
     * @param mixed            $key     array key
     * @param JiraIssueComment $comment comment data
     *
     * @throws JiraWebhookDataException
     */
    public function setComment($key, $comment)
    {
        $this->comments[$key] = $comment;
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
     * @return integer
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @return integer
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
     * @return JiraIssueComment
     */
    public function getLastComment()
    {
        return end($this->comments);
    }

    /**
     * Get author name of last comment
     * 
     * @return string
     */
    public function getLastCommenterName()
    {
        return $this->getLastComment()->getAuthor()->getName();
    }

    /**
     * Get body (text) of last comment
     * 
     * @return string
     */
    public function getLastCommentBody()
    {
        return $this->getLastComment()->getBody();
    }
}