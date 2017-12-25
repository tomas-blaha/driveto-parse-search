<?php

namespace App\Presenters;

use Nette;
use App\Model\CarModel;
use App\Form\SearchForm;

class SearchPresenter extends BasePresenter {

    private $car;
    private $searchForm;

    public function injectCar(CarModel $car) {
        $this->car = $car;
    }

    public function injectSearchForm(SearchForm $search_form) {
        $this->searchForm = $search_form;
    }

    public function createComponentSearchForm() {
        $form = $this->searchForm->create();
        $form->onSuccess[] = [$this, 'searchCars'];
        return $form;
    }

    public function searchCars($form) {
        $values = $form->getValues();
        $this->template->cars = $this->car->search($values['make'], $values['engine_volume']);
    }

}
