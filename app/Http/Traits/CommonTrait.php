<?php

/**
 * CommonTrait
 * php version 8.1
 *
 * @category  CommonTrait
 *
 * @author    Publicis Sapient <mail@company.com>
 * @copyright 2021 Publicis Sapient
 * @license   see LICENSE
 *
 * @link      https://publicissapient.com/
 */

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;
use Mail;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Models\User;
use GuzzleHttp\Client;
trait CommonTrait
{
    
    const URL = 'http://www.special.mm/';
    const userName = 'specialistsupplements2323@gmail.com';
    const passWord = '2AoKRA6Pgcig';
    /**
     * Adding logs for Accessworlpay log file
     *
     * @param $data     data
     * @param $filename file name
     * @return void
     */
    private function logData($data)
    {
        Log::channel('ssltd')
            ->info(json_encode($data).PHP_EOL);
    }

    /**
     * Get user id by username
     *
     * @param $name user name
     * @return void
     */
    protected function getUserIdbyName($name)
    {
        $result = User::where('name', $name)
            ->select('id')
            ->first();
        if ($result) {
            return $result->id;
        } else {
            return false;
        }
    }

    protected function loginAndRedirect(){

        $userName = 'specialistsupplements2323@gmail.com';
        $passWord = '2AoKRA6Pgcig';

        $url = self::URL.'testapi.php';
        $order_data = array();
        $request = (new Client())->request(
            'POST',
            $url,
            [
                'headers' => array(
                    'Authorization' => base64_encode($userName . ':' . $passWord),
                   ),
                'body' => json_encode($order_data)
            ]
        );
        $response = $request->getBody()->getContents();

        $pwString = $userName.':'. $passWord;
        
        
        $encodedUrl = self::URL.'index.php?route=wpdropship/order&det='.$this->encodePw($pwString);
        
        return $encodedUrl;

    }

    protected function importOrderAndRedirect($order_data = array()){

        $userName = self::userName;
        $passWord = self::passWord;

        $url = self::URL.'laravel_api.php';
        $request = (new Client())->request(
            'POST',
            $url,
            [
                'headers' => array(
                    'Authorization' => base64_encode($userName . ':' . $passWord),
                   ),
                'body' => json_encode($order_data)
            ]
        );

       
        $response = $request->getBody()->getContents();

        $this->logData($order_data);
        $this->logData($request);
        
        $pwString = $userName.':'. $passWord;
        

        $appendedUrl = '&det='.$this->encodePw($pwString);
        $redirect_to = self::URL.'index.php?route=wpdropship/importorder/ImportOrder'.$appendedUrl;
        
        return $redirect_to;

    }
    protected function encodePw($string){
        $privateKey = 'AA74CDCC2BBRT935136HH7B63C27';
        $secretKey = '5fgf5HJ5g27';
        $encryptMethod = "AES-256-CBC";
        $key = hash('sha256', $privateKey);
        $ival = substr(hash('sha256', $secretKey), 0, 16);
        $result = openssl_encrypt($string, $encryptMethod, $key, 0, $ival);
        $output = base64_encode($result);
        return $output;
    }
}