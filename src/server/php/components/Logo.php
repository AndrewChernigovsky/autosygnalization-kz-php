<?php

namespace COMPONENTS;

use DATA\ContactsData;

class Logo
{
    private $icon;
    public function __construct()
    {
        $this->icon = new ContactsData();
    }

    public function getLogo()
    {
        return $this->icon->getLogo();
    }
}
