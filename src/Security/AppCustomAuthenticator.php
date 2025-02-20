<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Core\Security;

/**
 * Voir : https://symfony.com/doc/current/security/custom_authenticator.html
 */
class AppCustomAuthenticator extends AbstractAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;
    
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    
    /**
     * Détermine si l'authentificateur doit s'activer pour la requête courante.
     * Ici, il s'active uniquement pour les requêtes POST sur la route "app_login".
     */
    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }
    
    /**
     * Extrait les informations d'identification de la requête et retourne un Passport.
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');
        
        // Optionnel : stocker l'email dans la session pour pré-remplir le formulaire en cas d'erreur
        // $request->getSession()->set(Security::LAST_USERNAME, $email);
    
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
            ]
        );
    }
    
    /**
     * Redirige l'utilisateur après une authentification réussie en fonction de son rôle.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?RedirectResponse
    {
        $user = $token->getUser();
        $roles = $user->getRoles();
    
        if (in_array('ROLE_ADMIN', $roles, true)) {
            $targetUrl = $this->urlGenerator->generate('admin_home');
        } elseif (in_array('ROLE_ARTISTE', $roles, true)) {
            $targetUrl = $this->urlGenerator->generate('artiste_home');
        } else {
            $targetUrl = $this->urlGenerator->generate('auditeur_home');
        }
    
        return new RedirectResponse($targetUrl);
    }
    
    /**
     * Retourne l'URL de la page de connexion.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }
    
    /**
     * Gère l'échec de l'authentification.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            // $request->getSession()->getFlashBag()->add('error', 'Authentification échouée : ' . $exception->getMessage());
        }
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}

    
    // public function start(Request $request, ?AuthenticationException $authException = null): Response
    // {
    //     /*
    //      * If you would like this class to control what happens when an anonymous user accesses a
    //      * protected page (e.g. redirect to /login), uncomment this method and make this class
    //      * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
    //      *
    //      * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
    //      */
    // }
