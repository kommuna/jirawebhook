<?php
namespace JiraWebhook;

use JiraWebhook\Exceptions\JiraWebhookException;
use JiraWebhook\Models\JiraWebhookData;
use League\Event\Emitter;

class JiraWebhook
{
    private static $converter = [];
    private static $emitter;

    protected $rawData;
    protected $data;

    /**
     * JiraWebhook constructor.
     */
    public function __construct()
    {
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
     * Return data
     *
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Set converter for formatting messages
     *
     * @param $name - convertor name
     * @param $converter - object that extend JiraWebhookDataConverter
     */
    public static function setConverter($name, JiraWebhookDataConverter $converter)
    {
        self::$converter[$name] = $converter;
    }

    /**
     * Converts $data into message by converter
     *
     * @param $name - convertor name
     * @param $data - instance of the class JiraWebhookData
     *
     * @return mixed
     *
     * @throws JiraWebhookException
     */
    public static function convert($name, JiraWebhookData $data)
    {
        if (!empty(self::$converter[$name])) {
            return self::$converter[$name]->convert($data);
        }

        throw new JiraWebhookException("Converter {$name} is not registered!");
    }

    /**
     * Add listener for event
     *
     * @param $name - event name
     * @param $listener - listener (it could be function or object (see league/event docs))
     * @param int $priority - listener priority
     */
    public function addListener($name, $listener, $priority = 0)
    {
        self::$emitter->addListener($name, $listener, $priority);
    }

    /**
     * Call listener by event name
     *
     * @param $name - event name
     * @param null $data
     */
    public function on($name, $data = null)
    {
        // TODO add check for $name is callable
        self::$emitter->emit($name, $data);
    }

    /**
     * Processing data, received from from JIRA webhook by events
     *
     * @throws JiraWebhookException
     */
    public function run()
    {
        $data = $this->extractData();
        $this->on($data->getWebhookEvent(), $data);
    }
    
    /**
     * Get raw data from JIRA and parsing it
     *
     * @return JiraWebhookData - parsed data
     *
     * @throws JiraWebhookException
     */
    public function extractData()
    {
        $f = fopen('php://input', 'r');
        $data = stream_get_contents($f);

        if (!$data) {
            throw new JiraWebhookException('There is not data in the Jira webhook');
        }

        $this->rawData = json_decode($data, true);
        $jsonError = json_last_error();

        if ($jsonError !== JSON_ERROR_NONE) {
            throw new JiraWebhookException("This data cannot be decoded from json (decode error: $jsonError)!");
        }

        $this->data = JiraWebhookData::parse($this->rawData);

        return $this->data;
    }
}
