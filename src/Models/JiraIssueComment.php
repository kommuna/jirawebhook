<?php
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraIssueComment
{
    protected $self;
    protected $id;
    protected $author;
    protected $body;
    protected $updateAuthor;
    protected $created;
    protected $updated;

    /**
     * Parsing JIRA issue comment $data
     * 
     * @param null $data
     * 
     * @return JiraIssueComment
     * 
     * @throws JiraWebhookDataException
     */
    public static function parse($data = null)
    {
        $commentData = new self;

        if ($data === null) {
            return $commentData;
        }

        $commentData->setSelf($data['self']);
        $commentData->setId($data['id']);

        if (!isset($data['author'])) {
            throw new JiraWebhookDataException('JIRA issue comment author does not exist!');
        }
        
        $commentData->setAuthor(JiraUser::parse($data['author']));

        if (!isset($data['body'])) {
            throw new JiraWebhookDataException('JIRA issue comment body does not exist!');
        }
        
        $commentData->setBody($data['body']);
        $commentData->setUpdateAuthor(JiraUser::parse($data['updateAuthor']));
        $commentData->setCreated($data['created']);
        $commentData->setUpdated($data['updated']);

        return $commentData;
    }

    /**
     * Get array of referenced users in comment
     *
     * @return mixed
     */
    public function getMentionedUsersNicknames()
    {
        preg_match_all("(\"/\[~(.*?)\]/\")", $this->body, $matches);
        return $matches[1];
    }

    /**
     * Check comment for references to users
     *
     * @return int
     */
    public function isCommentReference()
    {
        return stripos($this->body, '[~');
    }

    /**
     * @param $self
     */
    public function setSelf($self)
    {
        $this->self = $self;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param $updateAuthor
     */
    public function setUpdateAuthor($updateAuthor)
    {
        $this->updateAuthor = $updateAuthor;
    }

    /**
     * @param $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @param $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getUpdateAuthor()
    {
        return $this->updateAuthor;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return mixed
     */
    public function getCommentReference()
    {
        return $this->commentReference;
    }
}