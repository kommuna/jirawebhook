<?php
/**
 * Created by PhpStorm.
 * Author: Elena Kolevska
 * Date: 3/9/17
 * Time: 23:43
 */
namespace JiraWebhook\Tests;

use PHPUnit_Framework_TestCase;
use JiraWebhook\Models\JiraWebhookData;
use JiraWebhook\Exceptions\JiraWebhookDataException;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

class JiraWebhookDataTest extends PHPUnit_Framework_TestCase {

    protected $issue_created_payload;

    public function setUp()
    {
        $this->payload = JiraWebhookPayloadFactory::create();
    }

    public function testExceptionIsThrownWhenWebhookEventIsntSpecified()
    {
        $this->issue_created_payload['webhookEvent'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraWebhookData::parse($this->issue_created_payload);
    }
    public function testExceptionIsThrownWhenIssueEventTypeIsntSpecified()
    {
        $this->issue_created_payload['issue_event_type_name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraWebhookData::parse($this->issue_created_payload);
    }
    public function testExceptionIsThrownWhenIssueIsntSpecified()
    {
        $this->issue_created_payload['issue'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraWebhookData::parse($this->issue_created_payload);
    }
}