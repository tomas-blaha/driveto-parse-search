<?php

namespace App\Presenters;

use Nette;

class BasePresenter extends Nette\Application\UI\Presenter {

    protected $db;

    public function __construct(\Nette\Database\Context $db) {
        parent::__construct();
        $this->db = $db;
    }

}
