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
##JIRA data converters
To create a new converter you should create a new class that implements JiraWebhookDataConverter interface. Then to set
and use a new converter you should use following example code:

```php
use JiraWebhook\JiraWebhook;
use JiraWebhook\JiraWebhookDataConverter;

class NewConverterClass implements JiraWebhookDataConverter
{

    public function convert(JiraWebhookData $data)
    {
        /**
         * Your code here
         */
    }
}

JiraWebhook::setConverter('converterName', new NewConverterClass());
JiraWebhook::convert('converterName', $jiraWebhookData)
```

##JIRA data events
To create a new event you should use following example code:

```php
use JiraWebhook\JiraWebhook;

$jiraWebhook = new JiraWebhook($receivedData);
$jiraWebhook->addListener('eventName', $listener);
$jiraWebhook->run();
```

The `$eventName` must be some data from the [JiraWebhook\Models\JiraWebhookData]
(https://github.com/kommuna/jirawebhook/blob/master/src/Models/JiraWebhookData.php)