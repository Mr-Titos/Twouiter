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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;


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

    #[OA\Tag(name: 'Twouit')]
    #[Route('/api/twouit', name: 'get_twouit_all', methods: ['GET'])]
    public function indexT(EntityManagerInterface $entityManager): JsonResponse
    {
        $controllerResponse = parent::index($entityManager);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        return $this->json($this->serializer->serialize($controllerResponse->getContent(), 'json'));
    }

    #[OA\Tag(name: 'Twouit')]
    #[Route('/api/twouit/{id}', name: 'get_twouit_one', methods: ['GET'])]
    public function detailT(EntityManagerInterface $entityManager, $id): JsonResponse
    {
        $controllerResponse = parent::detail($entityManager, $id);
        if ($controllerResponse->getStatusCode() !== 200) {
            return $this->json(['error' => $controllerResponse->getMessage(),])->setStatusCode($controllerResponse->getStatusCode());
        }
        return $this->json($this->serializer->serialize($controllerResponse->getContent(), 'json'));
    }

    #[OA\Tag(name: 'Twouit')]
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

    #[OA\Tag(name: 'Twouit')]
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

    #[OA\Tag(name: 'Twouit')]
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