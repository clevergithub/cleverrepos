<?php

namespace Clever\CleverSMSFormBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'inscription');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'modification');
    }

}
