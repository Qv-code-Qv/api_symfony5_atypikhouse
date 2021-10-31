<?php

declare(strict_types=1);

namespace App\Service;

use App\Components\ImageStringToFileTransformer;
use App\Entity\User;
use App\Enum\SSOEnum;
use App\Form\RegistrationFormType;
use App\Form\Type\UserType;
use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Twig\Environment;
/**
 * Class UserService
 * @package App\Service
 */
final class UserService
{
    protected ?UserInterface $user;
   // protected $slackNotif;
    public function __construct(
        protected EntityManagerInterface $em,
        protected Environment $templating,
        protected RouterInterface $router,
        Security $security,
        protected FormFactoryInterface $formFactoryBuilder,
        protected UserPasswordEncoderInterface $passwordEncoder
        //NotificationSlackService $notificationSlackService
    ) {
        $this->user = $security->getUser();
    }


    /**
     * @param UserInterface|null $user
     */
    public function acceptVersion(?UserInterface $user = null):void
    {
        $userNow = ($user == null) ? $this->user : $user;
        $userNow->setAcceptVersion("luna_" . Kernel::VERSION);
        $this->em->flush();
        //$this->slackNotif->notificationToUser($userNow, "userConfigAccount");
    }


    /**
     * @param UserInterface $user
     * @return array|null
     */
    public function formUser(UserInterface $user):array {
        $form = $this->formFactoryBuilder->create(RegistrationFormType::class, $user, []);
        $form->handleRequest(Request::createFromGlobals());
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $form->getData();
                $users = $this->em->getRepository(User::class)->findBy(["email" => $user->getEmail()]);
                foreach ($users as $one) {
                    if ($one->getId() !== $user->getId()) {
                        return ["code" => 500, "luna_code" => 590, "message" => "Cet email n'est pas disponible.",
                            "form" => $form];
                    }
                }
                if (null !== $user->getPlainPassword()) {
                    $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);
                }
                $this->em->flush();
                return ["code" => 200, "luna_code" => 200, "message" => "Les informations ont été modifiées.",
                    "form" => $form];
            }
            else {
                return ["code" => 500, "luna_code" => 591, "message" => "Une erreur s'est produite.",
                    "form" => $form];
            }
        }
        return ["code" => 001, "form" => $form];
    }

    /**
     * @param UserInterface|null $user
     * @return bool
     */
    public function checkAcceptVersion(?UserInterface $user = null):bool
    {
        return (($user != null && "luna_" . Kernel::VERSION == $user->getAcceptVersion()) );
    }

    /**
     * @param UserInterface $user
     * @param string $avatar
     * @return array
     */
    public function avatarSave(UserInterface $user, string $avatar):array {
        $transformer = new ImageStringToFileTransformer();
        try {
            $file = $transformer->reverseTransform($avatar);
        } catch (Exception $e) {
            return ["code" => 500, "luna_code" => 600, "message" => "Cannot upload this image"];
        }

        $user->setAvatarFile($file);
        $this->em->flush();

        return ["code" => 200, "luna_code" => 200, "message" => "OK"];
    }

}
