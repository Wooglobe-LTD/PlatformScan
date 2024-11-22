<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/15/2018
 * Time: 11:26 AM
 */

class Sheet
{

    

    private $client;
    private $token;
    // private $sheet_id = '1lCAEVInNuFlnxAQPjjq9W1H-zbUB42wKZiT5rG0wW8w';
    private $sheet_id = '1nrA1buP2hTnEHDlGPx_rPAzhdYlLZe71FwA__i5DpPc';

    // Constructor
    function __construct() {
        define('SCOPES', implode(' ', array(
                Google\Service\Gmail::MAIL_GOOGLE_COM,
                Google\Service\Drive::DRIVE,
                Google\Service\YouTube::YOUTUBE,
                Google\Service\YouTube::YOUTUBE_READONLY,
                Google\Service\YouTube::YOUTUBE_UPLOAD,
                Google\Service\YouTube::YOUTUBEPARTNER,
                Google\Service\YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,
                Google\Service\YouTube::YOUTUBE_FORCE_SSL,

                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,
            )
        ));
        $this->client = new Google_Client();
        $this->client->setApplicationName(APPLICATION_NAME_TUBE);
        $this->client->setScopes(SCOPES);
        $this->client->setAuthConfig(YOUTUBE_CLIENT_SECRET_PATH);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');

        $credentialsPath = YOUTUBE_CREDENTIALS_PATH;

        if(!file_exists($credentialsPath)){
            redirect('cb_gmail');
        }
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
        $this->token = $accessToken['access_token'];

        $this->client->setAccessToken($accessToken);
        if ($this->client->isAccessTokenExpired()) {
            $refreshTokenSaved = $this->client->getRefreshToken();
            $this->client->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            //$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($this->client->getAccessToken()));
        }
    }



    public function addVideosToSheet(){
        $service = new Google_Service_Sheets($this->client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
        $spreadsheetId = $this->sheet_id;
        $range = 'A2:E';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        $this->addRowToSpreadsheet(array('MJPrF7JGhPY','https://www.youtube.com/watch?v=MJPrF7JGhPY','Groom Get Picture of Him and Late Grandmother From Bridal || Best Viral Videos',True,'WGA589063'));
        echo '<pre>';
        print_r($values);
        exit;
    }

    function addRowToSpreadsheet($ary_values = array(),$sheet_id = 0) {


        $sheet_service = new Google_Service_Sheets($this->client);

        // Set the sheet ID
        $fileId = $this->sheet_id; // Copy & paste from a spreadsheet URL

        // Build the CellData array
        $values = array();
        foreach( $ary_values AS $d ) {
            $cellData = new Google_Service_Sheets_CellData();
            $value = new Google_Service_Sheets_ExtendedValue();
            $value->setStringValue($d);
            $cellData->setUserEnteredValue($value);
            $values[] = $cellData;
        }

        // Build the RowData
        $rowData = new Google_Service_Sheets_RowData();
        $rowData->setValues($values);

        // Prepare the request
        $append_request = new Google_Service_Sheets_AppendCellsRequest();
        $append_request->setSheetId($sheet_id);
        $append_request->setRows($rowData);
        $append_request->setFields('userEnteredValue');

        // Set the request
        $request = new Google_Service_Sheets_Request();
        $request->setAppendCells($append_request);

        // Add the request to the requests array
        $requests = array();
        $requests[] = $request;

        // Prepare the update
        $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
            'requests' => $requests
        ));

        try {
            // Execute the request
            $response = $sheet_service->spreadsheets->batchUpdate($fileId, $batchUpdateRequest);
            if( $response->valid() ) {
                // Success, the row has been added
                return true;
            }
        } catch (Exception $e) {
            // Something went wrong
            error_log($e->getMessage());
            print_r($e->getMessage());
        }

        return false;
    }

}
