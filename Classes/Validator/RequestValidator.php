<?php

namespace Validator;

use InvalidArgumentException;
use Repository\TokensAutorizadosRepository;
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;
use Service\UsuariosService;

class RequestValidator
{
    private $request;
    private array $dadosRequest = [];
    private object $TokensAutorizadosRepository;

    const GET = 'GET';
    const DELETE = 'DELETE';
    const USUARIOS = 'USUARIOS';
    
    /**
     * __construct
     *
     * @param  mixed $request
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
        $this->TokensAutorizadosRepository = new TokensAutorizadosRepository();
    }
    
    /**
     * METODO QUE PROCESSAR AS REQUISIÇÕES
     *
     * @return void
     */
    public function processarRequest()
    {
        // POR PADRÃO O RETORNO É UMA MENSAGEM DE ERRO DE ROTA
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA); // UTF8 PORQUE SERÁ RETORNADO JSON

        if (in_array($this->request['metodo'], ConstantesGenericasUtil::TIPO_REQUEST, true)) { // IN_ARRAY DIFERENCIA MAIUSCULO E MINUSCULO
            $retorno = $this->direcionarRequest();
        }

        return $retorno;
    }

    /**
     * MÉTODO RESPONSÁVEL POR DIRECIONAR A REQUISIÇÃO PARA O MÉTODO QUE VAI REALIZAR A FUNCIONALIDADE    
     *
     * @return void
     */
    private function direcionarRequest()
    {
        if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE) {
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }

        $this->TokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);

        // DIRECIONAR O MÉTODO E USAR O MÉTODO VARIÁVEL
        $metodo = $this->request['metodo'];

        return $this->$metodo(); // USANDO O MÉTODO VARIÁVEL DO PHP PARA CHAMAR A FUNCAO DO TIPO DA REQUEST(GET, POST, PUT OU DELETE)
    }

    /**
     * @return string
     */
    private function get()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

        // VALIDANDO SE A ROTA É EXISTENTE
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET)) {
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $retorno = $usuariosService->validarGet();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    /**
     * @return string
     */
    private function delete()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

        // VALIDANDO SE A ROTA É EXISTENTE
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE)) {
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $retorno = $usuariosService->validarDelete();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    /**
     * @return string
     */
    private function post()
    {
        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

        // VALIDANDO SE A ROTA É EXISTENTE
        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST)) {
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $usuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuariosService->validarPost();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }

    /**
     * @return string
     */
    private function put()
    {

        $retorno = utf8_encode(ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA);

        if (in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT)) {
            switch ($this->request['rota']) {
                case self::USUARIOS:
                    $usuariosService = new UsuariosService($this->request);
                    $usuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuariosService->validarPut();
                    break;

                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
            }
        }

        return $retorno;
    }
}
