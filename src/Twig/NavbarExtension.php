<?php

namespace App\Twig;

use App\Entity\NavbarMenu;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class NavbarExtension extends AbstractExtension implements GlobalsInterface
{

    /*
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGlobals() : array
    {
        return array(
            'navbar_elements' => $this->em->getRepository(NavbarMenu::class)->getMenu(),

        );
    }
}
