<?php

class ConverterModel {
    public function wordsToNumber($words) {
        $wordArr = array(
            'zero' => 0, 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5,
            'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9, 'ten' => 10,
            'eleven' => 11, 'twelve' => 12, 'thirteen' => 13, 'fourteen' => 14, 'fifteen' => 15,
            'sixteen' => 16, 'seventeen' => 17, 'eighteen' => 18, 'nineteen' => 19,
            'twenty' => 20, 'thirty' => 30, 'forty' => 40, 'fifty' => 50,
            'sixty' => 60, 'seventy' => 70, 'eighty' => 80, 'ninety' => 90,
            'hundred' => 100, 'thousand' => 1000, 'million' => 1000000, 'billion' => 1000000000, 'trillion' => 1000000000000,
        );

        $words = explode(' and ', $words);
        $leftWords = explode(' ', trim($words[0]));

        $result = '';

        $leftTotal = 0;
        $leftCurrentNumber = 0;
        foreach ($leftWords as $word) {
            $number = $wordArr[$word] ?? null;
    
            if ($number === null) {
                continue;
            }
    
            if ($number >= 1000) {
                $leftTotal += $leftCurrentNumber * $number;
                $leftCurrentNumber = 0;
            } elseif ($number >= 100) {
                $leftCurrentNumber *= $number;
            } else {
                $leftCurrentNumber += $number;
            }
        }
    
        $leftTotal = $leftTotal + $leftCurrentNumber;

        // Handle decimals
        $rightTotal = 0;
        $rightCurrentNumber = 0;
        if (isset($words[1])) {
            $rightWords = explode(' ', trim($words[1]));

            foreach ($rightWords as $word) {
                $number = $wordArr[$word] ?? null;
        
                if ($number === null) {
                    continue;
                }
        
                if ($number >= 1000) {
                    $rightTotal += $rightCurrentNumber * $number;
                    $rightCurrentNumber = 0;
                } elseif ($number >= 100) {
                    $rightCurrentNumber *= $number;
                } else {
                    $rightCurrentNumber += $number;
                }
            }
        
            $rightTotal = $rightTotal + $rightCurrentNumber;
            $result = $leftTotal + ($rightTotal / 100);
            $formatted_result = number_format($result, 2, '.', ',');
            return $formatted_result;

        }

        return number_format($leftTotal, 0, '.', ',');
    }

    public function numberToWords($num) {
        $ones = [
            0 => "zero", 1 => "one", 2 => "two", 3 => "three", 4 => "four", 5 => "five",
            6 => "six", 7 => "seven", 8 => "eight", 9 => "nine", 10 => "ten",
            11 => "eleven", 12 => "twelve", 13 => "thirteen", 14 => "fourteen", 15 => "fifteen",
            16 => "sixteen", 17 => "seventeen", 18 => "eighteen", 19 => "nineteen"
        ];

        $tens = [
            2 => "twenty", 3 => "thirty", 4 => "forty", 5 => "fifty",
            6 => "sixty", 7 => "seventy", 8 => "eighty", 9 => "ninety"
        ];

        $hundreds = [
            "hundred", "thousand", "million", "billion", "trillion"
        ];

        if ($num == 0) {
            return "Zero";
        }

        $num = number_format($num, 2, ".", ","); 
        $num_arr = explode(".", $num); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
    
        $whole_arr = array_reverse(explode(",", $wholenum));
        krsort($whole_arr, 1);

        $result = "";

        foreach($whole_arr as $key => $i){
            while(substr($i, 0, 1) == "0") $i=substr($i, 1, 5);

            if($i < 20) {
                if(substr($i, 0, 1) != "0" && isset($ones[$i])) $result .= $ones[$i];
            } else if ($i < 100) { 
                if(substr($i, 0, 1) != "0") {
                    if ($i < 20) {
                        $result .= $ones[$i];
                    } else {
                        $result .= $tens[substr($i, 0, 1)];
                        if (substr($i, 1, 1) != "0") {
                            $result .= " " . $ones[substr($i, 1, 1)];
                        }
                    }
                }
            } else {
                $result .= $ones[substr($i, 0, 1)]." ".$hundreds[0];

                if(substr($i, 1, 1) != "0") {
                    if (substr($i, 1, 1) == "1") {
                        $result .= " ".$ones[substr($i, 1, 2)]; 
                    } else {
                        $result .= " ".$tens[substr($i, 1, 1)]; 
                        if(substr($i, 2, 1) != "0") $result .= " ".$ones[substr($i, 2, 1)]; 
                    }
                } else {
                    if(substr($i, 2, 1) != "0") $result .= " ".$ones[substr($i, 2, 1)];
                }
            }

            if($key > 0 && $i){ 
                $result .= " ".$hundreds[$key]." ";
            }

        }

        if($decnum > 0){
            $result .= " and ";
            if (substr($decnum, 0, 1) == '0') {
                $result .= $ones[substr($decnum, 1, 1)] . " cents";
            } else if($decnum < 20){
                $result .= $ones[$decnum] . " cents";
            } else if($decnum < 100){
                $result .= $tens[substr($decnum, 0, 1)];
                $result .= " ".$ones[substr($decnum, 1, 1)] . " cents";;
            }
        }

        $result = ucfirst($result);
        return $result;
    }

    public function convertToUSD($amount) {
        $api_key = '124873d14c90ba4628f9dec3';
        $api_url = "https://v6.exchangerate-api.com/v6/$api_key/latest/USD";
    
        $response_json = file_get_contents($api_url);
    
        if(false !== $response_json) {
    
            try {
                $response = json_decode($response_json);
    
                if('success' === $response->result) {
                    $usd_rate = $response->conversion_rates->PHP;
                    $converted_amount = $amount / $usd_rate;

                    $formatted_amount = number_format($converted_amount, 2, '.', ',');
                    return $formatted_amount;
                }
    
            }
            catch(Exception $e) {
                echo 'Error: ' . $e->getMessage();
                return false;
            }
        } else {
            echo 'Error: Unable to fetch exchange rates';
            return false;
        }
    }
    
}

?>