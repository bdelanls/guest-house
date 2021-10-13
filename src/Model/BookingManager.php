<?php

namespace App\Model;

class BookingManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'booking';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $booking
     * @return int
     */
    public function insert(array $booking): int
    {
        // prepared request
        $sql = "INSERT INTO " . self::TABLE . " 
        (`user_id`, `guestroom_id`, `arrival`, `departure`, `num_of_persons`, `taxi`, `restoration_id`) 
        VALUES 
        (:user_id, :guestroom_id, :arrival, :departure, :num_of_persons, :taxi, :restoration_id)";


        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':user_id', $booking['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':guestroom_id', $booking['guestroom_id'], \PDO::PARAM_INT);
        $statement->bindValue(':arrival', $booking['arrival'], \PDO::PARAM_STR);
        $statement->bindValue(':departure', $booking['departure'], \PDO::PARAM_STR);
        $statement->bindValue(':num_of_persons', $booking['num_of_persons'], \PDO::PARAM_INT);
        $statement->bindValue(':taxi', $booking['taxi'], \PDO::PARAM_BOOL);
        $statement->bindValue(':restoration_id', $booking['restoration_id'], \PDO::PARAM_INT);

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
     * @param array $booking
     * @return bool
     */
    public function update(array $booking): bool
    {

        // prepared request
        $sql = "UPDATE " . self::TABLE . " SET 
        `disabled` = :disabled,
        `user_id` = :user_id,
        `guestroom_id` = :guestroom_id,
        `arrival` = :arrival,
        `departure` = :departure,
        `num_of_persons` = :num_of_persons,
        `taxi` = :taxi,
        `restoration_id` = :restoration_id
        WHERE 
        id=:id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $booking['id'], \PDO::PARAM_BOOL);
        $statement->bindValue(':disabled', $booking['disabled'], \PDO::PARAM_BOOL);
        $statement->bindValue(':user_id', $booking['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':guestroom_id', $booking['guestroom_id'], \PDO::PARAM_INT);
        $statement->bindValue(':arrival', $booking['arrival'], \PDO::PARAM_STR);
        $statement->bindValue(':departure', $booking['departure'], \PDO::PARAM_STR);
        $statement->bindValue(':num_of_persons', $booking['num_of_persons'], \PDO::PARAM_INT);
        $statement->bindValue(':taxi', $booking['taxi'], \PDO::PARAM_BOOL);
        $statement->bindValue(':restoration_id', $booking['restoration_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }



    public function selectAllCross(int $id): array
    {
        return $this->pdo->query('SELECT arrival, departure FROM booking WHERE guestroom_id=' . $id )->fetchAll();

    }

    
    /**
     * Method selectAlloneUser
     *
     * @param int $id [explicite description]
     *
     * @return array
     */
    public function selectAlloneUser(int $id): array
    {
        $sql = "SELECT * FROM booking WHERE user_id = " . $id;
        return $this->pdo->query($sql)->fetchAll();
    }
}
