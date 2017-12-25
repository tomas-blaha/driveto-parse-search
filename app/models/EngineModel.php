<?php

/**
 * Represents database "engine" entity
 */

namespace App\Model;

class EngineModel extends EntityModel {

    const TABLE_NAME = "engine";

    /**
     * Check if record alredy exists
     * 
     * @todo Add unique identifier to differentitate years/technologies etc. in engines for custom search/comparison
     * 
     * @param int $make
     * @param double $volume
     * @param int $power
     * @return mixed
     */
    public function getUnique($make, $volume, $power) {
        return $this->db->table(self::TABLE_NAME)
                        ->where('make_id = ?', $make)
                        ->where('volume = ?', $volume)
                        ->where('power = ?', $power)
                        ->fetch();
    }

    /**
     * If record not found, insert the new one
     * 
     * @param int $make_id
     * @param double $engine_volume
     * @param int $engine_power
     * @return mixed
     */
    public function getOrInsert($make_id, $engine_volume, $engine_power) {
        if (!$record = $this->getUnique($make_id, $engine_volume, $engine_power)) {
            $record = $this->insert(array(
                'make_id' => $make_id,
                'volume' => $engine_volume,
                'power' => $engine_power
            ));
        }
        
        return $record;
    }

}
