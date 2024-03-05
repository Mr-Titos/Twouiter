<?php

namespace App\Controller;

use App\Entity\User;
use App\RequestEntity\RequestAddUser;
use App\ResponseEntity\ResponseAllUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $entities = $entityManager->getRepository(User::class)->findAll();

        /*if (!$object) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }*/

        $responseEntities = array();
        foreach($entities as $entity) {
            $responseEntity = new ResponseAllUser();
            $responseEntity -> setName($entity->getName());
            $responseEntity -> setMail($entity->getMail());
            $responseEntities[] = $responseEntity;
        }

        return $this->json($responseEntities);
    }

    #[Route('/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        try {
            $bodyObject = json_decode($request->getContent());

            $requestEntity = new RequestAddUser();
            $requestEntity->setName($bodyObject->name);
            $requestEntity->setLogin($bodyObject->login);
            $requestEntity->setPassword($bodyObject->password);
            $requestEntity->setMail($bodyObject->mail == null ? $bodyObject->login : $bodyObject->mail);

            $errors = $validator->validate($requestEntity);
            if (count($errors) > 0) {
                throw new \Exception('Invalid object content');
            }

            $entity = new User();
            $entity->extractData($requestEntity);

            // tell Doctrine you want to save the Product (no queries yet)
            $entityManager->persist($entity);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->json([
                'content' => 'Saved new product with id '.$entity->getId(),
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Invalid object content',
            ])->setStatusCode(400);
        }
    }

    private function verifyObjectContent($object): bool
    {
        return false;
    }
}
