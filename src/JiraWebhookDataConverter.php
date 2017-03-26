<?php
/**
 * Interface for JiraWebhookData converters.
 *
 * @credits https://github.com/kommuna
 * @author  chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook;

use JiraWebhook\Models\JiraWebhookData;
use Maknz\Slack\Message;

interface JiraWebhookDataConverter
{
    /**
     * Convert $data into a Slack Client Instance
     *
     * @param JiraWebhookData $data parsed data from JIRA webhook
     * @param Message $message Slack Client Message instance
     *
     * @return Message
     */
    public function convert(JiraWebhookData $data, Message $message);
}