<?php

declare(strict_types=1);

namespace Elewant\UserBundle\Form;

use Elewant\UserBundle\Entity\User;
use HWI\Bundle\OAuthBundle\Form\RegistrationFormHandlerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RegistrationFormHandler implements RegistrationFormHandlerInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function process(Request $request, Form $form, UserResponseInterface $userInformation): bool
    {
        try {
            $user = $this->userProvider->loadUserByUsername($userInformation->getNickname());
        } catch (UsernameNotFoundException $exception) {
            $user = new User(
                $userInformation->getNickname(),
                (string) $userInformation->getRealName(),
                $userInformation->getResponse()['location'] ?? ''
            );
        }

        $form->setData($user);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            return $form->isValid();
        }

        return false;
    }
}
