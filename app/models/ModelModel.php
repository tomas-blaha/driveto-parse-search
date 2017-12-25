<?php

namespace App\Model;

class ModelModel extends EntityModel {

    const TABLE_NAME = "model";

    
    /**
     * Check if record alredy exists
     * 
     * @Todo How to differentitate for example Fabia I an Fabia II? And facelifts?
     * 
     * @param string $name
     * @param string $line
     * @return mixed
     */
    public function getUnique($name, $line) {
        return $this->db->table(self::TABLE_NAME)
                        ->where('name = ?', $name)
                        ->where('line = ?', $line)
                        ->fetch();
    }

    /**
     * If record not found, insert the new one
     * 
     * @param int $make_id
     * @param string $name
     * @param string $line
     * @return mixed
     */
    public function getOrInsert($make_id, $name, $line) {
        if (!$record = $this->getUnique($name, $line)) {
            $record = $this->insert(array(
                'make_id' => $make_id,
                'name' => $name,
                'line' => $line
            ));
        }
        
        return $record;
    }

}
