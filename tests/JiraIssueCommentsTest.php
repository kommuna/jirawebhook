<?php
/**
 * Test for methods in class JiraWebhook\Models\JiraIssueComments
 *
 * @credits https://github.com/kommuna
 * @author  Miss Lv lv@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook\Tests;

use JiraWebhook\Models\JiraIssueComments;
use PHPUnit_Framework_TestCase;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

/**
 * @property  array issueCommentsData
 *
 */
class JiraIssueCommentsTest extends PHPUnit_Framework_TestCase {

    protected $issueCommentsData;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $payload = JiraWebhookPayloadFactory::create();
        $this->issueCommentsData = $payload['issue']['fields']['comment'];
    }

    /**
     * @covers JiraIssueComments::parse
     */
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