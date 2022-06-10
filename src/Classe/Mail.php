<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail

{
    private $api_key = 'bc6996101d6c0ed49226df3fc3586b35';
    private $api_key_secret = 'a88d61412197b038175b03226a6b80ed';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        
        $body = [
          'Messages' => [
             [
               'From' => [
                   'Email' => "afrobio13@gmail.com",
                   'Name' => "Afrobio"
               ],
               'To' => [
                   [
                       'Email' => $to_email,
                       'Name' => $to_name
                   ]
               ],
               'TemplateID' => 3433288,
               'TemplateLanguage' => true,
               'Subject' => $subject,
               'Variables' => [
                   'content' => $content
               ]

            ]
        ]
     ];
     $response = $mj->post(Resources::$Email, ['body' => $body]);
     $response->success();
    }
}