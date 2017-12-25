<?php

namespace App\Model;

class OfferModel extends EntityModel {

    const TABLE_NAME = "offer";

    /**
     * Check if record alredy exists
     * 
     * @todo How to check if offer is the new one or just changed?
     * 
     * @param int $car_id
     * @param int $partner_offer_id
     * @param string $package
     * @return mixed
     */
    public function getUnique($car_id, $partner_offer_id, $package) {
        return $this->db->table(self::TABLE_NAME)
                        ->where('car_id = ?', $car_id)
                        ->where('partner_offer_id = ?', $partner_offer_id)
                        ->where('package = ?', $package)
                        ->fetch();
    }

    /**
     * If record not found, insert the new one
     * Returns boolean to check if new record has been stored
     * 
     * @param int $car_id
     * @param int $price
     * @param int $vat_rate
     * @param string $partner_offer_id
     * @param string $package
     * @return boolean
     */
    public function checkOrInsert($car_id, $price, $vat_rate, $partner_offer_id, $package) {
        if ($this->getUnique($car_id, $partner_offer_id, $package)) {
            return true;
        } else {
            $record = $this->insert(array(
                'car_id' => $car_id,
                'price' => $price,
                'vat_rate' => $vat_rate,
                'partner_offer_id' => $partner_offer_id,
                'package' => $package
            ));
            
            return false;
        }
    }

}
