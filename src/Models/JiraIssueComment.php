<?php
/**
 * Class that parses JIRA issue single comment data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
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
     * JIRA comment ID
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

        $commentData->validate($data);

        $commentData->setSelf($data['self']);
        $commentData->setId($data['id']);
        $commentData->setAuthor(JiraUser::parse($data['author']));
        $commentData->setBody($data['body']);
        $commentData->setUpdateAuthor(JiraUser::parse($data['updateAuthor']));
        $commentData->setCreated($data['created']);
        $commentData->setUpdated($data['updated']);

        return $commentData;
    }

    /**
     * Validates if the necessary parameters have been provided
     *
     * @param $data
     * @throws JiraWebhookDataException
     */
    public function validate($data)
    {
        if (empty($data['author'])) {
            throw new JiraWebhookDataException('JIRA issue comment author does not exist!');
        }
        if (empty($data['body'])) {
            throw new JiraWebhookDataException('JIRA issue comment body does not exist!');
        }
    }

    /**
     * Get array of user nicknames that referenced in comment
     *
     * @return mixed
     */
    public function getMentionedUsersNicknames()
    {
        preg_match_all("/\[~(.*?)\]/", $this->body, $matches);
        return $matches[1];
    }

    /**
     * Remove from comment body code and quote blocks
     *
     * @return mixed
     */
    public function bodyParsing()
    {
        return preg_replace("/\{code(.*?)\}(.*?)\{code\}|\{quote\}(.*?)\{quote\}/", "", $this->body);
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
     * @return JiraIssueComment
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
     * @return JiraUser
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getBody($start = 0, $length = null)
    {
        return mb_substr($this->body, $start, $length);
    }

    /**
     * @return JiraUser
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
}