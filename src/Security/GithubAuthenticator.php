<?php

namespace App\Security;

use App\Entity\Client;
use App\Entity\User; // your user entity
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use App\Service\SecurityService;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GithubAuthenticator extends SocialAuthenticator{

    use TargetPathTrait;

    private $router;
    private $clientRegistry;
    private $userRepository;
    private $securityService;

    public function __construct(SecurityService $securityService,UserRepository $userRepository,RouterInterface $router, ClientRegistry $clientRegistry){
          $this->router= $router;
          $this->clientRegistry= $clientRegistry;
          $this->userRepository= $userRepository;
          $this->securityService= $securityService;
    }

  public function start(Request $request, AuthenticationException $authException = null)
    {
       return new RedirectResponse($this->router->generate('login'));
    }

    public function supports(Request $request)
    {
      return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'github';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->clientRegistry->getClient('github'));
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /**@var GithubResourceOwner $clientGit */
        $clientGit = $this->clientRegistry->getClient('github')->fetchUserFromToken($credentials);
        $user = $this->userRepository->findOneBy(['email' => $clientGit->getEmail()]);
        if($user) return $user;
        else{
            $user=new User();
            $user->setEmail($clientGit->getEmail());
            $client= new Client();
            $client->setNom($clientGit->getName());
            $client->setPrenom($clientGit->getNickname());
            $this->securityService->persist_cli($client);
            $this->securityService->save_cli($user,$client);
            return $user;
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
       
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
         $targePath=$this->getTargetPath($request->getSession(),$providerKey);
        return new RedirectResponse($targePath ?: '/');
    }

}
