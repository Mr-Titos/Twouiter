<?php

namespace App\Controller;

use App\Controller\AbstractTwouiterController\AbstractTwouiterController;
use App\DTO\RequestEntity\User\RequestAddUser;
use App\DTO\RequestEntity\User\RequestUpdateUser;
use App\DTO\ResponseEntity\User\ResponseAllUser;
use App\DTO\ResponseEntity\User\ResponseOneUser;
use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'User')]
class UserController extends AbstractTwouiterController
{
    function __construct() {
        parent::__construct();
        $this->entityType = User::class;
        $this->requestAddType = RequestAddUser::class;
        $this->requestUpdateType = RequestUpdateUser::class;
        $this->responseAllType = ResponseAllUser::class;
        $this->responseOneType = ResponseOneUser::class;
    }

    #[Route('/api/login', name: 'login_check', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        var_dump($user);
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'user' => $user->getName(),
        ]);
    }

    #[OA\Get(
        description: 'List of all user',
        summary: 'List of all user',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: ResponseAllUser::class))
                )
            ),
            new OA\Response(response: 400, description: 'code 400, Bad request =('),
            new OA\Response(response: 500, description: 'code 500, ooops !'),
        ]
    )]
    #[Route('/api/user', name: 'get_user_all', methods: ['GET'])]
    public function indexU(EntityManagerInterface $entityManager): JsonResponse
    {
        $controllerResponse = parent::index($entityManager);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        return $this->json($this->serializer->serialize($controllerResponse->getContent(), 'json'));
    }

    #[OA\Get(
        description: 'Detail of one user',
        summary: 'Detail of one user',
        parameters: [
            new OA\Parameter(name: 'id', description: 'user ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new Model(type: ResponseOneUser::class)
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    //#[Security(name: 'Bearer')]
    #[Route('/api/user/{id}', name: 'get_user_one', methods: ['GET'])]
    public function detailU(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $controllerResponse = parent::detail($entityManager, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        return $this->json($this->serializer->serialize($controllerResponse->getContent(), 'json'));
    }

    #[OA\Post(
        description: 'Create one user',
        summary: 'Create one user',
        requestBody: new OA\RequestBody(
            description: 'Request body',
            required: true,
            content: new Model(type: RequestAddUser::class)
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success'
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    #[Route('/api/user', name: 'create_user', methods: ['POST'])]
    public function createU(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $controllerResponse = parent::create($request, $entityManager, $validator);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }

        $entity = $controllerResponse->getContent();

        // guarantee every user at least has the USER role
        $roles[] = RoleEnum::USER;
        $entity->setRoles($roles);

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword($entity, $entity->getPassword());
        $entity->setPassword($hashedPassword);

        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->json(['content' => 'Saved new object with id '.$entity->getId(),])->setStatusCode($controllerResponse->getStatusCode());
    }

    #[OA\Put(
        description: 'Update one user',
        summary: 'Update one user',
        requestBody: new OA\RequestBody(
            description: 'Request body',
            required: true,
            content: new Model(type: RequestUpdateUser::class)
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success'
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    #[Route('/api/user/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateU(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, $id): JsonResponse
    {
        $controllerResponse = parent::update($request, $entityManager, $validator, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }

        $entity = $controllerResponse->getContent();
        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->json(['content' => 'Updated object with id '.$entity->getId(),])->setStatusCode($controllerResponse->getStatusCode());
    }

    #[OA\Delete(
        description: 'Delete one user',
        summary: 'Delete one user',
        parameters: [
            new OA\Parameter(name: 'id', description: 'user ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success'
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    #[Route('/api/user/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteU(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $controllerResponse = parent::delete($entityManager, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        // Remove the constraint of the friend relation
        $entity = $controllerResponse->getContent();
        foreach ($entity->getFriends() as $friend) {
            $entity->removeFriend($friend);
            $friend->removeFriend($entity);
            $entityManager->persist($friend);
        }
        $entityManager->flush();

        return $this->json(['content' => $controllerResponse->getMessage()]);
    }


    #[OA\Put(
        description: 'Add a friend',
        summary: 'Add a friend',
        parameters: [
            new OA\Parameter(name: 'id', description: 'user ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'idF', description: 'friend ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success'
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    #[Route('/api/user/{id}/addFriend/{idF}', name: 'addFriend_user', methods: ['PUT'])]
    public function addFriend(EntityManagerInterface $entityManager, $id, $idF): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository($this->entityType)->find($id);
            $entityF = $entityManager->getRepository($this->entityType)->find($idF);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);
            if (!$entityF)
                throw new Exception('No object found for id '.$idF, 404);

            // No need to verify if they are already friend as it's already done in the method addFriend :D
            $entity->addFriend($entityF);
            $entityF->addFriend($entity);

            $entityManager->persist($entity);
            $entityManager->persist($entityF);
            $entityManager->flush();

            return $this->json($this->serializer->serialize(new ResponseOneUser($entity), 'json'));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }


    #[OA\Put(
        description: 'Remove a friend',
        summary: 'Remove a friend',
        parameters: [
            new OA\Parameter(name: 'id', description: 'user ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'idF', description: 'friend ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success'
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    #[Route('/api/user/{id}/removeFriend/{idF}', name: 'removeFriend_user', methods: ['PUT'])]
    public function removeFriend(EntityManagerInterface $entityManager, $id, $idF): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository($this->entityType)->find($id);
            $entityF = $entityManager->getRepository($this->entityType)->find($idF);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);
            if (!$entityF)
                throw new Exception('No object found for id '.$idF, 404);

            // No need to verify if they are already friend as it's already done in the method removeFriend :D
            $entity->removeFriend($entityF);
            $entityF->removeFriend($entity);

            $entityManager->persist($entity);
            $entityManager->persist($entityF);
            $entityManager->flush();

            return $this->json($this->serializer->serialize(new ResponseOneUser($entity), 'json'));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }
}
