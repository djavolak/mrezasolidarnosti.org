<?php

namespace Solidarity\Backend\Blocks\Profiledata;

use Skeletor\ContentEditor\Contracts\BlockViewFilterInterface;
use Solidarity\Frontend\Service\Session;

class ProfiledataViewFilter implements BlockViewFilterInterface
{
    public function __construct(
        protected Session $session
    )
    {

    }

    public function filter(array $data): array
    {
        $data['isDonorLoggedIn'] = $this->session->isDonor();
        if ($data['isDonorLoggedIn']) {
            $data['donor'] = $this->session->getUser();
        }

        return $data;
    }
}
