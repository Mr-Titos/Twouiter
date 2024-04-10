<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Controller\AbstractTwouiterController\AbstractTwouiterController;
use App\DTO\RequestEntity\Twouit\RequestAddTwouit;
use App\DTO\RequestEntity\Twouit\RequestUpdateTwouit;
use App\DTO\ResponseEntity\Twouit\ResponseAllTwouit;
use App\DTO\ResponseEntity\Twouit\ResponseOneTwouit;
use App\Entity\Twouit;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Twouit')]
#[AllowDynamicProperties] class TwouitController extends AbstractTwouiterController
{
    function __construct() {
        parent::__construct();
        $this->entityType = Twouit::class;
        $this->requestAddType = RequestAddTwouit::class;
        $this->requestUpdateType = RequestUpdateTwouit::class;
        $this->responseAllType = ResponseAllTwouit::class;
        $this->responseOneType = ResponseOneTwouit::class;
    }

    #[OA\Get(
        description: 'List of all twouits',
        summary: 'List of all twouits',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: ResponseAllTwouit::class))
                )
            ),
            new OA\Response(response: 400, description: 'code 400, Bad request =('),
            new OA\Response(response: 500, description: 'code 500, ooops !'),
        ]
    )]
    #[Route('/api/twouit', name: 'get_twouit_all', methods: ['GET'])]
    public function indexT(EntityManagerInterface $entityManager): JsonResponse
    {
        $controllerResponse = parent::index($entityManager);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        return $this->json($this->serializer->serialize($controllerResponse->getContent(), 'json'));
    }

    #[OA\Get(
        description: 'Detail of one twouit',
        summary: 'Detail of one twouit',
        parameters: [
            new OA\Parameter(name: 'id', description: 'twouit ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new Model(type: ResponseOneTwouit::class)
            ),
            new OA\Response(response: 400, description: 'Bad request =('),
            new OA\Response(response: 500, description: 'Ooops !'),
        ]
    )]
    #[Route('/api/twouit/{id}', name: 'get_twouit_one', methods: ['GET'])]
    public function detailT(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $controllerResponse = parent::detail($entityManager, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        return $this->json($this->serializer->serialize($controllerResponse->getContent(), 'json'));
    }

    #[OA\Post(
        description: 'Create one twouit',
        summary: 'Create one twouit',
        requestBody: new OA\RequestBody(
            description: 'Request body',
            required: true,
            content: new Model(type: RequestAddTwouit::class)
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
    #[Route('/api/twouit', name: 'create_twouit', methods: ['POST'])]
    public function createT(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $controllerResponse = parent::create($request, $entityManager, $validator);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }

        $twouit = $controllerResponse->getContent();
        $twouit->setEntryDate(new \DateTime());
        $entityManager->persist($twouit);
        $entityManager->flush();

        return $this->json(['content' => 'Saved new object with id '.$twouit->getId(),])->setStatusCode($controllerResponse->getStatusCode());
    }

    #[OA\Put(
        description: 'Update one twouit',
        summary: 'Update one twouit',
        requestBody: new OA\RequestBody(
            description: 'Request body',
            required: true,
            content: new Model(type: RequestUpdateTwouit::class)
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
    #[Route('/api/twouit/{id}', name: 'update_twouit', methods: ['PUT'])]
    public function updateT(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, $id): JsonResponse
    {
        $controllerResponse = parent::update($request, $entityManager, $validator, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }

        $twouit = $controllerResponse->getContent();
        $entityManager->persist($twouit);
        $entityManager->flush();

        return $this->json(['content' => 'Updated object with id '.$twouit->getId(),])->setStatusCode($controllerResponse->getStatusCode());
    }

    #[OA\Delete(
        description: 'Delete one twouit',
        summary: 'Delete one twouit',
        parameters: [
            new OA\Parameter(name: 'id', description: 'twouit ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
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
    #[Route('/api/twouit/{id}', name: 'delete_twouit', methods: ['DELETE'])]
    public function deleteT(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $controllerResponse = parent::delete($entityManager, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }

        $entityManager->flush();
        return $this->json(['content' => $controllerResponse->getMessage()]);
    }
}