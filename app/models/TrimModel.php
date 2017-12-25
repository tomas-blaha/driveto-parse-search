<?php

namespace App\Model;

class TrimModel extends EntityModel {

    const TABLE_NAME = "trim";

    /**
     * Check if record alredy exists
     * 
     * @param string $name
     * @param int $make
     * @return mixed
     */
    public function getUnique($name, $make) {
        return $this->db->table(self::TABLE_NAME)
                        ->where('name = ?', $name)
                        ->where('make_id = ?', $make)
                        ->fetch();
    }

    /**
     * If record not found, insert the new one
     * 
     * @param int $make_id
     * @param string $trim
     * @return mixed
     */
    public function getOrInsert($make_id, $trim) {
        if (!$record = $this->getUnique($trim, $make_id)) {
            $record = $this->insert(array(
                'make_id' => $make_id,
                'name' => $trim
            ));
        }

        return $record;
    }

}
