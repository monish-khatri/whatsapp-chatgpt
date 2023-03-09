<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use \SimpleXMLElement;

class TwilioController extends Controller
{
    private $twilioSid;
    private $twilioToken;
    private $twilioWpNumber;

    public function __construct()
    {
        $this->twilioSid = config('chatgpt.twilio_sid');
        $this->twilioToken = config('chatgpt.twilio_token');
        $this->twilioWpNumber = config('chatgpt.twilio_wp_number');
    }

    /**
     * Generate ChatGPT response and send it to the twilio
     */
    public function handleIncomingMessage(Request $request): Response
    {
        // Fetch the message & from nubmer information
        $message = $request->input('Body');
        $from = $request->input('From');

        // Get the response text from openai api
        $responseText = ChatGPT::response($message);

        // Generate the Twiml Response
        $twiml = self::generateTwimlResponse([
            'success' => true,
            'message' => $responseText,
            'toNumber' => $from,
        ]);

        return response($twiml)->header('Content-Type', 'text/xml');
    }

    /**
     * Send Whatsapp Message from Twilio
     */
    public function sendMessage(Request $request): void
    {
        try {
            // Create a new Twilio client
            $twilio = new Client($this->twilioSid, $this->twilioToken);

            // Send a WhatsApp message
            $message = $twilio->messages->create(
                $request->toNumber,
                [
                    "from" => "whatsapp:$this->twilioWpNumber",
                    "body" => $request->message,
                ]
            );
            Log::channel('chatgpt')->debug("Success:{$request->toNumber}", $message->toArray());
        } catch (\Throwable $th) {
            Log::channel('chatgpt')->error("Error:{$request->toNumber}" . $th->getMessage());
        }
    }

    /**
     * Function will generate the twiml xml response
     */
    public static function generateTwimlResponse(array $array): bool|string
    {
        // Create a new SimpleXMLElement object with a root element of "Response"
        $response = new SimpleXMLElement("<Response></Response>");

        // Iterate over the associative array and create a new XML element for each key-value pair
        foreach ($array as $key => $value) {
            // Create a new XML element with the key as the element name and the value as the element text
            $response->addChild($key, $value);
        }

        // Convert the SimpleXMLElement object to a string
        return $response->asXML();
    }
}
