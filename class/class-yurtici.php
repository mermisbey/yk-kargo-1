<?php
    Class yurtici {

        protected static $_parameters, $_sclient;
        public $_debug = false;

        public function __construct(array $attributes = array()) {

            self::$_parameters = [
                'wsUserName'   => $attributes['wsUserName'],
                'wsPassword'   => $attributes['wsPassword'],
                'userLanguage' => $attributes['userLanguage'],
            ];
        }

        public function setUrl($url) {
            self::$_sclient = new \SoapClient($url);
        }



        public function createShipment($datagelen) {

            $data = array_merge(
                array("wsUserName" => self::$_parameters['wsUserName'],
                    "wsPassword" => self::$_parameters['wsPassword'],
                    "userLanguage" => self::$_parameters['userLanguage'],
                ),
                array("ShippingOrderVO" => $datagelen)
            );


            $this->setUrl('http://webservices.yurticikargo.com:8080/KOPSWebServices/ShippingOrderDispatcherServices?wsdl');
            return self::$_sclient->createShipment($data);
        }

        public function cancelShipment($cargoKeys) {

            $data = array_merge(
                array("wsUserName" => self::$_parameters['wsUserName'],
                    "wsPassword" => self::$_parameters['wsPassword'],
                    "userLanguage" => self::$_parameters['userLanguage'],
                    "cargoKeys" => $cargoKeys,
                )

            );


            $this->setUrl('http://webservices.yurticikargo.com:8080/KOPSWebServices/ShippingOrderDispatcherServices?wsdl');
            return self::$_sclient->cancelShipment($data);
        }

        public function queryShipment($keys,$keyType,$addHistoricalData=true,$onlyTracking=true) {

            $data = array_merge(
                array("wsUserName"        => self::$_parameters['wsUserName'],
                    "wsPassword"        => self::$_parameters['wsPassword'],
                    "wsLanguage"        => self::$_parameters['userLanguage'],
                    "keys"              => $keys,                                  // array olacak []
                    "keyType"           => $keyType,                               // 0 – Kargo Anahtarı 1 – Fatura Anahtarı
                    "addHistoricalData" => $addHistoricalData,                     // true / false Default : false
                    "onlyTracking"      => $onlyTracking,                          // true / false Default : false

                )

            );


            $this->setUrl('http://webservices.yurticikargo.com:8080/KOPSWebServices/ShippingOrderDispatcherServices?wsdl');
            return self::$_sclient->queryShipment($data);
        }


        public function queryShipmentDetail($keys,$keyType,$addHistoricalData=true,$onlyTracking=true,$jsonData=true) {

            $data = array_merge(
                array("wsUserName"        => self::$_parameters['wsUserName'],
                    "wsPassword"        => self::$_parameters['wsPassword'],
                    "wsLanguage"        => self::$_parameters['userLanguage'],
                    "keys"              => $keys,                                  // array olacak []
                    "keyType"           => $keyType,                               // 0 – Kargo Anahtarı 1 – Fatura Anahtarı
                    "addHistoricalData" => $addHistoricalData,                     // true / false Default : false
                    "onlyTracking"      => $onlyTracking,                          // true / false Default : false
                    "jsonData"          => $jsonData,                          // true / false Default : false

                )

            );


            $this->setUrl('http://webservices.yurticikargo.com:8080/KOPSWebServices/ShippingOrderDispatcherServices?wsdl');
            return self::$_sclient->queryShipmentDetail($data);
        }



        public function __destruct() {
            if ($this->_debug) {
                print_r(self::$_parameters);
            }
        }
    }

