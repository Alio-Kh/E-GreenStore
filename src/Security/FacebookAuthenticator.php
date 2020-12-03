<?php

namespace App\Security;

use App\Entity\Client;
use App\Entity\User; 
use App\Repository\UserRepository;
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
use App\Service\SecurityService;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

 class FacebookAuthenticator extends socialAuthenticator{
   
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
      return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'facebook';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->clientRegistry->getClient('facebook'));
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /**@var FabookUser  $clientFacebook */
        $clientFacebook = $this->clientRegistry->getClient('facebook')->fetchUserFromToken($credentials);
        $facebook_user = $this->userRepository->findOneBy(['email' => $clientFacebook->getEmail()]);
        if($facebook_user)  {
          return $facebook_user;
        }
        else{
          $facebook_user= $this->userRepository->findOneBy(['facebook_id' => $clientFacebook->getId()]);
          if ($facebook_user){
            return $facebook_user;
          } 
          /** if the user is logging in for the first time, and 
           * with a different email address
           */
            $user=new User();
            $user->setEmail($clientFacebook->getEmail());
            $user->setFacebook_id($clientFacebook->getid());
            $client= new Client();
            $client->setNom($clientFacebook->getFirstName());
            $client->setPrenom($clientFacebook->getLastName());
            $this->securityService->persist_cli($client);
            $this->securityService->save_cli($user,$client);
            return $user;
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
         $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
         $targePath=$this->getTargetPath($request->getSession(),$providerKey);
        return new RedirectResponse($targePath ?: '/');
    }

}