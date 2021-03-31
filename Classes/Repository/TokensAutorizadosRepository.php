<?php

namespace Repository;

use DB\MySQL;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;

class TokensAutorizadosRepository
{
    private object $MySQL;
    public const TABELA = "token_autorizados";
        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->MySQL = new MySQL(); // INSTANCIANDO OBJETO DO DB
    }
              
    /**
     * validarToken
     *
     * @param  mixed $token
     * @return void
     */
    public function validarToken($token)
    {
        $token = str_replace([' ', 'Bearer'], '', $token);

        if($token) {
            $consultaToken = 'SELECT id FROM '. self::TABELA .' WHERE token=:token  AND status=:status';
            $stmt = $this->getMySQL()->getDb()->prepare($consultaToken); // PREPARANDO A QUERY            
            $stmt->bindValue(':token', $token);
            $stmt->bindValue(':status', ConstantesGenericasUtil::SIM);
            $stmt->execute();

            if($stmt->rowCount() !== 1) {
                header('HTTP/1.1 401 Unauthorized');
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            }
        } else {
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_VAZIO);
        }
    }
        
    /**
     * getMySQL
     *
     * @return void
     */
    public function getMySQL()
    {
        return $this->MySQL;
    }
}