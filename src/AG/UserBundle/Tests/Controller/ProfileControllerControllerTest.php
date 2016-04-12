<?php

namespace AG\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerControllerTest extends WebTestCase
{
    public function testEditcolors()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'edit-colors');
    }

}
