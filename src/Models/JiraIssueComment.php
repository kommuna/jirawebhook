<?php
namespace JiraWebhook\Models;

class JiraIssueComment
{
    private $self;
    private $id;
    private $author;
    private $body;
    private $updateAuthor;
    private $created;
    private $updated;

    private $commentReference;

    public static function parse($data = null)
    {
        $commentData = new self;

        if ($data === null) {
            return $commentData;
        }

        $commentData->setSelf($data['self']);
        $commentData->setId($data['id']);
        $commentData->setAuthor(JiraUser::parse($data['author']));
        $commentData->setBody($data['body']);
        $commentData->setUpdateAuthor(JiraUser::parse($data['updateAuthor']));
        $commentData->setCreated($data['created']);
        $commentData->setUpdated($data['updated']);

        return $commentData;
    }

    public function getMentionedUsersNicknames()
    {
        preg_match_all("(\"/\[~(.*?)\]/\",", $this->body, $matches);
        return $matches[1];
    }

    public function isCommentReference()
    {
        return stripos($this->body, '[~');
    }

    public function setSelf($self)
    {
        $this->self = $self;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setUpdateAuthor($updateAuthor)
    {
        $this->updateAuthor = $updateAuthor;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function setCommentReference($commentreference)
    {
        $this->commentReference = $commentreference;
    }

    /**************************************************/

    public function getSelf()
    {
        return $this->self;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getUpdateAuthor()
    {
        return $this->updateAuthor;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getCommentReference()
    {
        return $this->commentReference;
    }
}