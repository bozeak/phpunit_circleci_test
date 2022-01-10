<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    protected function setUp()
    {
        $this->fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);

        $this->client = $this->makeClient();

        $this->crawler = $this->client->request('GET', '/');
    }

    public function testEnclosuresAreShownOnTheHomepage()
    {
        $this->assertStatusCode(200, $this->client);

        $table = $this->crawler->filter('.table-enclosures');
        $this->assertCount(3, $table->filter('tbody tr'));
    }

    public function testThereIsAnAlarmButtonWithoutSecurity()
    {
        $this->fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ])->getReferenceRepository();
        $enclosure = $this->fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());
        $this->assertGreaterThan(0, $this->crawler->filter($selector)->count());
    }

    public function testItGrowsADinosaurFromSpecification()
    {

        $this->client->followRedirects();

        $this->assertStatusCode(200, $this->client);

        $form = $this->crawler->selectButton('Grow dinosaur')->form();
        $form['enclosure']->select(3);
        $form['specification']->setValue('large herbivore');

        $this->client->submit($form);

        $this->assertContains('Grew a large herbivore in enclosure #3', $this->client->getResponse()->getContent());
    }
}