<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\{JsonResponse,Response};
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Medico;


class MedicosController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicos", methods={"POST"})
    */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequisicao);

        $medico = new Medico();
        $medico->crm = $dadosEmJson->crm;
        $medico->nome = $dadosEmJson->nome;

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

} 