<?php
/**
 * Class with methods for parsing data from JIRA webhook, setting converters and listeners for events.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook;

use League\Event\Emitter;
use JiraWebhook\Models\JiraWebhookData;
use JiraWebhook\Exceptions\JiraWebhookException;

class JiraWebhook
{
    /**
     * Here will be stored converters
     *
     * @var array
     */
    private static $converter = [];

    /**
     * League\Event\Emitter
     *
     * @var
     */
    private static $emitter;

    /**
     * Raw data from JIRA webhook
     *
     * @var
     */
    protected $rawData;

    /**
     * Parsed data from JIRA webhook
     * JiraWebhook\Models\JiraWebhookData
     *
     * @var
     */
    protected $data;

    /**
     * JiraWebhook constructor.
     *
     * @param null $rawData raw data from JIRA webhook
     */
    public function __construct($rawData = null)
    {
        $this->rawData = $rawData;

        self::getEmitter();
    }

    /**
     * Initialize emitter
     *
     * @return Emitter
     */
    public static function getEmitter()
    {
        if (!self::$emitter) {
            self::$emitter = new Emitter();
        }

        return self::$emitter;
    }

    /**
     * Return raw data
     * 
     * @return mixed raw|null
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * Return parsed data
     *
     * @return mixed
     */
    public function getData() 
    {
        return $this->data;
    }

    /**
     * Set $converter for formatting messages
     *
     * @param string                   $name      converter name
     * @param JiraWebhookDataConverter $converter object that extends JiraWebhookDataConverter
     */
    public static function setConverter($name, JiraWebhookDataConverter $converter)
    {
        self::$converter[$name] = $converter;
    }

    /**
     * Converts $data into message by converter
     *
     * @param string          $name converter name
     * @param JiraWebhookData $data instance of the class JiraWebhookData
     *
     * @return mixed
     *
     * @throws JiraWebhookException
     */
    public static function convert($name, JiraWebhookData $data, $slackClientMessage)
    {
        if (!empty(self::$converter[$name])) {
            return self::$converter[$name]->convert($data, $slackClientMessage);
        }

        throw new JiraWebhookException("Converter {$name} is not registered!");
    }

    /**
     * Add listener for event
     *
     * @param string   $name     event name
     * @param callable $listener listener (it could be function or object (see league/event docs))
     * @param int      $priority listener priority
     */
    public function addListener($name, $listener, $priority = 0)
    {
        self::$emitter->addListener($name, $listener, $priority);
    }

    /**
     * Call listener by event $name
     *
     * @param string $name event name
     * @param null   $data
     */
    public function trigger($name, $data = null)
    {
        self::$emitter->emit($name, $data);
    }

    /**
     * Processing data, received from from JIRA webhook by events
     *
     * @param null $data
     */
    public function run($data = null)
    {
        $data = $this->extractData($data);
        $this->trigger($data->getWebhookEvent(), $data);
    }

    /**
     * Parse raw data from JIRA
     *
     * @param null $data
     * @return JiraWebhookData parsed data
     *
     * @throws JiraWebhookException
     */
    public function extractData($data = null)
    {
        $this->rawData = json_decode($data ? $data : $this->rawData, true);
        $jsonError = json_last_error();

        if ($jsonError !== JSON_ERROR_NONE) {
            throw new JiraWebhookException("This data cannot be decoded from json (decode error: {$jsonError})!");
        }

        $this->data = JiraWebhookData::parse($this->rawData);

        return $this->data;
    }
}
