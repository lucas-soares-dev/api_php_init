<?php

namespace Service;

use InvalidArgumentException;
use Repository\UsuariosRepository;
use Util\ConstantesGenericasUtil;

class UsuariosService
{
    private array $dados; // RECEBE OS DADOS DA MINHA REQUEST
    private array $dadosRequest = [];

    private object $UsuariosRepository;
    private const TABELA = "usuarios";
    private const RECURSOS_GET = ['listar'];
    private const RECURSOS_DELETE = ['deletar'];
    private const RECURSOS_POST = ["cadastrar"];
    private const RECURSOS_PUT = ['atualizar'];
    
    /**
     * __construct
     *
     * @param  mixed $dados
     * @return void
     */
    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->UsuariosRepository = new UsuariosRepository();
    }
    
    /**
     * validarGet
     *
     * @return void
     */
    public function validarGet()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_GET)) {
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso();
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequisicao($retorno);

        return $retorno;
    }
    
    /**
     * validarDelete
     *
     * @return void
     */
    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_DELETE)) {
            if ($this->dados['id'] > 0) {
                $retorno = $this->$recurso(); // CHAMANDO MÉTODO DE DELETAR
            } else {
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        return $retorno;
    }
    
    /**
     * validarPost
     *
     * @return void
     */
    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_POST)) {
            $retorno = $this->$recurso();
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequisicao($retorno);

        return $retorno;
    }
    
    /**
     * validarPut
     *
     * @return void
     */
    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];

        if (in_array($recurso, self::RECURSOS_PUT) && $this->dados['id'] > 0) {
            $retorno = $this->$recurso();
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
        }

        $this->validarRetornoRequisicao($retorno);

        return $retorno;
    }

    public function setDadosCorpoRequest($dadosRequest)
    {
        $this->dadosRequest = $dadosRequest;
    }
   
    /**
     * BUSCA O USUÁRIO PELO IDENTIFICADOR(ID)
     *
     * @return void
     */
    private function getOneByKey()
    {
        return $this->UsuariosRepository->getMysql()->getOneByKey(self::TABELA, $this->dados['id']);
    }

    /********
     * MÉTODO RESPONSÁVEL POR LISTAR USUÁRIOS
     */
    private function listar()
    {
        return $this->UsuariosRepository->getMysql()->getAll(self::TABELA); // BUSCANDO OS DADOS DA TABELA DE USUÁRIOS
    }
   
    /**
     * @return void
     */
    private function deletar()
    {
        return $this->UsuariosRepository->getMysql()->delete(self::TABELA, $this->dados['id']); // DELETANDO USUARIO
    }
    
    /**
     *
     * @return void
     */
    private function cadastrar()
    {
        // RECUPERAR OS DADOS. PODERIA SER DESSA FORMA:
        // $login = $this->dadosRequest['login'];
        // $cep = $this->dadosRequest['cep'];

        // MAS DA FORMA ABAIXO É UMA SIMPLIFICAÇÃO
        [$login, $cep] = [$this->dadosRequest['login'], $this->dadosRequest['cep']];

        if ($login && $cep) {

            if ($this->UsuariosRepository->insertUser($login, $cep) > 0) {
                $idInserido = $this->UsuariosRepository->getMySQL()->getDb()->lastInsertId();
                $this->UsuariosRepository->getMySQL()->getDb()->commit();

                return ['id_inserido' => $idInserido];
            }

            $this->UsuariosRepository->getMySQL()->getDb()->rollBack(); // NÃO ESTÁ FUNCIONANDO O ROLLBACK

            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_CEP_OBRIGATORIO);
    }
    
    /**
     * @return void
     */
    private function atualizar()
    {
        // ENVIAR OS DADOS PARA O UPDATEUSER
        if ($this->UsuariosRepository->updateUser($this->dados['id'], $this->dadosRequest) === 1) {
            // REALIZAR O COMMIT NO BD
            $this->UsuariosRepository->getMySQL()->getDb()->commit();

            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);
    }

    /**
     * @param  mixed $retorno
     * @return void
     */
    private function validarRetornoRequisicao($retorno)
    {
        if($retorno == null) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
    }
}
