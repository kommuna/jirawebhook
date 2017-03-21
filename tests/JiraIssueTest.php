<?php
/**
 * Author: Elena Kolevska
 */
namespace JiraWebhook\Tests;

use JiraWebhook\Exceptions\JiraWebhookDataException;
use JiraWebhook\Models\JiraIssueComments;
use PHPUnit_Framework_TestCase;
use JiraWebhook\Models\JiraUser;
use JiraWebhook\Models\JiraIssue;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

class JiraIssueTest extends PHPUnit_Framework_TestCase {

    protected $issueData;

    public function setUp()
    {
        $payload = JiraWebhookPayloadFactory::create();
        $this->issueData = $payload['issue'];
    }

    public function testParse()
    {
        $issue = JiraIssue::parse($this->issueData);

        $this->assertEquals($this->issueData['id'], $issue->getID());
        $this->assertEquals($this->issueData['self'], $issue->getSelf());
        $this->assertEquals($this->issueData['key'], $issue->getKey());
        $this->assertEquals($this->issueData['fields']['issuetype']['name'], $issue->getIssueType());
        $this->assertEquals($this->issueData['fields']['project']['name'], $issue->getProjectName());
        $this->assertEquals($this->issueData['fields']['priority']['name'], $issue->getPriority());
        $this->assertEquals($this->issueData['fields']['status']['name'], $issue->getStatus());
        $this->assertEquals($this->issueData['fields']['summary'], $issue->getSummary());

        $this->assertInstanceOf(JiraUser::class, $issue->getAssignee());
        $this->assertInstanceOf(JiraIssueComments::class, $issue->getIssueComments());
    }

    public function testExceptionIsThrownWhenWebhookIssueIdIsntSet()
    {
        $this->issueData['id'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }
    public function testExceptionIsThrownWhenWebhookIssueSelfUrlIsntSet()
    {
        $this->issueData['self'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }
    public function testExceptionIsThrownWhenWebhookIssueKeyIsntSet()
    {
        $this->issueData['key'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }
    public function testExceptionIsThrownWhenWebhookIssueFieldsArentSet()
    {
        $this->issueData['fields'] = [];
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }
    public function testExceptionIsThrownWhenWebhookIssueTypeIsntSet()
    {
        $this->issueData['fields']['issuetype']['name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }
    public function testExceptionIsThrownWhenWebhookIssuePriorityIsntSet()
    {
        $this->issueData['fields']['priority']['name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }
    public function testIsPriorityBlocker()
    {
        $this->issueData['fields']['priority']['name'] = 'Blocker';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isPriorityBlocker());
    }
    public function testIsNotPriorityBlocker()
    {
        $this->issueData['fields']['priority']['name'] = 'Foo';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertFalse($issue->isPriorityBlocker());
    }
    public function testIsStatusResolved()
    {
        $this->issueData['fields']['status']['name'] = 'Resolved';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isStatusResolved());

        $this->issueData['fields']['status']['name'] = '123 Resolved';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isStatusResolved());
    }
    public function testIsStatusNotResolved()
    {
        $this->issueData['fields']['status']['name'] = 'Foo';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertFalse($issue->isStatusResolved());
    }
    public function testIsStatusClosed()
    {
        $this->issueData['fields']['status']['name'] = 'Close';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isStatusClose());
    }
    public function testIsStatusNotClosed()
    {
        $this->issueData['fields']['status']['name'] = 'Foo';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertFalse($issue->isStatusClose());
    }
}