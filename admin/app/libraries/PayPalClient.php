<?php

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
//// mmmobitech
/// Client Id AWFGQZgx3JFhn6FsiPbCkah_3AO4sEfOeIP8XhaOIpPRJVjili2-czFaBzbf1kB0-dj9XkiSLUXivDu-
/// client Secret EBoKqCwaD9fwTqPE3m9i-7105sUjgCeo3E8FwLVAOb86DGXvLgYE3wiEVgbJdZWCYOLyqEKwXDI5IALA

class PayPalClient
{
    /*
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
    */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function apiClient(){
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                PAYPAL_CLIENT_ID,
                PAYPAL_CLIENT_SECRET
            )
        );
        return $apiContext;
    }

    /*
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
    */
    public static function environment() {
        $clientId = PAYPAL_CLIENT_ID;
        $clientSecret = PAYPAL_CLIENT_SECRET;
        return new SandboxEnvironment($clientId, $clientSecret);
    }

    public function createOrder() {
        $client = self::client();
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => "test_ref_id1",
                "amount" => [
                    "value" => "100.00",
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                "cancel_url" => "https://example.com/cancel",
                "return_url" => "https://example.com/return"
            ]
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            echo '<pre>';
            print_r($response);
            exit;
        }catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
            exit;
        }
    }

    public function createPayment() {
        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Set redirect URLs
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(base_url('/cronjobs/paypal-processed'))
            ->setCancelUrl(base_url('/cronjobs/paypal-cancel'));

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(10);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Payment description");

        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        // Create payment with valid API context
        try {
            $payment->create(self::apiClient());
            // Get PayPal redirect URL and redirect the customer
            $approvalUrl = $payment->getApprovalLink();
            echo $approvalUrl;
            exit;
            // Redirect the customer to $approvalUrl

        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } 
        catch (Exception $ex) {
            die($ex);
        }
    }

    public function test_payout($email,$amount,$currency,$transaction_id){
        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject("You have a Payout!");
        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for your patronage!')
            ->setReceiver($email)
            ->setSenderItemId($transaction_id)
            ->setAmount(new \PayPal\Api\Currency('{
                        "value":"'.$amount.'",
                        "currency":"'.$currency.'"
                    }'));

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);

        $request = clone $payouts;
        try {
            $output = $payouts->create(array('sync_mode' => 'false'),self::apiClient());
        }
        catch (Exception $ex) {
            echo '<pre>';
            print_r($ex->getData());
            exit(1);
        }

        return $output;
    }
    
    public function payout($email,$amount,$currency,$transaction_id){
        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject("You have a Payout!");
        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for your patronage!')
            ->setReceiver($email)
            ->setSenderItemId($transaction_id)
            ->setAmount(new \PayPal\Api\Currency('{
                        "value":"'.$amount.'",
                        "currency":"'.$currency.'"
                    }'));

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);

        $request = clone $payouts;
        try {
            $output = $payouts->create(array('sync_mode' => 'false'),self::apiClient());
        }
        catch (Exception $ex) {
            echo '<pre>';
            print_r($ex->getData());
            exit(1);
        }

        return $output;
    }
    
    public function paymentAuth($amount, $currency, $transaction_id) {
        $apiContext = self::apiClient();
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("paypal");
    
        $amountObj = new \PayPal\Api\Amount();
        $amountObj->setCurrency($currency)->setTotal($amount);
    
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amountObj)
                    ->setDescription("Payout for Transaction ID: " . $transaction_id);
    
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(base_url("/paypal_payout_status"))
                     ->setCancelUrl(base_url("/paypal_payout_status"));
    
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("authorize")
                ->setPayer($payer)
                ->setTransactions(array($transaction))
                ->setRedirectUrls($redirectUrls);
    
        try {
            $payment->create($apiContext);
            $this->redirectToPayPal($payment);
        } catch (Exception $ex) {
            echo "An error occurred while creating the payment. Please try again.";
            exit(1);
        }
    }
    
    public function executePayment($email, $amount, $currency, $transaction_id) {
        $apiContext = self::apiClient();
        $paymentId = $_GET['paymentId'];
        $payerId = $_GET['PayerID'];
    
        try {
            $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
            $execution = new \PayPal\Api\PaymentExecution();
            $execution->setPayerId($payerId);
    
            $result = $payment->execute($execution, $apiContext);
    
            if ($result->getState() === "approved") {
                return $this->initiatePayout($email, $amount, $currency, $transaction_id);
            } else {
                echo "Payment not approved. Please try again.";
            }
        } catch (Exception $ex) {
            echo "An error occurred while executing the payment. Please try again.";
        }
    }
    
    private function initiatePayout($email, $amount, $currency, $transaction_id) {
        $apiContext = self::apiClient();
        $payouts = new \PayPal\Api\Payout();
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId(uniqid())
                          ->setEmailSubject("You have a Payout!");
    
        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')
                   ->setNote('Thanks for your patronage!')
                   ->setReceiver($email)
                   ->setSenderItemId($transaction_id)
                   ->setAmount(new \PayPal\Api\Currency('{
                       "value":"' . $amount . '",
                       "currency":"' . $currency . '"
                   }'));
    
        $payouts->setSenderBatchHeader($senderBatchHeader)
                ->addItem($senderItem);
    
        try {
            $output = $payouts->create(['sync_mode' => 'false'], $apiContext);
            echo "Payout successful!";
            return $output;
        } catch (Exception $ex) {
            echo "Payout initiation failed.";
        }
    }
    
    private function redirectToPayPal($payment) {
        echo json_encode(['url' => $payment->getApprovalLink()]);
        return;
    }
    

}
