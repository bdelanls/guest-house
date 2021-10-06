<?php

namespace App\Model;

class GuestroomManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'guestroom';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $guestroom
     * @return int
     */
    public function insert(array $guestroom): int
    {
        // prepared request
        $sql = "INSERT INTO " . self::TABLE . " 
        (`description`, `title`, `max_persons`, `num_bed`, `add_bed`, `wifi`, `tv`, `clim`, `area`, `price`, `promotion`, `disabled`, `pets`) 
        VALUES 
        (:description, :title, :max_persons, :num_bed, :add_bed, :wifi, :tv, :clim, :area, :price, :promotion, :disabled, :pets)";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':description', $guestroom['description'], \PDO::PARAM_STR);
        $statement->bindValue(':title', $guestroom['title'], \PDO::PARAM_STR);
        $statement->bindValue(':max_persons', $guestroom['max_persons'], \PDO::PARAM_INT);
        $statement->bindValue(':num_bed', $guestroom['num_bed'], \PDO::PARAM_INT);
        $statement->bindValue(':add_bed', $guestroom['add_bed'], \PDO::PARAM_INT);
        $statement->bindValue(':wifi', $guestroom['wifi'], \PDO::PARAM_BOOL);
        $statement->bindValue(':tv', $guestroom['tv'], \PDO::PARAM_BOOL);
        $statement->bindValue(':clim', $guestroom['clim'], \PDO::PARAM_BOOL);
        $statement->bindValue(':area', $guestroom['area'], \PDO::PARAM_BOOL);
        $statement->bindValue(':price', $guestroom['price'], \PDO::PARAM_INT);
        $statement->bindValue(':promotion', $guestroom['promotion'], \PDO::PARAM_INT);
        $statement->bindValue(':disabled', $guestroom['disabled'], \PDO::PARAM_BOOL);
        $statement->bindValue(':pets', $guestroom['pets'], \PDO::PARAM_BOOL);

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
     * @param array $guestroom
     * @return bool
     */
    public function update(array $guestroom): bool
    {

        // prepared request
        $sql= "UPDATE " . self::TABLE . " SET 
        `title` = :title,
        `description` = :description,
        `max_persons` = :max_persons,
        `num_bed` = :num_bed,
        `add_bed` = :add_bed,
        `wifi` = :wifi,
        `tv` = :tv,
        `clim` = :clim,
        `area` = :area,
        `price` = :price,
        `promotion` = :promotion,
        `disabled` = :disabled,
        `pets` = :pets
        WHERE 
        id=:id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $guestroom['id'], \PDO::PARAM_INT);
        $statement->bindValue(':title', $guestroom['title'], \PDO::PARAM_STR);
        $statement->bindValue(':description', $guestroom['description'], \PDO::PARAM_STR);
        $statement->bindValue(':max_persons', $guestroom['max_persons'], \PDO::PARAM_INT);
        $statement->bindValue(':num_bed', $guestroom['num_bed'], \PDO::PARAM_INT);
        $statement->bindValue(':add_bed', $guestroom['add_bed'], \PDO::PARAM_INT);
        $statement->bindValue(':wifi', $guestroom['wifi'], \PDO::PARAM_BOOL);
        $statement->bindValue(':tv', $guestroom['tv'], \PDO::PARAM_BOOL);
        $statement->bindValue(':clim', $guestroom['clim'], \PDO::PARAM_BOOL);
        $statement->bindValue(':area', $guestroom['area'], \PDO::PARAM_INT);
        $statement->bindValue(':price', $guestroom['price'], \PDO::PARAM_INT);
        $statement->bindValue(':promotion', $guestroom['promotion'], \PDO::PARAM_INT);
        $statement->bindValue(':disabled', $guestroom['disabled'], \PDO::PARAM_BOOL);
        $statement->bindValue(':pets', $guestroom['pets'], \PDO::PARAM_BOOL);

        return $statement->execute();
    }
}
