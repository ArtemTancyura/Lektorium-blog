<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testRedirect()
    {
        $client = static::createClient();

        $client->request('GET', '/admin');

        $client->request('GET', '/home');

        $this->assertTrue(

            $client->getResponse()->isRedirect('/login')

        );
    }

    public function testRegistration()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registration');

        $this->assertCount(7, $crawler->filter('input'));

        $this->assertCount(2, $crawler->filter('a'));

        $this->assertCount(2, $crawler->filter('p'));

        $this->assertCount(1, $crawler->filter('h2'));
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertCount(3, $crawler->filter('input'));

        $this->assertCount(2, $crawler->filter('a'));

        $this->assertCount(2, $crawler->filter('p'));

        $this->assertCount(1, $crawler->filter('h2'));
    }
}
