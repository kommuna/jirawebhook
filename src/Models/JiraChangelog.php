<?php
/**
 * Class that parses JIRA changelog data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

class JiraChangelog
{
    /**
     * JIRA changelog id
     *
     * @var
     */
    protected $id;

    /**
     * Array of changelog items
     * JiraWebhook\Models\JiraChangelogItem
     *
     * @var array
     */
    protected $items = [];

    /**
     * Parsing JIRA changelog data
     *
     * @param null $data
     *
     * @return JiraChangelog
     */
    public static function parse($data = null)
    {
        $changelogData = new self;

        if (!$data) {
            return $changelogData;
        }

        $changelogData->setId($data['id']);

        foreach ($data['items'] as $key => $item) {
            $changelogData->setItem($key, JiraChangelogItem::parse($item));
        }

        return $changelogData;
    }

    /**
     * Check if JIRA issue was assigned
     *
     * @return bool
     */
    public function isIssueAssigned()
    {
        $isAssigned = false;

        foreach ($this->items as $item) {
            if ($isAssigned = $item->getField() === 'assignee') {
                break;
            }
        }

        return $isAssigned;
    }

    /**************************************************/

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $key
     * @param $item
     */
    public function setItem($key, $item)
    {
        $this->items[$key] = $item;
    }

    /**************************************************/

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}