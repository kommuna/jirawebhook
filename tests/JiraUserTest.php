<?php
/**
 * Test for methods in class JiraWebhook\Models\JiraUser
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
use JiraWebhook\Exceptions\JiraWebhookDataException;
use JiraWebhook\Tests\Factories\JiraWebhookPayloadFactory;

/**
 * @property  array issueUser
 */
class JiraUserTest extends PHPUnit_Framework_TestCase {

    protected $issueUser;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $payload = JiraWebhookPayloadFactory::create();
        $this->issueUser = $payload['user'];
    }

    /**
     * @covers JiraUserTest::parse
     */
    public function testParse()
    {
        $issueComments = JiraUser::parse($this->issueUser);

        $this->assertEquals($this->issueUser['self'], $issueComments->getSelf());
        $this->assertEquals($this->issueUser['name'], $issueComments->getName());
        $this->assertEquals($this->issueUser['key'], $issueComments->getKey());
        $this->assertEquals($this->issueUser['emailAddress'], $issueComments->getEmail());
        $this->assertEquals($this->issueUser['avatarUrls'], $issueComments->getAvatarURLs());
        $this->assertEquals($this->issueUser['displayName'], $issueComments->getDisplayName());
        $this->assertEquals($this->issueUser['active'], $issueComments->getActive());
        $this->assertEquals($this->issueUser['timeZone'], $issueComments->getTimeZone());
    }

    /**
     * @covers JiraUserTest::parse
     */
    public function testExceptionIsThrownWhenUserNameIsntSet()
    {
        $this->issueUser['name'] = null;
        $this->expectException(JiraWebhookDataException::class);
        JiraUser::parse($this->issueUser);
    }

}