<?php

namespace Mabasic\Mailchimp;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Read this:
 * Manage Subscribers with the MailChimp API
 * http://developer.mailchimp.com/documentation/mailchimp/guides/manage-subscribers-with-the-mailchimp-api/
 */
class Mailchimp
{
    protected $guzzle;

    public function __construct($key, $dc = 'us17')
    {
        $this->guzzle = new Client([
            'base_uri' => "https://{$dc}.api.mailchimp.com/3.0/",
            'headers' => [
                'Authorization' => 'apikey: ' . $key
            ]
        ]);
    }

    /**
     * In previous versions of the API, we exposed internal database IDs eid and leid for
     * emails and list/email combinations. In API 3.0, we no longer use or expose either of
     * these IDs. Instead, we identify your subscribers by the MD5 hash of the lowercase version
     * of their email address so you can easily predict the API URL of a subscriber’s data.
     *
     * For example, to get the MD5 hash of the email address Urist.McVankab@freddiesjokes.com,
     * first convert the address to its lowercase version: urist.mcvankab@freddiesjokes.com.
     * The MD5 hash of urist.mcvankab@freddiesjokes.com is 62eeb292278cc15f5817cb78f7790b08.
     */
    protected function identifySubscriber(string $email): string
    {
        return hash('md5', strtolower($email));
    }

    /**
     * To see if an email address subscribed to your list, you’ll need your List ID,
     * the email address, and its MD5 hash.
     *
     * For the address, urist.mcvankab@freddiesjokes.com, you’d make a GET request
     * to /3.0/lists/9e67587f52/members/62eeb292278cc15f5817cb78f7790b08.
     *
     * If the call returns a 404 response, the subscriber isn’t on your list.
     * They may have been deleted, or they were never on the list at all.
     *
     * If the call returns a 200 response, check the status field. You’ll see one of these labels in the status field.
     *
     * subscribed
     * - This address is on the list and ready to receive email. You can only send campaigns to ‘subscribed’ addresses.
     * unsubscribed
     * - This address used to be on the list but isn’t anymore.
     * pending
     * - This address requested to be added with double-opt-in but hasn’t confirmed their subscription yet.
     * cleaned
     * - This address bounced and has been removed from the list.
     */
    protected function checkSubscriptionStatus(string $list_id, string $email)
    {
        try {
            $response = $this->guzzle->get("lists/{$list_id}/members/{$this->identifySubscriber($email)}");

            return json_decode($response->getBody())->status;
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * To add someone to your list, send a POST request to the List Members
     * endpoint: /3.0/lists/9e67587f52/members/. The request body should be a
     * JSON object that has the member information you want to add, with status
     * and any other required list fields.
     *
     * {
     *     "email_address": "urist.mcvankab@freddiesjokes.com",
     *     "status": "subscribed",
     *     "merge_fields": {
     *         "FNAME": "Urist",
     *         "LNAME": "McVankab"
     *     }
     * }
     *
     * You’ll receive errors if the address is already on your list, or if any required
     * merge_fields are missing. Find the full list of fields available in the List Member Schema.
     *
     * Subscriber Status
     *
     * To add a subscriber, you must include the subscriber’s status in your JSON object.
     * - Use `subscribed` to add an address right away.
     * - Use `pending` to send a confirmation email.
     * - Use `unsubscribed` or `cleaned` to archive unused addresses.
     *
     * The unsubscribed option is useful if you import addresses from another service.
     * When you add subscribers to the unsubscribed or cleaned status groups, you’ll have a
     * record of the subscriber, and we’ll prevent sends to that address in the future.
     * That’s ideal for inactive or unsubscribed addresses, which can increase spam complaints
     * if they’re accidentally added as subscribed.
     */
    public function subscribeAnAddress(string $list_id, string $email, string $status = 'pending')
    {
        if (in_array($this->checkSubscriptionStatus($list_id, $email), ['subscribed', 'pending', 'unsubscribed'])) {
            return false;
        }

        $response = $this->guzzle->post("lists/{$list_id}/members", [
            'json' => [
                'email_address' => $email,
                'status' => $status
            ]
        ]);

        return true;
    }
}
