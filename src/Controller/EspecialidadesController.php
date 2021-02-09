<?php

namespace App\Controller;

use App\Entity\Especialidade;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\{JsonResponse,Response};
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
    * @var EspecialidadeRepository
    */
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository
    ){
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }


    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function nova(Request $request): Response
    {
        $dadosRequest   = $request->getContent();
        $dadosEmJson    = json_decode($dadosRequest);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
    * @Route("/especialidades", methods={"GET"})
    */
    public function buscarTodas(): Response
    {
        $especialidadeList = $this->repository->findAll();

        return new JsonResponse($especialidadeList);
    }

    /**
    * @Route("/especialidades/{id}", methods={"GET"})
    */
    public function buscarUma(int $id): Response
    {
        return new JsonResponse($this->repository->find($id));
    }
    
    /**
    * @Route("/especialidades/{id}", methods={"PUT"})
    */
    public function atualiza(int $id, Request $request): Response
    {

        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);
        
        $especialidade = $this->repository->find($id);
        $especialidade
            ->setDescricao($dadosEmJson->descricao);
        
        $this->entityManager->flush();
        
        return new JsonResponse($especialidade);
    }

    /**
    * @Route("/especialidades/{id}", methods={"DELETE"})
    */

    public function remove(int $id): Response
    {

    $especialidade = $this->repository->find($id);
    //remover entidade do repositorio
    $this->entityManager->remove($especialidade);
    //remove database
    $this->entityManager->flush();

    return new Response('Especialidade deletada', Response::HTTP_NO_CONTENT);

    }
}
