<?php

namespace App\Controller;

use App\Entity\User;
use App\RequestEntity\RequestAddUser;
use App\RequestEntity\RequestUpdateUser;
use App\ResponseEntity\ResponseAllUser;
use App\ResponseEntity\ResponseOneUser;
use App\Service\ObjectUpdatingService;
use Doctrine\Inflector\Inflector;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    private Serializer $serializer;
    private ObjectUpdatingService $objectUpdatingService;
    function __construct() {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->objectUpdatingService = ObjectUpdatingService::getInstance();
    }

    #[Route('/user', name: 'get_user_all', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $entities = $entityManager->getRepository(User::class)->findAll();

        $responseEntities = array();
        foreach($entities as $entity)
            $responseEntities[] = new ResponseAllUser($entity);

        return $this->json($this->serializer->serialize($responseEntities, 'json'));
    }

    #[Route('/user/{id}', name: 'get_user_one', methods: ['GET'])]
    public function detail(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository(User::class)->find($id);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);

            return $this->json($this->serializer->serialize(new ResponseOneUser($entity), 'json'));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    #[Route('/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        try {
            $bodyObject = json_decode($request->getContent());

            $requestEntity = new RequestAddUser();
            $this->objectUpdatingService->fillDataWithMatchingKeyByStaticProperties($requestEntity, $bodyObject);

            $errors = $validator->validate($requestEntity);
            if (count($errors) > 0)
                throw new \Exception('Invalid object content', 400);

            $entity = new User();
            $this->objectUpdatingService->fillMissingDataWithOriginalEntity($requestEntity, $entity);
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->json(['content' => 'Saved new object with id '.$entity->getId(),]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    #[Route('/user/{id}', name: 'update_user', methods: ['PUT'])]
    public function updateUser(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, $id): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository(User::class)->find($id);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);

            $bodyObject = json_decode($request->getContent());
            $requestEntity = new RequestUpdateUser();
            $this->objectUpdatingService->fillDataWithMatchingKeyByStaticProperties($requestEntity, $entity);
            $this->objectUpdatingService->fillDataWithMatchingKeyByDynamicProperties($requestEntity, $bodyObject);

            $errors = $validator->validate($requestEntity);
            if (count($errors) > 0)
                throw new \Exception('Invalid object content', 400);

            $this->objectUpdatingService->fillMissingDataWithOriginalEntity($requestEntity, $entity);
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->json(['content' => 'Updated new object with id '.$entity->getId()]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    #[Route('/user/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository(User::class)->find($id);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);

            $entityManager->remove($entity);
            $entityManager->flush();

            return $this->json(['content' => 'Deleted object with id '.$id,]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    #[Route('/user/{id}/addFriend/{idF}', name: 'addFriend_user', methods: ['PUT'])]
    public function addFriend(EntityManagerInterface $entityManager, $id, $idF): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository(User::class)->find($id);
            $entityF = $entityManager->getRepository(User::class)->find($idF);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);
            if (!$entityF)
                throw new Exception('No object found for id '.$idF, 404);

            // No need to verify if they are already friend as it's already done in the method addFriend :D
            $entity->addFriend($entityF);
            $entityF->addFriend($entity);

            return $this->json($this->serializer->serialize(new ResponseOneUser($entity), 'json'));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }

    #[Route('/user/{id}/removeFriend/{idF}', name: 'removeFriend_user', methods: ['PUT'])]
    public function removeFriend(EntityManagerInterface $entityManager, $id, $idF): JsonResponse
    {
        try {
            $entity = $entityManager->getRepository(User::class)->find($id);
            $entityF = $entityManager->getRepository(User::class)->find($idF);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);
            if (!$entityF)
                throw new Exception('No object found for id '.$idF, 404);

            // No need to verify if they are already friend as it's already done in the method removeFriend :D
            $entity->removeFriend($entityF);
            $entityF->removeFriend($entity);

            return $this->json($this->serializer->serialize(new ResponseOneUser($entity), 'json'));
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage(),])->setStatusCode($e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500);
        }
    }
}
