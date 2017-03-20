<?php
/**
 * Author: Elena Kolevska
 * Date: 3/9/17
 * Time: 23:43
 */

namespace JiraWebhook\Tests;

use JiraWebhook\Models\JiraWebhookData;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;
use PHPUnit_Framework_TestCase;
use \JiraWebhook\JiraWebhook;
use \JiraWebhook\Exceptions\JiraWebhookException;

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