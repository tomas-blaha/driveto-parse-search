<?php

require('Base.php');

class ImportTest extends Base {

    protected $import;

    public function setUp() {
        parent::setUp();

        $this->import = $this->container->getService('import');
    }

    public function testLoadXML() {
        $import = $this->container->getService('import');

        // Valid XML file
        Tester\Assert::type('array', $import->loadXML(__DIR__ . '/feed/validXmlFile.xml'));

        // Test XML feed does not exist
        \Tester\Assert::exception(function() use ($import) {       # Očekáváme výjimku
            $import->loadXML('nonExistingFile.xml');
        }, Exception::class, null, 1);

        /**
         * @todo Test on UNIX with CHMOD. Test tests if file is readable
         */
        /*
          \Tester\Assert::exception(function() use ($import) {       # Očekáváme výjimku
          $import->loadXML('invalidChmodXmlFile.xml');
          }, Exception::class, null, 2);
         */

        // Test XML feed in not XML file
        \Tester\Assert::exception(function() use ($import) {       # Očekáváme výjimku
            $import->loadXML(__DIR__ . '/feed/invalidXmlFile.xml');
        }, Exception::class, null, 3);

        // Test XML feed does not match schema
        \Tester\Assert::exception(function() use ($import) {       # Očekáváme výjimku
            $import->loadXML(__DIR__ . '/feed/invalidXmlSchema.xml');
        }, Exception::class, null, 4);
    }

    public function testToArray() {
        $import = $this->container->getService('import');

        $object = new stdClass();
        $object->a = "text";
        $object->b = new stdClass();
        $object->b->c = "another text";

        $array = array();
        $array['a'] = "text";
        $array['b'] = array();
        $array['b']['c'] = "another text";

        \Tester\Assert::same($array, $import->toArray($object));
    }

    /**
     * Matches test array all conditions
     */
    public function testIsOfferValid() {

        $import = $this->container->getService('import');

        $valid_offer = array(
            'car' => array(
                'carSpecification' => array(
                    'make' => "Škoda",
                    'model' => "Fabia",
                    'trim' => "Style",
                    'engineVolume' => 1.2
                )
            ),
            'listPrice' => array(
                'withVat' => 25000,
                'withoutVat' => 200000
            )
        );

        $invalid_offer = $valid_offer;
        unset($invalid_offer['car']['carSpecification']['make']);

        Tester\Assert::true($import->isOfferValid($valid_offer));
        Tester\Assert::false($import->isOfferValid($invalid_offer));
    }

    public function testImportXml() {
        /*
          $this->clearDatabase();
          $this->insertEmptyData();


          Tester\Assert::same(1, $this->import->importXML());

          Tester\Assert::same(0, $this->import->importXML());
         */
    }

}

(new ImportTest)->run();
