<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory
{
    /**
    * @var EspecialidadeRepository
    */
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarMedico(string $json): Medico
    {
        $dadosEmJson = json_decode($json);
        $especialidadeId = $dadosEmJson->especialidadeId;
        $especialidade   = $this->especialidadeRepository->find($especialidadeId);

        $medico = new Medico();
        $medico
            ->setCrm($dadosEmJson->crm)
            ->setNome($dadosEmJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}