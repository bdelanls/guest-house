<?php

namespace App\Model;

class RestorationManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'restoration';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $restoration
     * @return int
     */
    public function insert(array $restoration): int
    {
        // prepared request
        $sql = "INSERT INTO " . self::TABLE . " (`name`) VALUES (:name)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':name', $restoration['name'], \PDO::PARAM_STR);

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
     * @param array $restoration
     * @return bool
     */
    public function update(array $restoration): bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name WHERE id=:id");
        $statement->bindValue('id', $restoration['id'], \PDO::PARAM_INT);
        $statement->bindValue(':name', $restoration['name'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
