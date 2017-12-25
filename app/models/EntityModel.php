<?php

/**
 * Represents general database entity
 * Contains basic functions to work with data
 * Inherited function must have TABLE_NAME defined
 */

namespace App\Model;

use Nette\Database\Context;

abstract class EntityModel {

    protected $db;

    public function __construct(Context $db) {
        $this->db = $db;
    }

    public function getAll() {
        return $this->db->table(static::TABLE_NAME)->fetchAll();
    }

    public function insert($data) {
        return $this->db->table(static::TABLE_NAME)->insert($data);
    }

    public function getById($id) {
        return $this->db->table(static::TABLE_NAME)->where('id = ?', $id) > fetch();
    }

}
