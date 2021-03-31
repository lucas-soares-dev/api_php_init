<?php
namespace Util;

use InvalidArgumentException;
use JsonException;

class JsonUtil
{    
    /**
     * tratarCorpoRequisicaoJson
     *
     * @return void
     */
    public static function tratarCorpoRequisicaoJson()
    {
        try {
            $postJson = json_decode(file_get_contents('php://input'), true); // RECUPERANDO OS DADOS DA REQUISIÇÃO
        } catch(JsonException $e) {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERR0_JSON_VAZIO);
        }

        if(is_array($postJson) && count($postJson) > 0) {
            return $postJson;
        }
    }
    
    /**
     * processarArrayParaRetornar
     *
     * @param  mixed $retorno
     * @return void
     */
    public function processarArrayParaRetornar($retorno) 
    {
        $dados = [];
        $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_ERRO; // SETANDO RETORNO DE ERRO

        // VERIFICAÇÕES PARA ALTERAR O TIPO DO RETORNO PARA SUCESSO CASO ELE SEJA EFETUADO COM SUCESSO
        if((is_array($retorno) && count($retorno) > 0) || strlen($retorno) > 10) {
            $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_SUCESSO;
            // ADICIONANDO DADOS DO RETORNO NA RESPOSTA
            $dados[ConstantesGenericasUtil::RESPOSTA] = $retorno;
        }

        $this->retornarJson($dados);
    }
    
    /**
     * retornarJson
     *
     * @param  mixed $json
     * @return void
     */
    private function retornarJson($json)
    {   

        // TRATANDO RESPOSTA JSON NO HEADER
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Access-Control-Allow-Method: GET, POST, PUT, DELETE');

        echo json_encode($json);
    }
}

