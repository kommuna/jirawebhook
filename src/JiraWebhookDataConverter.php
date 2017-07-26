<?php
/**
 * Interface for JiraWebhookData converters.
 *
 * @credits https://github.com/kommuna
 * @author  Chewbacca chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook;

use JiraWebhook\Models\JiraWebhookData;

abstract class JiraWebhookDataConverter
{
    /**
     * Convert $data into a formatted string message
     *
     * @param JiraWebhookData $data parsed data from JIRA webhook
     *
     * @return string
     */
    abstract public function convert(JiraWebhookData $data);

    /**
     * Truncate string
     *
     * @param string $commentBody JIRA issue comment body
     * @param int    $length
     *
     * @return bool|string
     */
    public function truncateCommentBody($commentBody, $length = 178)
    {
        return substr($commentBody, 0, $length);
    }
}