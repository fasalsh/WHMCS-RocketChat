<?php

namespace WHMCS\Module\Notification\RocketChat;


use WHMCS\Config\Setting;
use WHMCS\Exception;
use WHMCS\Module\Notification\DescriptionTrait;
use WHMCS\Module\Contracts\NotificationModuleInterface;
use WHMCS\Notification\Contracts\NotificationInterface;


class RocketChat implements NotificationModuleInterface
{
    use DescriptionTrait;

    public function __construct()
    {
        $this->setDisplayName('Rocket.chat')
            ->setLogoFileName('logo.svg');
    }

/*
    public function settings()
    {
        return [
            'hookURL' => [
                'FriendlyName' => 'Webhook URL',
                'Type' => 'text',
                'Description' => 'Exemple: https://rocketchat.example.com/hooks/123456789[...]',
                'Placeholder' => ' ',
            ],
            'botUser' => [
                'FriendlyName' => 'Bot Username',
                'Type' => 'text',
                'Description' => 'Exemple: WHMCS',
                'Placeholder' => 'WHMCS',
            ],
        ];
    }
*/
   public function settings()
    {
        return [
            'baseURL' => [
                'FriendlyName' => 'RocketChat Base URL',
                'Type' => 'text',
                'Description' => 'The base URL for your RocketChat instance (ie: https://chat.example.com)',
                'Placeholder' => "",
            ],
        ];
    }


    public function testConnection($settings)
    {

/*
        $hookURL = $settings['hookURL'];
        $botUser = $settings['botUser'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $hookURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '
        {
            "username": "' . $botUser . '",
            "text": "",
            "attachments": [
                {
                    "title": "Connected!",
                    "text": "Connected with WHMCS",
                    "color": "#00C853"
                }
            ]
        }
        ');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close($ch); 
*/
	return true;
    }

    public function notificationSettings()
    {
        return [
	        'notificationToken' => [
                'FriendlyName' => 'Incoming WebHook Tokens',
                'Type' => 'text',
                'Description' => 'Choose the notification webhook tokens (comma delimit for more than one)',
		'Required' => true,
            ],
	];
    }

    public function getDynamicField($fieldName, $settings)
    {
        return [];
    }


    public function sendNotification(NotificationInterface $notification, $moduleSettings, $notificationSettings)
    {
//        $hookURL = $moduleSettings['hookURL'];
//        $botUser = $moduleSettings['botUser'];

	$to = explode(',', $notificationSettings['notificationToken']);
	$to = array_filter(array_unique($to));
	if (!$to) {
            throw new Exception('No Notification tokens Found');
        }
	$postData=[
                'text' => sprintf("[%s](%s) \n %s", $notification->getTitle(), $notification->getUrl(), $notification->getMessage()),
	];
	foreach ($notification->getAttributes() as $attribute) {
	 	$postData['attachments'][] = [
            		'title' => $attribute->getLabel(),
            		'text' => $attribute->getValue(),
            		'title_link' => $attribute->getUrl(),
			//'color'=> "#00C853"
        	];
  	}	

        foreach ($to as $k => $notificationToken) {
		$notificationURL=sprintf("%s/hooks/%s", $moduleSettings['baseURL'], $notificationToken);

		$ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $notificationURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
/*
        {
            "username": "rocket.cat",
            "text": "",
            "attachments": [
                {
                    "title": "' . $notification->getTitle() . '",
                    "title_link": "' . $notification->getUrl() . '",
                    "text": "' . $notification->getMessage() . '",
                    "color": "#00C853"
                }
            ]
        }
        ');
*/

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close($ch);
    }
}
}
