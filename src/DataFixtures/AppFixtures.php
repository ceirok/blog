<?php
/**
 * Created by PhpStorm.
 * User: ceirokilp
 * Date: 25/09/2018
 * Time: 12:28
 */

namespace App\DataFixtures;


use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++)
        {
            $microPost = new MicroPost();
            $microPost->setText('Some random text '. rand(0, 100));
            $microPost->setTime(new \Datetime('2018-03-15'));
            $microPost->setUser($this->getReference('john doe'));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('john doe');
        $user->setFullName('John Doe');
        $user->setEmail('john_doe@test.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'john123'));

        $this->addReference('john doe', $user);

        $manager->persist($user);

        $manager->flush();
    }
}