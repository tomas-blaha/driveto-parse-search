<?php

/**
 * Model used for verifying and processing XML feed
 */

namespace App\Model;

use App\Model\CarModel;
use App\Model\MakeModel;
use App\Model\ModelModel;
use App\Model\TrimModel;
use App\Model\EngineModel;
use App\Model\OfferModel;
use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ImportModel {

    private $log;
    private $offer;
    private $make;
    private $model;
    private $trim;
    private $engine;

    /**
     * Defined in config.neon
     * 
     * @var string
     */
    private $sourceSchemeDir;

    public function __construct($source_scheme_dir, CarModel $car, MakeModel $make, ModelModel $model, TrimModel $trim, EngineModel $engine, OfferModel $offer) {
        $this->car = $car;
        $this->offer = $offer;
        $this->make = $make;
        $this->model = $model;
        $this->trim = $trim;
        $this->engine = $engine;
        $this->sourceSchemeDir = $source_scheme_dir;

        $this->log = new Logger("Import");
        $this->log->pushHandler(new StreamHandler(__DIR__ . '/../../log/import.log'));
    }

    /**
     * "Hacky" method used for converting XML object to array
     * 
     * @param object $object
     * @return array
     * @throws Exception
     */
    public function toArray($object) {
        $json = json_encode($object);

        if (!$json) {
            throw new Exception("Error parsing XML object", 1);
        }

        $array = json_decode($json, true);

        /**
         * Input canot be decoed or is deeper then recursion limit
         */
        if ($json === null) {
            throw new Exception("Error parsing XML object", 2);
        }

        return $array;
    }

    public function loadXML($src) {
        /*
         *  Prevent throwing native XML errors -> make thme catchable
         */
        libxml_use_internal_errors(true);

        $this->log->notice("Trying to load XML feed");


        if (!file_exists($src)) {
            $this->log->error("XML feed was not found");
            throw new Exception("File not found", 1);
        }

        $xml_file_content = file_get_contents($src);

        if (!$xml_file_content) {
            $this->log->error("XML feed could not be loaded");
            throw new Exception("Couldn't load file", 2);
        }

        $xml_document = new \DOMDocument();

        $xml_loaded = $xml_document->loadXML($xml_file_content, LIBXML_NOBLANKS);

        if (!$xml_loaded) {
            $this->log->error("XML feed could not be loaded from string");
            throw new Exception("Coulnd't load XML from string", 3);
        }


        if (!$xml_document->schemaValidate($this->sourceSchemeDir)) {

            $this->log->error("XML feed is not valid according to used schema");
            throw new Exception("XML schema is not valid according to Driveto schema", 4);
        }

        $simplexml_content = simplexml_load_file($src);


        if (!$simplexml_content) {
            $this->log->error("Failed loading XML file");
            throw new Exception("Failed loading XML file", 5);
        }

        $array = $this->toArray($simplexml_content);

        return $array;
    }

    /**
     * Check if offer has specified all required parameters
     * 
     * @todo Should be car stored even when no offers are found?
     * 
     * @param array $offer
     * @return boolean
     */
    public function isOfferValid($offer) {
        if (!isset($offer['car'])) {
            return false;
        }

        if (!isset($offer['car']['carSpecification'])) {
            return false;
        }

        if (
                !isset($offer['car']['carSpecification']['make']) ||
                !isset($offer['car']['carSpecification']['model']) ||
                !isset($offer['car']['carSpecification']['trim']) ||
                !isset($offer['car']['carSpecification']['engineVolume'])
        ) {
            return false;
        }

        if (!isset($offer['listPrice'])) {
            return false;
        }

        if (
                !isset($offer['listPrice']['withVat']) &&
                !isset($offer['listPrice']['withouVat'])
        ) {
            return false;
        }

        return true;
    }

    /**
     * Import defined XML feed
     * 
     * @return int
     */
    public function importXML() {
        $this->log->notice("Trying to import XML file");
        $added_offers = 0;
        $xml = $this->loadXML(__DIR__ . '/../../www/source/feed.xml');
        $offers = $xml['carOffers'];

        foreach ($offers['carOffer'] as $offer) {
            if (!$this->isOfferValid($offer)) {
                /**
                 * If offer is not valid, skip and continue with next one
                 * Errors will be stored to log file for further fix
                 */
                $this->log->warning("Offer was invalid, skipping...");
                continue;
            }

            $offer_make = $offer['car']['carSpecification']['make'];
            $offer_model = $offer['car']['carSpecification']['model'];
            $offer_model_line = $offer['car']['carSpecification']['modelLine'];
            $offer_trim = $offer['car']['carSpecification']['trim'];
            $offer_price = $offer['listPrice']['withVat'];
            $offer_engine_volume = $offer['car']['carSpecification']['engineVolume'];
            $offer_engine_power = $offer['car']['carSpecification']['enginePowerKw'];
            $partner_car_offer_id = $offer['partnerCarOfferId'];

            $make = $this->make->getOrInsert($offer_make);
            $model = $this->model->getOrInsert($make->id, $offer_model, $offer_model_line);
            $trim = $this->trim->getOrInsert($make->id, $offer_trim);
            $engine = $this->engine->getOrInsert($make->id, $offer_engine_volume, $offer_engine_power);
            $car = $this->car->getOrInsert($make->id, $model->id, $trim->id, $engine->id, $offer_price);

            if (isset($offer['priceOffers'])) {
                foreach ($offer['priceOffers']['priceOffer'] as $price_offer) {
                    $price = $price_offer['price']['withoutVat'];
                    $vat_rate = $price_offer['price']['vatRate'];
                    $package = $price_offer['package'];

                    if (!$this->offer->checkOrInsert($car->id, $price, $vat_rate, $partner_car_offer_id, $package)) {
                        $added_offers++;
                    };
                }
            }
        }
        if ($added_offers) {
            $this->log->notice("XML import finished. $added_offers offers stored.");
        } else {
            $this->log->notice("XML import finished. No offers stored.");
        }

        return $added_offers;
    }

}
