<?php
include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
use Neodynamic\SDK\Web\ClientPrintJob;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\UserSelectedPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\ParallelPortPrinter;
use Neodynamic\SDK\Web\SerialPortPrinter;
use Neodynamic\SDK\Web\NetworkPrinter;


// Process request

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    // save printer settings & commands in the built-in WebClientPrint filesystem cache (*)
    // (*) you can change the cache system to a DB or other solution
    
    // get the session id
    $sid = $_POST['sid'];
    if (isset($sid)){
        // create cache file storing printer settings & commands
        $cacheFileName = (Utils::strEndsWith(WebClientPrint::$wcpCacheFolder, '/')?WebClientPrint::$wcpCacheFolder:WebClientPrint::$wcpCacheFolder.'/').$sid.'.ini';
        $data = '';
        foreach ($_POST as $key => $value){
            $data .= $key.'="'.base64_encode($value).'"'.chr(13).chr(10);
        }
        $handle = fopen($cacheFileName, 'w') or die('Cannot open file:  '.$cacheFileName);  
        fwrite($handle, $data);
        fclose($handle);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    // Generate ClientPrintJob? only if clientPrint param is in the query string
    $urlParts = parse_url($_SERVER['REQUEST_URI']);
    $rawQuery = $urlParts['query'];
    
    if (isset($rawQuery)){
        if($rawQuery[WebClientPrint::CLIENT_PRINT_JOB]){
            //we need the session id to look for the ini file containing printer settings & commands
            parse_str($rawQuery, $qs);
            $sid = $qs['sid'];
            if(isset($sid)){
                // try to get ini file from cache
                $cacheFileName = (Utils::strEndsWith(WebClientPrint::$wcpCacheFolder, '/')?WebClientPrint::$wcpCacheFolder:WebClientPrint::$wcpCacheFolder.'/').$sid.'.ini';
                if(file_exists($cacheFileName)){
                    $print_info = parse_ini_file($cacheFileName);
                    
                    //remove file from cache
                    unlink($cacheFileName);
                    
                    // create print job...
                    if(isset($print_info)){
                            
                        //get printer commands
                        $printerCommands = base64_decode($print_info['printerCommands']);

                        //get printer settings
                        $printerTypeId = base64_decode($print_info['pid']);
                        $clientPrinter = NULL;    
                        if ($printerTypeId == '0') //use default printer
                        {
                            $clientPrinter = new DefaultPrinter();
                        }
                        else if ($printerTypeId == '1') //show print dialog
                        {
                            $clientPrinter = new UserSelectedPrinter();
                        }
                        else if ($printerTypeId == '2') //use specified installed printer
                        {
                            $clientPrinter = new InstalledPrinter(base64_decode($print_info['receipt_printer']));
                        }
                        else if ($printerTypeId == '3') //use IP-Ethernet printer
                        {
                            $clientPrinter = new NetworkPrinter(base64_decode($print_info['netPrinterHost']), base64_decode($print_info['netPrinterIP']), base64_decode($print_info['netPrinterPort']));
                        }
                        else if ($printerTypeId == '4') //use Parallel Port printer
                        {
                            $clientPrinter = new ParallelPortPrinter(base64_decode($print_info['parallelPort']));
                        }
                        else if ($printerTypeId == '5') //use Serial Port printer
                        {
                            $clientPrinter = new SerialPortPrinter(base64_decode($print_info['serialPort']),
                                                                        base64_decode($print_info['serialPortBauds']),
                                                                        base64_decode($print_info['serialPortParity']),
                                                                        base64_decode($print_info['serialPortStopBits']),
                                                                        base64_decode($print_info['serialPortDataBits']),
                                                                        base64_decode($print_info['serialPortFlowControl']));
                        }

                        //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
                        $cpj = new ClientPrintJob();
                        $cpj->clientPrinter = $clientPrinter;
                        $cpj->printerCommands = $printerCommands;
                        $cpj->formatHexValues = true;
                        
                        //Send ClientPrintJob back to the client
                        ob_clean();
                        echo $cpj->sendToClient();
                        
                    }
                }
            }    
        }
    }
    
}

 