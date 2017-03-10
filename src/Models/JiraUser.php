<?php
/**
 * Class that pars JIRA user data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraUser
{
    /**
     * JIRA user self URL
     * 
     * @var
     */
    protected $self;

    /**
     * JIRA user name
     * 
     * @var
     */
    protected $name;

    /**
     * JIRA user key
     *
     * @var
     */
    protected $key;

    /**
     * JIRA user email
     *
     * @var
     */
    protected $email;

    /**
     * JIRA user displayed name
     *
     * @var
     */
    protected $displayName;

    /**
     * Array of JIRA user avatars
     *
     * @var
     */
    protected $avatarURLs;

    /**
     * JIRA user active
     * 
     * @var
     */
    protected $active;

    /**
     * JIRA user time zone
     *
     * @var
     */
    protected $timeZone;

    /**
     * Parsing JIRA user $data
     * 
     * @param null $data
     * 
     * @return JiraUser
     * 
     * @throws JiraWebhookDataException
     */
    public static function parse($data = null)
    {
        $userData = new self;

        if (!$data) {
            return $userData;
        }

        $userData->validate($data);

        $userData->setSelf($data['self']);
        $userData->setName($data['name']);
        $userData->setKey($data['key']);
        $userData->setEmail($data['emailAddress']);
        $userData->setAvatarURLs($data['avatarUrls']);
        $userData->setDisplayName($data['displayName']);
        $userData->setActive($data['active']);
        $userData->setTimeZone($data['timeZone']);

        return $userData;
    }

    /**
     * @param $data
     * @throws JiraWebhookDataException
     */
    public function validate($data)
    {
        if (empty($data['name'])) {
            throw new JiraWebhookDataException('JIRA issue user name does not exist!');
        }
    }

    /**
     * @param $self
     */
    public function setSelf($self)
    {
        $this->self = $self;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param $avatarURLs
     */
    public  function setAvatarURLs($avatarURLs)
    {
        $this->avatarURLs = $avatarURLs;
    }

    /**
     * @param $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @param $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param $timeZone
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public  function getAvatarURLs()
    {
        return $this->avatarURLs;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }
}