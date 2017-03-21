<?php

/**
 * Author: Elena Kolevska
 * Date: 3/20/17
 * Time: 14:55
 */

namespace JiraWebhook\Tests\Factories;

use Faker\Factory;
use Faker\Provider\Internet;

class JiraWebhookPayloadFactory
{
    public static function create()
    {
        $faker = Factory::create();
        $faker->addProvider(new Internet($faker));

        $id = $faker->randomNumber;
        $username = $faker->userName;
        $email = $faker->email;

        $issueTypes = ['Task', 'Operations'];
        $priorities = ['Low', 'High'];

        return   [
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
                    "summary" => $faker->sentence,
                    'issuetype' => [
                        'self' => $faker->url,
                        'id' => $id,
                        'description' => $faker->sentence(),
                        'iconUrl' => $faker->url,
                        'name' => $issueTypes[array_rand($issueTypes)],
                        'subtask' => false,
                        'avatarId' => $faker->url,
                    ],
                    'priority' => [
                        "self" => $faker->url,
                        "iconUrl" => $faker->url,
                        "name" => $priorities[array_rand($priorities)],
                        "id" => $id,
                    ],
                    "project" => [
                        "self" => $faker->url,
                        "id" => $id,
                        "key" => $faker->word,
                        "name" => $faker->word,
                        'avatarUrls' => [
                            '48x48' => $faker->url,
                            '24x24' => $faker->url,
                            '32x32' => $faker->url,
                            '16x16' => $faker->url
                        ],
                    ],
                    "assignee" => [
                        "self" => $faker->url,
                        "name" => $faker->name,
                        "key" => $faker->word,
                        "emailAddress" => $faker->email,
                        'avatarUrls' => [
                            '48x48' => $faker->url,
                            '24x24' => $faker->url,
                            '32x32' => $faker->url,
                            '16x16' => $faker->url
                        ],
                        "displayName" => $faker->name,
                        "active" => true,
                        "timeZone" => $faker->timezone
                    ],
                    "status" => [
                        "self" => $faker->url,
                        "description" => $faker->sentences(),
                        "iconUrl" => $faker->url,
                        "name" => $faker->email,
                        "id" => $faker->randomDigitNotNull,

                        "statusCategory" => [
                            "self" => $faker->url,
                            "id" => $faker->randomDigitNotNull,
                            "key" => $faker->word,
                            "colorName" => $faker->colorName,
                            "name" => $faker->word,

                        ]
                    ],
                    "comment" => [
                        "comments" => [
                            [
                                "self" => $faker->url,
                                "id" => $faker->numberBetween(0,100000),
                                "author" => [
                                    "self" => $faker->url,
                                    "name" => $faker->name,
                                    "key" => $faker->word,
                                    "emailAddress" => $faker->email,
                                    'avatarUrls' => [
                                        '48x48' => $faker->url,
                                        '24x24' => $faker->url,
                                        '32x32' => $faker->url,
                                        '16x16' => $faker->url
                                    ],
                                    "displayName" => $faker->name,
                                    "active" => true,
                                    "timeZone" => $faker->timezone
                                ],
                                "body" => $faker->sentence,
                                "updateAuthor" => [
                                    "self" => $faker->url,
                                    "name" => $faker->name,
                                    "key" => $faker->word,
                                    "emailAddress" => $faker->email,
                                    'avatarUrls' => [
                                        '48x48' => $faker->url,
                                        '24x24' => $faker->url,
                                        '32x32' => $faker->url,
                                        '16x16' => $faker->url
                                    ],
                                    "displayName" => $faker->name,
                                    "active" => true,
                                    "timeZone" => $faker->timezone
                                ],
                                "created" => $faker->dateTime,
                                "updated" => $faker->dateTime
                            ],
                            [
                                "self" => $faker->url,
                                "id" => $faker->numberBetween(0,100000),
                                "author" => [
                                    "self" => $faker->url,
                                    "name" => $faker->name,
                                    "key" => $faker->word,
                                    "emailAddress" => $faker->email,
                                    'avatarUrls' => [
                                        '48x48' => $faker->url,
                                        '24x24' => $faker->url,
                                        '32x32' => $faker->url,
                                        '16x16' => $faker->url
                                    ],
                                    "displayName" => $faker->name,
                                    "active" => true,
                                    "timeZone" => $faker->timezone
                                ],
                                "body" => $faker->sentence,
                                "updateAuthor" => [
                                    "self" => $faker->url,
                                    "name" => $faker->name,
                                    "key" => $faker->word,
                                    "emailAddress" => $faker->email,
                                    'avatarUrls' => [
                                        '48x48' => $faker->url,
                                        '24x24' => $faker->url,
                                        '32x32' => $faker->url,
                                        '16x16' => $faker->url
                                    ],
                                    "displayName" => $faker->name,
                                    "active" => true,
                                    "timeZone" => $faker->timezone
                                ],
                                "created" => $faker->dateTime,
                                "updated" => $faker->dateTime
                            ]
                        ],
                        "maxResults" => 10,
                        "total" => 2,
                        "startAt" => 0
                    ],
                ],
            ],
            'changelog' => [

            ]
        ];
    }
}