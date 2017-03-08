<?php
/**
 * Class that pars JIRA issue single comment data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraIssueComment
{
    /**
     * JIRA comment self url
     *
     * @var
     */
    protected $self;

    /**
     * JIRA comemnt ID
     *
     * @var
     */
    protected $id;

    /**
     * JIRA comment author
     * JiraWebhook\Models\JiraUser
     *
     * @var
     */
    protected $author;

    /**
     * JIRA comment text
     *
     * @var
     */
    protected $body;

    /**
     * JIRA comment update author
     * JiraWebhook\Models\JiraUser
     *
     * @var
     */
    protected $updateAuthor;

    /**
     * JIRA comment create data time
     *
     * @var
     */
    protected $created;

    /**
     * JIRA comment update data time
     *
     * @var
     */
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

        if (!$data) {
            return $commentData;
        }

        $commentData->setSelf($data['self']);
        $commentData->setId($data['id']);

        if (empty($data['author'])) {
            throw new JiraWebhookDataException('JIRA issue comment author does not exist!');
        }
        
        $commentData->setAuthor(JiraUser::parse($data['author']));

        if (empty($data['body'])) {
            throw new JiraWebhookDataException('JIRA issue comment body does not exist!');
        }
        
        $commentData->setBody($data['body']);
        $commentData->setUpdateAuthor(JiraUser::parse($data['updateAuthor']));
        $commentData->setCreated($data['created']);
        $commentData->setUpdated($data['updated']);

        return $commentData;
    }

    /**
     * Get array of user nicknames that referenced comment
     *
     * @return mixed
     */
    public function getMentionedUsersNicknames()
    {
        preg_match_all("/\[~(.*?)\]/", $this->body, $matches);
        return $matches[1];
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