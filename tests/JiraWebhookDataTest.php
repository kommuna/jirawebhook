<?php
/**
 * Author: Elena Kolevska
 */
namespace JiraWebhook\Tests;

use JiraWebhook\Models\JiraIssue;
use PHPUnit_Framework_TestCase;
use JiraWebhook\Models\JiraWebhookData;
use JiraWebhook\Exceptions\JiraWebhookDataException;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

class JiraWebhookDataTest extends PHPUnit_Framework_TestCase {

    protected $payload;

    public function setUp()
    {
        $this->payload = JiraWebhookPayloadFactory::create();
    }

    public function testExceptionIsThrownWhenWebhookEventIsntSpecified()
    {
        $this->payload['webhookEvent'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraWebhookData::parse($this->payload);
    }
    public function testExceptionIsThrownWhenIssueEventTypeIsntSpecified()
    {
        $this->payload['issue_event_type_name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraWebhookData::parse($this->payload);
    }
    public function testExceptionIsThrownWhenIssueIsntSpecified()
    {
        $this->payload['issue'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraWebhookData::parse($this->payload);
    }

    public function testParse()
    {
        $webhookData = JiraWebhookData::parse($this->payload);

        $this->assertEquals($this->payload, $webhookData->getRawData());
        $this->assertEquals($this->payload['timestamp'], $webhookData->getTimestamp());
        $this->assertEquals($this->payload['webhookEvent'], $webhookData->getWebhookEvent());
        $this->assertEquals($this->payload['issue_event_type_name'], $webhookData->getIssueEvent());
        $this->assertInstanceOf(JiraIssue::class, $webhookData->getIssue());

    }
}