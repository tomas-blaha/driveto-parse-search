<?php

/**
 * Represents database "make" entity
 */

namespace App\Model;

class MakeModel extends EntityModel {

    const TABLE_NAME = "make";

    /**
     * Get all makes in an array - used for select input in form
     * 
     * @todo change to database helpers "toPairs" function
     * 
     * @return array
     * 
     */
    public function getAllAsArray() {
        $makes_selection = $this->getAll();
        $makes = array();

        if ($makes_selection) {
            foreach ($makes_selection as $make) {
                $makes[$make->id] = $make->name;
            }
        }

        return $makes;
    }

    /**
     * Check if record alredy exists
     * 
     * @param string $name
     * @return mixed
     */
    public function getUnique($name) {
        return $this->db->table(self::TABLE_NAME)
                        ->where('name = ?', $name)
                        ->fetch();
    }

    /**
     * If record not found, insert the new one
     * 
     * @param string $make
     * @return mixed
     */
    public function getOrInsert($make) {
        if (!$record = $this->getUnique($make)) {
            $record = $this->insert(array(
                'name' => $make
            ));
        }

        return $record;
    }

}
