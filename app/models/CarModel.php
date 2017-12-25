<?php

/**
 * Represents database "car" entity
 */

namespace App\Model;

class CarModel extends EntityModel {

    const TABLE_NAME = "car";

    /**
     * Search cars bssed on parameters
     * 
     * @param int $make_id
     * @param double $engine_volume
     * @return mixed
     */
    public function search($make_id, $engine_volume = false) {
        $results = $this->db->table(self::TABLE_NAME)
                ->where('make.id = ?', $make_id);

        if ($engine_volume) {
            $results->where('engine.volume', $engine_volume);
        }

        return $results->fetchAll();
    }

    /**
     * Check if record alredy exists
     * 
     * @param int $make
     * @param int $model
     * @param int $trim
     * @param int $engine
     * @return mixed
     */
    public function getUnique($make, $model, $trim, $engine) {
        return $this->db->table(self::TABLE_NAME)
                        ->where('make_id = ?', $make)
                        ->where('model_id = ?', $model)
                        ->where('trim_id = ?', $trim)
                        ->where('engine_id = ?', $engine)
                        ->fetch();
    }

    /**
     * If record not found, insert the new one
     * 
     * @param int $make_id
     * @param int $model_id
     * @param int $trim_id
     * @param int $engine_id
     * @param int $price
     * @return mixed
     */
    public function getOrInsert($make_id, $model_id, $trim_id, $engine_id, $price) {
        if (!$record = $this->getUnique($make_id, $model_id, $trim_id, $engine_id)) {
            $record = $this->insert(array(
                'make_id' => $make_id,
                'model_id' => $model_id,
                'trim_id' => $trim_id,
                'engine_id' => $engine_id,
                'list_price' => $price
            ));
        }

        return $record;
    }

}
