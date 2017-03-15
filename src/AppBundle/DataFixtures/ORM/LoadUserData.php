<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $encoder = $this->container->get('security.password_encoder');
        $encoded = $encoder->encodePassword($userAdmin, 'admin');
        $userAdmin->setPassword($encoded);
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        $userAdmin->setIsActive(true);
        $userAdmin->setEmail('test@gmail.com');

        $manager->persist($userAdmin);
        $manager->flush();
    }
}