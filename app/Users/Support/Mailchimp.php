<?php

namespace Ollieread\Users\Support;

use DrewM\MailChimp\MailChimp as MailchimpAPI;

class Mailchimp
{
    public const INTERESTS = [
        'mwl'     => 'da9bfe9006',
        'ksa'     => '9749ac74ae',
        'porter'  => 'eb01032502',
        'surveys' => '4b8a5eb909',
        'monthly' => '74918694cc',
    ];

    const API_LIST_SUBSCRIBERS = '/lists/{list_id}/members';
    const API_LIST_SUBSCRIBER  = '/lists/{list_id}/members/{subscriber_hash}';
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Mailchimp constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->mailchimp = new MailchimpAPI(config('services.mailchimp.key'));
    }

    protected function createSubscription(string $email, string $username, array $interests = [], ?string $firstName = null, ?string $lastName = null): array
    {
        return $this->request(
            'POST',
            str_replace('{list_id}', config('services.mailchimp.list'), self::API_LIST_SUBSCRIBERS),
            [
                'email_address' => $email,
                'email_type'    => 'html',
                'status'        => 'subscribed',
                'merge_fields'  => [
                    'LNAME'    => $lastName ?? '',
                    'FNAME'    => $firstName ?? '',
                    'USERNAME' => $username,
                ],
                'interests'     => $interests,
            ]
        );
    }

    public function isSubscribed(string $email): bool
    {
        $response = $this->request(
            'GET',
            str_replace(['{list_id}', '{subscriber_hash}'], [config('services.mailchimp.list'), md5(strtolower($email))], self::API_LIST_SUBSCRIBER)
        );

        return ! empty($response);
    }

    private function request(string $verb, string $url, array $data = [])
    {
        $response = $this->mailchimp->{$verb}($url, $data);

        if ($this->mailchimp->success()) {
            return $response;
        }

        return [];
    }

    public function subscribe(string $email, string $username, array $interests = [], ?string $firstName = null, ?string $lastName = null): bool
    {
        if ($this->isSubscribed($email)) {
            $response = $this->updateSubscription($email, $username, $interests, $firstName, $lastName);
        } else {
            $response = $this->createSubscription($email, $username, $interests, $firstName, $lastName);
        }

        return $response && $response['status'] === 'subscribed';
    }

    public function unsubscribe(string $email): bool
    {
        if ($this->isSubscribed($email)) {
            $response = $this->request(
                'PATCH',
                str_replace(['{list_id}', '{subscriber_hash}'], [config('services.mailchimp.list'), md5(strtolower($email))], self::API_LIST_SUBSCRIBER),
                [
                    'status' => 'unsubscribed',
                ]
            );

            return $response && $response['status'] === 'subscribed';
        }

        return true;
    }

    protected function updateSubscription(string $email, string $username, array $interests = [], ?string $firstName = null, ?string $lastName = null): array
    {
        return $this->request(
            'PATCH',
            str_replace(['{list_id}', '{subscriber_hash}'], [config('services.mailchimp.list'), md5(strtolower($email))], self::API_LIST_SUBSCRIBER),
            [
                'email_address' => $email,
                'email_type'    => 'html',
                'status'        => 'subscribed',
                'merge_fields'  => [
                    'LNAME'    => $lastName ?? '',
                    'FNAME'    => $firstName ?? '',
                    'USERNAME' => $username,
                ],
                'interests'     => $interests,
            ]
        );
    }
}
