#What is this?
This is PHP classes for Atlassian JIRA webhook data structure with events.

This library contains classes that can parse data from JIRA webhook, create data converters and events.

#Installation
With composer, create a new composer.json file and add the following code:
```
{
    "minimum-stability" : "dev",
    "require": {
        "kommuna/jirawebhook": "dev-master"
    }
}
```

Then run command `composer install`.

#Usage
##JIRA data events
To create a new event use following example code:

```php
use JiraWebhook\JiraWebhook;

try {
    $f = fopen('php://input', 'r');
    $data = stream_get_contents($f);

    if (!$data) {
        throw new JiraWebhookException('There is not data in the Jira webhook');
    }
} catch (JiraWebhookException $e) {
    error_log($e->getMessage());
}

$jiraWebhook = new JiraWebhook($data);
$jiraWebhook->addListener('jira:issue_updated', function($event, $data)
{
    if ($data->isIssueCommented()) {
        error_log('Issue have a new comment!');
    }
});

try {
    $jiraWebhook->run();
} catch (\Exception $e) {
     error_log($e->getMessage());
}
```

The `$eventName` must be some data from the [JiraWebhook\Models\JiraWebhookData]
(https://github.com/kommuna/jirawebhook/blob/master/src/Models/JiraWebhookData.php)

##JIRA data converters
To create a new converter create a new class that implements JiraWebhookDataConverter interface. Then to set and use
a new converter use following example code:

```php
use JiraWebhook\JiraWebhook;
use JiraWebhook\JiraWebhookDataConverter;

class NewConverterClass implements JiraWebhookDataConverter
{

    public function convert(JiraWebhookData $data)
    {
        $issue = $data->getIssue();

        $message = vsprintf(
            "Key: %s, Status: %s, Assignee: %s",
            [
                $issue->getKey(),
                $issue->getStatus(),
                $issue->getAssignee(),
            ]
        );

        return $message;
    }
}

JiraWebhook::setConverter('converterName', new NewConverterClass());
$message = JiraWebhook::convert('converterName', $jiraWebhookData)
```

##Helpers  

Jira webhooks format example:

```json
{
    "timestamp": 1488421130059,
    "webhookEvent": "jira:issue_created",
    "issue_event_type_name": "issue_created",
    "user": {
        "self": "https://example.atlassian.net/rest/api/2/user?username=admin",
        "name": "admin",
        "key": "admin",
        "emailAddress": "elena@k.com",
        "avatarUrls": {
            "48x48": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=48",
            "24x24": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=24",
            "16x16": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=16",
            "32x32": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=32"
        },
        "displayName": "Elena K  [Administrator]",
        "active": true,
        "timeZone": "Europe/London"
    },
    "issue": {
        "id": "10900",
        "self": "https://example.atlassian.net/rest/api/2/issue/10900",
        "key": "AJ-94",
        "fields": {
            "issuetype": {
                "self": "https://example.atlassian.net/rest/api/2/issuetype/10101",
                "id": "10101",
                "description": "A task that needs to be done.",
                "iconUrl": "https://example.atlassian.net/secure/viewavatar?size=xsmall&avatarId=10318&avatarType=issuetype",
                "name": "Task",
                "subtask": false,
                "avatarId": 10318
            },
            "timespent": null,
            "project": {
                "self": "https://example.atlassian.net/rest/api/2/project/10000",
                "id": "10000",
                "key": "AJ",
                "name": "ExampleProject",
                "avatarUrls": {
                    "48x48": "https://example.atlassian.net/secure/projectavatar?avatarId=10324",
                    "24x24": "https://example.atlassian.net/secure/projectavatar?size=small&avatarId=10324",
                    "16x16": "https://example.atlassian.net/secure/projectavatar?size=xsmall&avatarId=10324",
                    "32x32": "https://example.atlassian.net/secure/projectavatar?size=medium&avatarId=10324"
                }
            },
            "customfield_10110": null,
            "fixVersions": [
                {
                    "self": "https://example.atlassian.net/rest/api/2/version/10000",
                    "id": "10000",
                    "description": "First release",
                    "name": "Version 1.0",
                    "archived": false,
                    "released": false,
                    "releaseDate": "2017-01-29"
                }
            ],
            "customfield_10111": null,
            "aggregatetimespent": null,
            "customfield_10112": null,
            "resolution": null,
            "customfield_10113": null,
            "customfield_10114": "Not started",
            "customfield_10105": null,
            "customfield_10106": null,
            "customfield_10107": null,
            "customfield_10108": null,
            "customfield_10109": null,
            "resolutiondate": null,
            "workratio": -1,
            "lastViewed": null,
            "watches": {
                "self": "https://example.atlassian.net/rest/api/2/issue/AJ-94/watchers",
                "watchCount": 0,
                "isWatching": false
            },
            "created": "2017-03-02T02:18:49.432+0000",
            "customfield_10100": null,
            "priority": {
                "self": "https://example.atlassian.net/rest/api/2/priority/3",
                "iconUrl": "https://example.atlassian.net/images/icons/priorities/medium.svg",
                "name": "Medium",
                "id": "3"
            },
            "customfield_10101": null,
            "customfield_10102": null,
            "labels": [],
            "timeestimate": null,
            "aggregatetimeoriginalestimate": null,
            "versions": [
                {
                    "self": "https://example.atlassian.net/rest/api/2/version/10000",
                    "id": "10000",
                    "description": "First release",
                    "name": "Version 1.0",
                    "archived": false,
                    "released": false,
                    "releaseDate": "2017-01-29"
                }
            ],
            "issuelinks": [],
            "assignee": {
                "self": "https://example.atlassian.net/rest/api/2/user?username=admin",
                "name": "admin",
                "key": "admin",
                "emailAddress": "elena@k.com",
                "avatarUrls": {
                    "48x48": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=48",
                    "24x24": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=24",
                    "16x16": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=16",
                    "32x32": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=32"
                },
                "displayName": "Elena K  [Administrator]",
                "active": true,
                "timeZone": "Europe/London"
            },
            "updated": "2017-03-02T02:18:49.432+0000",
            "status": {
                "self": "https://example.atlassian.net/rest/api/2/status/10000",
                "description": "",
                "iconUrl": "https://example.atlassian.net/",
                "name": "To Do",
                "id": "10000",
                "statusCategory": {
                    "self": "https://example.atlassian.net/rest/api/2/statuscategory/2",
                    "id": 2,
                    "key": "new",
                    "colorName": "blue-gray",
                    "name": "To Do"
                }
            },
            "components": [],
            "timeoriginalestimate": null,
            "description": "Test task description",
            "timetracking": {},
            "customfield_10005": null,
            "attachment": [],
            "aggregatetimeestimate": null,
            "summary": "Test task",
            "creator": {
                "self": "https://example.atlassian.net/rest/api/2/user?username=admin",
                "name": "admin",
                "key": "admin",
                "emailAddress": "elena@k.com",
                "avatarUrls": {
                    "48x48": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=48",
                    "24x24": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=24",
                    "16x16": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=16",
                    "32x32": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=32"
                },
                "displayName": "Elena K  [Administrator]",
                "active": true,
                "timeZone": "Europe/London"
            },
            "subtasks": [],
            "customfield_10120": 1.0,
            "reporter": {
                "self": "https://example.atlassian.net/rest/api/2/user?username=admin",
                "name": "admin",
                "key": "admin",
                "emailAddress": "elena@k.com",
                "avatarUrls": {
                    "48x48": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=48",
                    "24x24": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=24",
                    "16x16": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=16",
                    "32x32": "https://secure.gravatar.com/avatar/aefb98ac295346853b105e82a9c0fa75?d=mm&s=32"
                },
                "displayName": "Elena K  [Administrator]",
                "active": true,
                "timeZone": "Europe/London"
            },
            "customfield_10000": "{}",
            "aggregateprogress": {
                "progress": 0,
                "total": 0
            },
            "customfield_10001": null,
            "customfield_10200": null,
            "customfield_10116": null,
            "customfield_10117": null,
            "environment": null,
            "customfield_10118": null,
            "customfield_10119": "0|i000ev:",
            "duedate": null,
            "progress": {
                "progress": 0,
                "total": 0
            },
            "comment": {
                "comments": [],
                "maxResults": 0,
                "total": 0,
                "startAt": 0
            },
            "votes": {
                "self": "https://example.atlassian.net/rest/api/2/issue/AJ-94/votes",
                "votes": 0,
                "hasVoted": false
            },
            "worklog": {
                "startAt": 0,
                "maxResults": 20,
                "total": 0,
                "worklogs": []
            }
        }
    }
}
```