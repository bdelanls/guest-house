<?php

namespace App\Model;

class ContactManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'contact';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $contact
     * @return int
     */
    public function insert(array $contact): int
    {
        // prepared request
        $sql = "INSERT INTO " . self::TABLE . " 
        (`name`, `email`, `subject`, `message`) 
        VALUES 
        (:name, :email, :subject, :message)";


        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':name', $contact['name'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $contact['email'], \PDO::PARAM_STR);
        $statement->bindValue(':subject', $contact['subject'], \PDO::PARAM_STR);
        $statement->bindValue(':message', $contact['message'], \PDO::PARAM_STR);

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
     * @param array $contact
     * @return bool
     */
    public function update(array $contact): bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $contact['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $contact['title'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
