<?php

namespace App\Presenters;

use Nette;
use App\Model\ImportModel;
use Exception;

class ImportPresenter extends BasePresenter {

    private $import;

    public function injectImport(ImportModel $import) {
        $this->import = $import;
    }

    public function actionDefault() {
        try {
            $this->template->inserted = $this->import->importXML();
            $this->template->success = true;
        } catch (Exception $e) {
            $this->template->success = false;
            $this->template->error_message = $e->getMessage() . " (Code " . $e->getCode() . ")";
        }
    }

}
