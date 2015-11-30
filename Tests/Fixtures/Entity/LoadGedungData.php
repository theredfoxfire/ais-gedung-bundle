<?php

namespace Ais\GedungBundle\Tests\Fixtures\Entity;

use Ais\GedungBundle\Entity\Gedung;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadGedungData implements FixtureInterface
{
    static public $gedungs = array();

    public function load(ObjectManager $manager)
    {
        $gedung = new Gedung();
        $gedung->setTitle('title');
        $gedung->setBody('body');

        $manager->persist($gedung);
        $manager->flush();

        self::$gedungs[] = $gedung;
    }
}
