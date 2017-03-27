#What is this?  

This is PHP library for processing and handling Atlassian JIRA webhook data.

It contains classes that can parse data from [JIRA webhooks](https://developer.atlassian.com/jiradev/jira-apis/webhooks), create [slack client message objects](https://github.com/maknz/slack) and events.

The package is meant to be used with the [Vicky slackbot](https://github.com/kommuna/vicky), but it can also be used independently.

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
        throw new JiraWebhookException('There is no data in the Jira webhook');
    }
} catch (JiraWebhookException $e) {
    error_log($e->getMessage());
}

$jiraWebhook = new JiraWebhook($data);
$jiraWebhook->addListener('jira:issue_updated', function($event, $data)
{
    if ($data->isIssueCommented()) {
        // Instantiate a new Slack Client (refer to https://github.com/maknz/slack for documentation)
        $slackClient = new Client($incomingWebhookUrl, $clientSettings);
        
        // Get Slack Client message object
        $slackClientMessage = $slackClient->createMessage();
        
        // Set up the slack message format
        JiraWebhook::convert('JiraDefaultToSlack', $data, $slackClientMessage);
        
        // Send off the message to Slack's API
        $slackClientMessage->to('#channel');
        $slackClientMessage->send();
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

To create a new converter create a new class that implements the JiraWebhookDataConverter interface. Then to set and use
a new converter use the following example code:

```php
use Maknz\Slack\Message;
use JiraWebhook\JiraWebhook;
use JiraWebhook\JiraWebhookDataConverter;

class NewConverterClass implements JiraWebhookDataConverter
{

    public function convert(JiraWebhookData $data, Message $clientMessage)
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
        
        $attachment = [
            "color" => $issue->getColour(),
            "fallback" => $message,
            "pretext" => $data->getIssueEventDescription(),
            "title" => vsprintf("(%s) %s", [$issue->getKey(), $issue->getSummary()]),
            "title_link" => $issue->getUrl(),

            'fields' => [
                [
                    'title' => 'Status',
                    'value' => $issue->getStatus(),
                    'short' => true // whether the field is short enough to sit side-by-side other fields
                ],
                [
                    'title' => 'Priority',
                    'value' => $issue->getPriority(),
                    'short' => true
                ]
            ],
        ];

        $clientMessage->attach($attachment);

        return $clientMessage;
    }
}

JiraWebhook::setConverter('converterName', new NewConverterClass());
$message = JiraWebhook::convert('converterName', $jiraWebhookData)
```

Please refer to [Slack's documentation for message formatting](https://api.slack.com/docs/message-attachments) for more details on the `$attachment` variable above.

# Testing  

Run ```vendor/bin/phpunit``` or just ```phpunit``` if you have it installed globally.