<?php
/**
 * Author: Elena Kolevska
 */
namespace JiraWebhook\Tests;

use JiraWebhook\Models\JiraIssueComments;
use PHPUnit_Framework_TestCase;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

class JiraIssueCommentsTest extends PHPUnit_Framework_TestCase {

    protected $issueData;

    public function setUp()
    {
        $payload = JiraWebhookPayloadFactory::create();
        $this->issueCommentsData = $payload['issue']['fields']['comment'];
    }

    public function testParse()
    {
        $issueComments = JiraIssueComments::parse($this->issueCommentsData);

        $this->assertEquals($this->issueCommentsData['maxResults'], $issueComments->getMaxResults());
        $this->assertEquals($this->issueCommentsData['total'], $issueComments->getTotal());
        $this->assertEquals($this->issueCommentsData['startAt'], $issueComments->getStartAt());
        $this->assertEquals(count($this->issueCommentsData['comments']), count($issueComments->getComments()));


        $this->assertInstanceOf(JiraIssueComments::class, $issueComments);
    }

}