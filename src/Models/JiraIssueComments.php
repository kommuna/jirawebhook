<?php
namespace JiraWebhook\Models;

class JiraIssueComments
{
    protected $comments = [];

    protected $maxResults;
    protected $total;
    protected $startAt;
    
    public static function parse($data = null)
    {
        $issueCommentsData = new self;

        if (!$data) {
            return $issueCommentsData;
        }

        foreach ($data['comments'] as $key => $comment) {
            $issueCommentsData->setComment($key, $comment);
        }

        $issueCommentsData->setMaxResults($data['maxResults']);
        $issueCommentsData->setTotal($data['total']);
        $issueCommentsData->setStartAt($data['startAt']);

        return $issueCommentsData;
    }

    public function setComment($key, $comment)
    {
        $this->comments[$key] = JiraIssueComment::parse($comment);
    }
    
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**************************************************/

    public function getComments()
    {
        return $this->comments;
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getStartAt()
    {
        return $this->startAt;
    }
    
    public function getLastComment()
    {
        return end($this->comments);
    }

    public function getLastCommenterName()
    {
        return $this->getLastComment()->getAuthor()->getName();
    }

    public function getLastCommentBody()
    {
        return $this->getLastComment()->getBody();
    }
}