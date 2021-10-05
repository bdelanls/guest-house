<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    
    /**
     * Get all row from database USER and ROLE.
     *
     * @return array
     */
    public function allWithRole(): array
    {
        $sql = "SELECT u.id, u.firstname, u.lastname, r.name role_name FROM `user` u LEFT JOIN `role` r ON u.role_id = r.id" ;
        return $this->pdo->query($sql)->fetchAll();
    }




    /**
     * @param array $user
     * @return int
     */
    public function insert(array $user): int
    {
        // prepared request
        $sql= "INSERT INTO " . self::TABLE . " 
        (`firstname`, `lastname`, `login`, `password`, `email`, `phone`, `role_id`) 
        VALUES 
        (:firstname, :lastname, :login, :password, :email, :phone, :role_id)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':login', $user['login'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':phone', $user['phone'], \PDO::PARAM_STR);
        $statement->bindValue(':role_id', $user['role_id'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $user
     * @return bool
     */
    public function update(array $user): bool
    {

        // prepared request

        if ($user['password'] != ""){
            $sql = "UPDATE " . self::TABLE . " SET 
            `firstname` = :firstname, 
            `lastname` = :lastname,
            `login` = :login,
            `password` = :password,
            `email` = :email,
            `phone` = :phone
            WHERE id=:id";
        }else{
            $sql = "UPDATE " . self::TABLE . " SET 
            `firstname` = :firstname, 
            `lastname` = :lastname,
            `login` = :login,
            `email` = :email,
            `phone` = :phone
            WHERE id=:id";
        }

        
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $user['id'], \PDO::PARAM_INT);
        $statement->bindValue(':firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':login', $user['login'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':phone', $user['phone'], \PDO::PARAM_STR);

        return $statement->execute();
    }


    /**
     *
     * @return array
     */
    public function selectLogin(array $user)
    {
        // prepared request
        $sql = "SELECT * FROM `user` u WHERE u.login='" . $user['login']."'";

        return $this->pdo->query($sql)->fetch();
    }


    /**
     *
     * @return array
     */
    public function loginExist(array $user): bool
    {
        // prepared request
        $sql = "SELECT * FROM `user` u WHERE u.login='" . $user['login']."'";

        return $this->pdo->query($sql)->fetch();
    }
    /**
     *
     * @return array
     */
    public function emailExist(array $user): bool
    {
        // prepared request
        $sql = "SELECT * FROM `user` u WHERE u.email='" . $user['email']."'";

        return $this->pdo->query($sql)->fetch();
    }

}
