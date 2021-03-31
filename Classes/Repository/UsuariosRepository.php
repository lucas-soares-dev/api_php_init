<?php

namespace Repository;

use DB\MySQL;

class UsuariosRepository
{
    private object $MySQL;
    public const TABELA = "usuarios";
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->MySQL = new MySQL(); // INSTANCIANDO OBJETO DO DB
    }

    public function insertUser($login, $cep) {
        if(strlen($login && $cep)) {
            $insert = 'INSERT INTO ' . self::TABELA . ' (login, cep) VALUES (:login, :cep)';
            $this->MySQL->getDb()->beginTransaction();
            $stmt = $this->MySQL->getDb()->prepare($insert);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':cep', $cep);
            $stmt->execute();

            return $stmt->rowCount();
        }
    }
    
    /**
     * updateUser
     *
     * @param  mixed $id
     * @param  mixed $dados
     * @return void
     */
    public function updateUser($id, $dados)
    {

        $update = 'UPDATE ' . self::TABELA . ' SET login = :login, cep = :cep WHERE id = :id';
        $this->MySQL->getDb()->beginTransaction();
        $stmt = $this->MySQL->getDb()->prepare($update);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':login', $dados['login']);
        $stmt->bindParam(':cep', $dados['cep']);
        $stmt->execute();

        return $stmt->rowCount();
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