# What is this?  

This is PHP library for processing and handling Atlassian JIRA webhook data.

It contains classes that can parse data from [JIRA webhooks](https://developer.atlassian.com/jiradev/jira-apis/webhooks), create listeners for custom events and interface for creating converters for parsed data.

The package is meant to be used with the [Vicky slackbot](https://github.com/kommuna/vicky), but it can also be used independently.

# Installation
  
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

# Usage  

## JIRA data events    

To create a new event use following example code:

```php
use JiraWebhook\JiraWebhook;
use JiraWebhook\Models\JiraWebhookData;

require __DIR__.'/vendor/autoload.php';
$config = require '/etc/vicky/config.php';

$jiraWebhook = new JiraWebhook();

$jiraWebhook->addListener('jira:event_name', function($e, $data)
{
    /**
     * Your code here
     */ 
});

try {
    $f = fopen('php://input', 'r');
    $data = stream_get_contents($f);

    if (!$data) {
        throw new JiraWebhookException('There is no data in the Jira webhook');
    }

    $jiraWebhook->run();
} catch (\Exception $e) {
     error_log($e->getMessage());
}
```

## JIRA data converters  

To create a new converter create a new class that implements the JiraWebhookDataConverter interface. Then to set and use
a new converter use the following example code:

```php
use JiraWebhook\JiraWebhookDataConverter;
use JiraWebhook\Models\JiraWebhookData;

class NewConverterClass implements JiraWebhookDataConverter
{
    public function convert(JiraWebhookData $data)
    {
        $issue        = $data->getIssue();
        $assigneeName = $issue->getAssignee()->getName();
        $comment      = $issue->getIssueComments()->getLastComment();
        
        $message = vsprintf(
            ":no_entry_sign: <%s|%s> %s: %s ➠ @%s\n@%s ➠ %s",
            [
                $issue->getUrl(),
                $issue->getKey(),
                $issue->getStatusName(),
                $issue->getSummary(),
                $assigneeName,
                $comment->getAuthor()->getName(),
                $comment->getBody(0, 178)
            ]
        );
        
        return $message;
    }
}

JiraWebhook::setConverter('converterName', new NewConverterClass());
$message = JiraWebhook::convert('converterName', $data);
```

Please refer to [Slack's documentation for message formatting](https://api.slack.com/docs/message-attachments) for more details.

# Testing  

Run ```vendor/bin/phpunit``` or just ```phpunit``` if you have it installed globally.