<?php

namespace DATA;

class SocialIconsData
{
    public function getData(): array
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        return [
            [
                'name' => 'facebook',
                'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($currentUrl),
                'path' => "/client/vectors/facebook-icon.svg",
                'width' => '20',
                'height' => '20',
                'attributes' => [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer',
                    'data-social' => 'facebook'
                ]
            ],
            [
                'name' => 'VK',
                'href' => 'https://vk.com/share.php?url=' . urlencode($currentUrl),
                'path' => "/client/vectors/vk-icon.svg",
                'width' => '20',
                'height' => '20',
                'attributes' => [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer',
                    'data-social' => 'vk'
                ]
            ],
            [
                'name' => 'ok',
                'href' => 'https://connect.ok.ru/offer?url=' . urlencode($currentUrl),
                'path' => "/client/vectors/ok-icon.svg",
                'width' => '20',
                'height' => '20',
                'attributes' => [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer',
                    'data-social' => 'ok'
                ]
            ],
            [
                'name' => 'google',
                'href' => 'https://plus.google.com/share?url=' . urlencode($currentUrl),
                'path' => "/client/vectors/google-icon.svg",
                'width' => '20',
                'height' => '20',
                'attributes' => [
                    'target' => '_blank',
                    'rel' => 'noopener noreferrer',
                    'data-social' => 'google'
                ]
            ]
        ];
    }
}
