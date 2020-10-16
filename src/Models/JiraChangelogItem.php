<?php
/**
 * Class that parses JIRA changelog item data and gives access to it.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Models;

class JiraChangelogItem
{
    /**
     * Issue field that has changed
     *
     * @var
     */
    protected $field;

    /**
     * Type of changed field
     *
     * @var
     */
    protected $fieldType;

    /**
     * Old value of the field
     *
     * @var
     */
    protected $from;

    /**
     * Name of the field old value
     *
     * @var
     */
    protected $fromString;

    /**
     * New value of the field
     *
     * @var
     */
    protected $to;

    /**
     * Name of field new value
     *
     * @var
     */
    protected $toString;

    /**
     * Parsing JIRA changelog item data
     *
     * @param null $data
     *
     * @return JiraChangelogItem
     */
    public static function parse($data = null)
    {
        $changelogItemData = new self;

        if (!$data) {
            return $changelogItemData;
        }

        $changelogItemData->setField($data['field']);
        $changelogItemData->setFieldType($data['fieldtype']);
        $changelogItemData->setFrom($data['from']);
        $changelogItemData->setFromString($data['fromString']);
        $changelogItemData->setTo($data['to']);
        $changelogItemData->setToString($data['toString']);

        return $changelogItemData;
    }

    /**
     * @param $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @param $fieldType
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @param $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param $fromString
     */
    public function setFromString($fromString)
    {
        $this->fromString = $fromString;
    }

    /**
     * @param $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @param $toString
     */
    public function setToString($toString)
    {
        $this->toString = $toString;
    }

    /**************************************************/

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getFromString()
    {
        return $this->fromString;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getToString()
    {
        return $this->toString;
    }
}