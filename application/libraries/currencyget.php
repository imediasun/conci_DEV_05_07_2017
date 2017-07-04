<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Currencyget {

        protected static $_iso_currency = '^(AED|AFN|ALL|AMD|ANG|AOA|ARS|AUD|AWG|AZN|BAM|BBD|BDT|BGN|BHD|BIF|BMD|BND|BOB|BOV|BRL|BSD|BTN|BWP|BYR|BZD|CAD|CDF|CHE|CHF|CHW|CLF|CLP|CNY|COP|COU|CRC|CUC|CUP|CVE|CZK|DJF|DKK|DOP|DZD|EGP|ERN|ETB|EUR|FJD|FKP|GBP|GEL|GHS|GIP|GMD|GNF|GTQ|GYD|HKD|HNL|HRK|HTG|HUF|IDR|ILS|INR|IQD|IRR|ISK|JMD|JOD|JPY|KES|KGS|KHR|KMF|KPW|KRW|KWD|KYD|KZT|LAK|LBP|LKR|LRD|LSL|LTL|LVL|LYD|MAD|MDL|MGA|MKD|MMK|MNT|MOP|MRO|MUR|MVR|MWK|MXN|MXV|MYR|MZN|NAD|NGN|NIO|NOK|NPR|NZD|OMR|PAB|PEN|PGK|PHP|PKR|PLN|PYG|QAR|RON|RSD|RUB|RWF|SAR|SBD|SCR|SDG|SEK|SGD|SHP|SLL|SOS|SRD|SSP|STD|SVC|SYP|SZL|THB|TJS|TMT|TND|TOP|TRY|TTD|TWD|TZS|UAH|UGX|USD|USN|USS|UYI|UYU|UZS|VEF|VND|VUV|WST|XAF|XAG|XAU|XBA|XBB|XBC|XBD|XCD|XDR|XFU|XOF|XPD|XPF|XPT|XSU|XTS|XUA|XXX|YER|ZAR|ZMW|ZWL)$';
        
        private static function google($from, $to){
            $data = @file_get_contents('http://www.google.com/finance/converter?a=1&from='.$from.'&to='.$to);
            preg_match("/<span class=bld>(.*)<\/span>/",$data, $conversion); 
            return isset($conversion[1]) ? preg_replace("/[^0-9.]/", "", $conversion[1]) : false;
        }
        
        private static function webservicex($from, $to){
            $url = 'http://www.webservicex.net/CurrencyConvertor.asmx/ConversionRate?FromCurrency='.$from.'&ToCurrency='.$to;
            $url = 'http://www.x-rates.com/calculator/?from='.$from.'&to='.$to.'&amount=1';
            $conversion = @simpleXML_load_file($url,"SimpleXMLElement",LIBXML_NOCDATA);
            return ($conversion ===  false) ? false : $conversion[0];
        }
        
        private static function yahoo($from, $to){
           $conversion = @file_get_contents('http://quote.yahoo.com/d/quotes.csv?s='.$from.$to.'=X&f;=l1&e;=.csv');
    	   return ($conversion === false) ? false : $conversion;
        }
        private static function rateexchange($from, $to){
           $conversion = @file_get_contents('http://rate-exchange.appspot.com/currency?from='.$from.'&to='.$to.'');
		   $conversion=json_decode($conversion);
		   if(isset($conversion->rate)){
				return ($conversion->rate === false) ? false : $conversion->rate;
		   }else{
				return false;
		   }
        }
        
        public function currency_conversion($value=1, $from, $to){

            //Validates ISO currency codes
            if (!preg_match('/'.self::$_iso_currency.'/i', strtoupper(trim($from)))) return 'Currency "FROM", is not a valid ISO Code.';
            if (!preg_match('/'.self::$_iso_currency.'/i', strtoupper(trim($to)))) return 'Currency "TO", is not a valid ISO Code.';

            //Runs the Yahoo exchange by default
			$exchange = self::rateexchange( $from, $to );

            if ( !$exchange ){
				//Runs the Yahoo exchange by default
				$exchange = self::yahoo( $from, $to );

				if ( !$exchange ){
					//If there is an error in Yahoo,Google runs
					$exchange = self::google( $from, $to );
					if ( !$exchange ){
						//If there is an error in Yahoo, Webservicex runs
						$exchange = self::webservicex( $from, $to );
					}
				}
			}

            if ( !$exchange ){
                return 'There has been a mistake, the servers do not respond.';
            }else{
                //Return the conversion multiplied by the value
                return ($exchange*$value);
            }
        }
    }


/* End of file currency.php */
/* Location: ./application/libraries/currency.php */