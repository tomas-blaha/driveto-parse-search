<?php

namespace App\Form;

use Nette\Application\UI\Form;
use App\Model\MakeModel;

class SearchForm {

    private $make;

    public function __construct(MakeModel $make) {
        $this->make = $make;
    }

    public function create() {
        $form = new Form;
        $makes = $this->make->getAllAsArray();

        $form->addSelect('make', 'Výrobce', $makes);

        /**
         * @todo add change to select dependent on make's engine list
         */
        $form->addText('engine_volume', 'Objem motoru');

        $form->addSubmit('search', 'Hledat');

        return $form;
    }

}
