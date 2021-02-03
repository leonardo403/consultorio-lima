<?php


namespace App\Controller;


use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\{JsonResponse,Response};
use Symfony\Component\Routing\Annotation\Route;


class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MedicoFactory
     */
    private $medicoFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory
    ){
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
    */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);
 
        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }
    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function buscarTodos() : Response
    {
        $repositorioDeMedicos = $this
        ->getDoctrine()
        ->getRepository(Medico::class);

        $medicoList = $repositorioDeMedicos->findAll();
        return new JsonResponse($medicoList); 
    }

    /**
    * @Route("/medicos/{id}", methods={"GET"})
    */
    public function buscarUm(int $id): Response
    {
        $medico =   $this->buscaMedico($id);      
        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;
        return new JsonResponse($medico,$codigoRetorno);

    }

    /**
    * @Route("/medicos/{id}", methods={"PUT"})
    */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $dadosEmJson = json_decode($corpoRequisicao);

        $medicoEnviado = new Medico();
        $medicoEnviado->crm = $dadosEmJson->crm;
        $medicoEnviado->nome = $dadosEmJson->nome;

        $medicoExistente = $this->buscaMedico($id);
        
        if (is_null($medicoExistente)) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->crm  = $medicoEnviado->crm;
        $medicoExistente->nome = $medicoEnviado->nome;

        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    /**
    * @Route("/medicos/{id}", methods={"DELETE"})
    */
    public function remove(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        
        //remover entidade do repositorio
        $this->entityManager->remove($medico);
        //remover no database
        $this->entityManager->flush();

        return new Response('Deletado', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function buscaMedico(int $id)
    {
        $repositorioDeMedicos = $this
        ->getDoctrine()
        ->getReference(Medico::class,$id);

        $medico = $repositorioDeMedicos;

        return $medico;
    }

} 