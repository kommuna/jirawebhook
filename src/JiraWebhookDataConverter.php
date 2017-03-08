<?php
/**
 * This file contains interface for JiraWebhookData converters.
 *
 * @credits https://github.com/kommuna
 * @author  chewbacca@devadmin.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JiraWebhook;

use JiraWebhook\Models\JiraWebhookData;

interface JiraWebhookDataConverter
{
    /**
     * Convert $data into message
     *
     * @param JiraWebhookData $data parsed data from JIRA webhook
     * 
     * @return mixed
     */
    public function convert(JiraWebhookData $data);
}