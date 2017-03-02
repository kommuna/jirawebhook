#What is this?
This is PHP classes for Atlassian JIRA webhook data structure with events.

This library contains classes that can parse data from [JIRA webhook]
(https://developer.atlassian.com/jiradev/jira-apis/webhooks), create data converters and events.

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