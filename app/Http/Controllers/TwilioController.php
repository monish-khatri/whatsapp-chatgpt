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
     * Display a listing of the resource.
     */
    public function handleIncomingMessage(Request $request): Response
    {
        $message = $request->input('Body');
        $from = $request->input('From');

        $responseText = ChatGPT::response($message);

        $twiml = self::generateTwimlResponse([
            'success' => true,
            'message' => $responseText,
            'toNumber' => $from,
        ]);

        return response($twiml)->header('Content-Type', 'text/xml');
    }

    /**
     * Send Message from Twilio
     */
    public function sendMessage(Request $request): void
    {
        try {
            // create a new Twilio client
            $twilio = new Client($this->twilioSid, $this->twilioToken);

            // send a WhatsApp message
            $message = $twilio->messages->create(
                $request->toNumber,
                [
                    "from" => "whatsapp:$this->twilioWpNumber",
                    "body" => $request->message,
                ]
            );
            Log::debug("Success:", $message->toArray());
        } catch (\Throwable $th) {
            Log::error("Error:" . $th->getMessage());
        }
    }

    /**
     * Function generate the twiml xml response
     */
    public static function generateTwimlResponse(array $array): bool|string
    {
        // create a new SimpleXMLElement object with a root element of "Response"
        $response = new SimpleXMLElement("<Response></Response>");

        // iterate over the associative array and create a new XML element for each key-value pair
        foreach ($array as $key => $value) {
            // create a new XML element with the key as the element name and the value as the element text
            $response->addChild($key, $value);
        }

        // convert the SimpleXMLElement object to a string
        return $response->asXML();
    }
}
