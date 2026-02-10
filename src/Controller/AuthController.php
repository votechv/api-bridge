<?php

namespace App\Controller;

use App\Dto\RegisterUserDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class AuthController extends AbstractController
{

    public function __construct(
       private UserService $userService,
        private ValidatorInterface $validator,
    ){}

    /**
     * @throws ExceptionInterface
     */
    #[Route('/register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterUserDto $dto): JsonResponse{

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json([
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->registerUser($dto);

        return $this->json([
            'message' => 'User successfully registered',
            'user' =>[
                'uuid' => $user->getUuid(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]
        ]);
    }
}
