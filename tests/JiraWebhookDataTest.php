<?php
/**
 * Created by PhpStorm.
 * Author: Elena Kolevska
 * Date: 3/9/17
 * Time: 23:43
 */
namespace JiraWebhook;

use PHPUnit_Framework_TestCase;
use Faker\Factory;
use Faker\Provider\Internet;
use JiraWebhook\Models\JiraWebhookData;
use JiraWebhook\Exceptions\JiraWebhookDataException;

class JiraWebhookDataTest extends PHPUnit_Framework_TestCase {

    protected $issue_created_payload;

    public function setUp()
    {
        $faker = Factory::create();
        $faker->addProvider(new Internet($faker));

        $id = $faker->randomNumber;
        $username = $faker->userName;
        $email = $faker->email;

        $this->payload = [
            'timestamp' => $faker->unixTime,
            'webhookEvent' => 'jira:issue_created',
            'issue_event_type_name' => 'issue_created',
            'user' => [
                'self' => $faker->url,
                'name' => $username,
                'key' => $username,
                'emailAddress' => $email,
                'avatarUrls' => [
                    '48x48' => $faker->url,
                    '24x24' => $faker->url,
                    '32x32' => $faker->url,
                    '16x16' => $faker->url
                ],
                'displayName' => $username,
                'active' => true,
                'timeZone' => $faker->timezone
            ],
            'issue' => [
                'id' => $id,
                'self' => $faker->url,
                'key' => $faker->word,
                'fields' => [
                    'issuetype' => [
                        'self' => $faker->url,
                        'id' => $id,
                        'description' => $faker->sentence(),
                        'iconUrl' => $faker->url,
                        'name' => array_rand(['Task', 'Operations']),
                        'subtask' => false,
                        'avatarId' => $faker->url,
                    ]
                ],
                'priority' => [
                    "self" => $faker->url,
                    "iconUrl" => $faker->url,
                    "name" => array_rand(['Low', 'High']),
                    "id" => $id,
                ]
            ],
            'changelog' => [

            ]
        ];
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