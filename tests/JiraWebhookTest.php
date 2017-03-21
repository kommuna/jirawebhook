<?php
/**
 * Author: Elena Kolevska
 */

namespace JiraWebhook\Tests;

use \JiraWebhook\JiraWebhook;
use PHPUnit_Framework_TestCase;
use JiraWebhook\Models\JiraWebhookData;
use \JiraWebhook\Exceptions\JiraWebhookException;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

class JiraWebhookTest extends PHPUnit_Framework_TestCase
{
    public function testExtractDataThrowsAnExceptionForInvalidJson()
    {
        $data = '{"foo":"bar}';  // Invalid json
        $this->expectException(JiraWebhookException::class);
        $jiraWebhook = new JiraWebhook();
        $jiraWebhook->extractData($data);
    }

    public function testExtractData()
    {
        $data = JiraWebhookPayloadFactory::create();
        $data = json_encode($data);
        $jiraWebhook = new JiraWebhook();
        $webhookData = $jiraWebhook->extractData($data);

        $this->assertInstanceOf(JiraWebhookData::class, $webhookData);
    }
}