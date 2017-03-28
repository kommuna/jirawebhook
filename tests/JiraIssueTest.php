<?php
/**
 * Test for methods in class JiraWebhook\Models\JiraIssue
 *
 * @credits https://github.com/kommuna
 * @author  Miss Lv lv@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Tests;

use PHPUnit_Framework_TestCase;
use JiraWebhook\Models\JiraUser;
use JiraWebhook\Models\JiraIssue;
use JiraWebhook\Models\JiraIssueComments;
use JiraWebhook\Exceptions\JiraWebhookDataException;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

/**
 * @property  array issueData
 */
class JiraIssueTest extends PHPUnit_Framework_TestCase {

    private $issueData;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $payload = JiraWebhookPayloadFactory::create();
        $this->issueData = $payload['issue'];
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testParse()
    {
        $this->issueData['self'] = 'https://testvicky.atlassian.net/rest/api/2/issue/10003';
        $this->issueData['fields']['priority']['name'] = 'Highest';
        $issue = JiraIssue::parse($this->issueData);


        $this->assertEquals($this->issueData['id'], $issue->getID());
        $this->assertEquals($this->issueData['self'], $issue->getSelf());
        $this->assertEquals('https://testvicky.atlassian.net/browse/' . $issue->getKey(), $issue->getUrl());
        $this->assertEquals($this->issueData['key'], $issue->getKey());
        $this->assertEquals($this->issueData['fields']['issuetype']['name'], $issue->getIssueType());
        $this->assertEquals($this->issueData['fields']['project']['name'], $issue->getProjectName());
        $this->assertEquals($this->issueData['fields']['priority']['name'], $issue->getPriority());
        $this->assertEquals('#ce0000', $issue->getColour());
        $this->assertEquals($this->issueData['fields']['status']['name'], $issue->getStatus());
        $this->assertEquals($this->issueData['fields']['summary'], $issue->getSummary());
        $this->assertInstanceOf(JiraUser::class, $issue->getAssignee());
        $this->assertInstanceOf(JiraIssueComments::class, $issue->getIssueComments());
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testExceptionIsThrownWhenWebhookIssueIdIsntSet()
    {
        $this->issueData['id'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testExceptionIsThrownWhenWebhookIssueSelfUrlIsntSet()
    {
        $this->issueData['self'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testExceptionIsThrownWhenWebhookIssueKeyIsntSet()
    {
        $this->issueData['key'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testExceptionIsThrownWhenWebhookIssueFieldsArentSet()
    {
        $this->issueData['fields'] = [];
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testExceptionIsThrownWhenWebhookIssueTypeIsntSet()
    {
        $this->issueData['fields']['issuetype']['name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testExceptionIsThrownWhenWebhookIssuePriorityIsntSet()
    {
        $this->issueData['fields']['priority']['name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraIssue::parse($this->issueData);
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testIsPriorityBlocker()
    {
        $this->issueData['fields']['priority']['name'] = 'Blocker';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isPriorityBlocker());
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testIsNotPriorityBlocker()
    {
        $this->issueData['fields']['priority']['name'] = 'Foo';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertFalse($issue->isPriorityBlocker());
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testIsStatusResolved()
    {
        $this->issueData['fields']['status']['name'] = 'Resolved';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isStatusResolved());

        $this->issueData['fields']['status']['name'] = '123 Resolved';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isStatusResolved());
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testIsStatusNotResolved()
    {
        $this->issueData['fields']['status']['name'] = 'Foo';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertFalse($issue->isStatusResolved());
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testIsStatusClosed()
    {
        $this->issueData['fields']['status']['name'] = 'Close';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertTrue($issue->isStatusClose());
    }

    /**
     * @covers JiraIssue::parse
     */
    public function testIsStatusNotClosed()
    {
        $this->issueData['fields']['status']['name'] = 'Foo';
        $issue = JiraIssue::parse($this->issueData);
        $this->assertFalse($issue->isStatusClose());
    }
}