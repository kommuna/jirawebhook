<?php
namespace JiraWebhook;

class JiraUser
{
    private $self;
    private $name;
    private $key;
    private $email;
    private $displayName;
    private $avatarURLs;
    private $active;
    private $timeZone;

    public static function parse($data = null)
    {
        $userData = new self;

        if ($data === null) {
            return $userData;
        }

        $userData->setSelf($data['self']);
        $userData->setName($data['name']);
        $userData->setKey($data['key']);
        $userData->setEmail($data['emailAdress']);
        $userData->setAvatarURLs($data['avatarUrls']);
        $userData->setDisplayName($data['displayName']);
        $userData->setActive($data['active']);
        $userData->setTimeZone($data['timeZone']);

        return $userData;
    }

    public function setSelf($self)
    {
        $this->self = $self;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public  function setAvatarURLs($avatarURLs)
    {
        $this->avatarURLs = $avatarURLs;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }

    /**************************************************/

    public function getSelf()
    {
        return $this->self;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public  function getAvatarURLs()
    {
        return $this->avatarURLs;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function getTimeZone()
    {
        return $this->timeZone;
    }
}