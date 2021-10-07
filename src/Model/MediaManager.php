<?php

namespace App\Model;

class MediaManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'media';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $media
     * @return int
     */
    public function insert(array $media): int
    {
        // prepared request
        $sql = "INSERT INTO " . self::TABLE . " 
        (`file`, `title`, `featured`, `guestroom_id`) 
        VALUES 
        (:file, :title, :featured, :guestroom_id)";


        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':file', $media['file'], \PDO::PARAM_STR);
        $statement->bindValue(':title', $media['title'], \PDO::PARAM_STR);
        $statement->bindValue(':featured', $media['featured'], \PDO::PARAM_BOOL);
        $statement->bindValue(':guestroom_id', $media['guestroom_id'], \PDO::PARAM_INT);

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
     * @param array $media
     * @return bool
     */
    public function update(array $media): bool
    {

        // prepared request
        $sql = "UPDATE " . self::TABLE . " 
        SET 
        `file` = :file,
        `title` = :title,
        `featured` = :featured, 
        `guestroom_id` = :guestroom_id
        WHERE 
        id=:id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $media['id'], \PDO::PARAM_INT);
        $statement->bindValue(':file', $media['file'], \PDO::PARAM_STR);
        $statement->bindValue(':title', $media['title'], \PDO::PARAM_STR);
        $statement->bindValue(':featured', $media['featured'], \PDO::PARAM_BOOL);
        $statement->bindValue(':guestroom_id', $media['guestroom_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }


    /**
     * Get all row from database guestroom.
     *
     * @return array
     */
    public function selectAllGuestroom(): array
    {
        $sql = "SELECT g.id, g.title FROM `guestroom` g";
        return $this->pdo->query($sql)->fetchAll();
    }
}
