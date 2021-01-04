<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\Images;
use AppBundle\Entity\User;

class ImagesControllerTest extends WebTestCase
{    
    private $client = null;
    
    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    
    /**
     * Test visit images page for not logged in user
     */
    public function testIndexNotLoggedIn()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
    
    /**
     * Test visit images page for logged in user
     */
    public function testIndexLoggedIn()
    {
        $this->logIn();
        $crawler = $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $this->assertStringContainsString('test@test.com', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /**
     * Test add image 
     */
    public function testAdd()
    {
        $fileName = 'test.jpg';
        $data = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
           . 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
           . 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
           . '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
        $data = base64_decode($data);
        $imgRaw = imagecreatefromstring($data);
        
        if ($imgRaw !== false) {
            imagejpeg($imgRaw, 'web/uploads/test.jpg', 100);
            imagedestroy($imgRaw);
            $file = new UploadedFile( 'web/uploads/images/test.jpg', 'test.jpg', 'image/jpeg', null, true, true);
        }

        $this->logIn();
        $name = 'test-img';
        $token = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('image');
        $formData = [
            'image' => [
                'name' => $name,
                'image' => $file,
                '_token' => $token->getValue(),
            ],
        ];
        
        $crawler = $this->client->request('POST', '/add', $formData);
        $container = $this->client->getContainer();
        $newImage = $container->get('doctrine')->getManager()->getRepository(Images::class)->findOneByName($name);
        $this->assertNotNull($newImage);
    }
    
    private function logIn()
    {
        $container = $this->client->getContainer();
        $session = $container->get('session');
        $firewall = 'main';
        $user = $container->get('doctrine')->getManager()->getRepository(User::class)->findOneByEmail('test@test.com');
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, ['ROLE_USER']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

}