<?php

namespace App\Controller\AbstractTwouiterController;

use App\Service\ObjectUpdatingService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractTwouiterController extends AbstractController
{
    protected string $entityType;
    protected string $requestAddType;
    protected string $requestUpdateType;
    protected string $responseAllType;
    protected string $responseOneType;

    protected Serializer $serializer;
    protected ObjectUpdatingService $objectUpdatingService;

    function __construct() {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->objectUpdatingService = ObjectUpdatingService::getInstance();
    }

    public function index(EntityManagerInterface $entityManager): TwouiterControllerResponse
    {
        try {
            $entities = $entityManager->getRepository($this->entityType)->findAll();

            $responseEntities = array();
            foreach($entities as $entity) {
                $responseEntities[] = new $this->responseAllType();
                $this->objectUpdatingService->fillDataWithMatchingKeyByStaticProperties($responseEntities[count($responseEntities) - 1], $entity);
            }

            return new TwouiterControllerResponse(200, null, $responseEntities);
        } catch (\Exception $e) {
            return new TwouiterControllerResponse(
                $e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500,
                $e->getMessage(),
                null
            );
        }
    }

    public function detail(EntityManagerInterface $entityManager, $id): TwouiterControllerResponse
    {
        try {
            $entity = $entityManager->getRepository($this->entityType)->find($id);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);

            return new TwouiterControllerResponse(200, null, new $this->responseOneType($entity));
        } catch (\Exception $e) {
            return new TwouiterControllerResponse(
                $e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500,
                $e->getMessage(),
                null
            );        }
    }


    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): TwouiterControllerResponse
    {
        try {
            $bodyObject = json_decode($request->getContent());

            $requestEntity = new $this->requestAddType();
            $this->objectUpdatingService->fillDataWithMatchingKeyByDynamicProperties($requestEntity, $bodyObject);

            $errors = $validator->validate($requestEntity);
            if (count($errors) > 0)
                throw new \Exception('Invalid object content', 400);

            $entity = new $this->entityType();
            $this->objectUpdatingService->fillMissingDataWithOriginalEntity($requestEntity, $entity, $entityManager);
            return new TwouiterControllerResponse(200, null, $entity);
        } catch (\Exception $e) {
            return new TwouiterControllerResponse(
                $e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500,
                $e->getMessage(),
                null
            );
        }
    }

    public function update(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, $id): TwouiterControllerResponse
    {
        try {
            $entity = $entityManager->getRepository($this->entityType)->find($id);

            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);

            $bodyObject = json_decode($request->getContent());
            $requestEntity = new $this->requestUpdateType();
            $this->objectUpdatingService->fillDataWithMatchingKeyByStaticProperties($requestEntity, $entity);
            $this->objectUpdatingService->fillDataWithMatchingKeyByDynamicProperties($requestEntity, $bodyObject);

            $errors = $validator->validate($requestEntity);
            if (count($errors) > 0)
                throw new \Exception('Invalid object content', 400);

            $this->objectUpdatingService->fillMissingDataWithOriginalEntity($requestEntity, $entity);
            $entityManager->persist($entity);
            $entityManager->flush();

            return new TwouiterControllerResponse(200, null, $entity);
        } catch (\Exception $e) {
            return new TwouiterControllerResponse(
                $e->getCode() > 199 && $e->getCode() < 600 ? $e->getCode() : 500,
                $e->getMessage(),
                null
            );
        }
    }

    public function delete(EntityManagerInterface $entityManager, $id): TwouiterControllerResponse
    {
        try {
            $entity = $entityManager->getRepository($this->entityType)->find($id);
            if (!$entity)
                throw new Exception('No object found for id '.$id, 404);

            $entityManager->remove($entity);
            return new TwouiterControllerResponse(200,'Deleted object with id '.$id, $entity);
        } catch (\Exception $e) {
            return new TwouiterControllerResponse(
                $e->getCode() > 299 && $e->getCode() < 600 ? $e->getCode() : 500,
                $e->getMessage(),
                null
            );        }
    }
}