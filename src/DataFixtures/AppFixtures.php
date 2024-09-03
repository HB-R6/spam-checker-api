<?php

namespace App\DataFixtures;

use App\Entity\SpamDomain;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const SPAM_DOMAINS = [
        "spam.net",
        "buymystuff.org",
        "amendes.gouv.shop"
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SPAM_DOMAINS as $domainName) {
            $domain = new SpamDomain();
            $domain->setDomain($domainName);

            $manager->persist($domain);
        }

        $manager->flush();
    }
}
