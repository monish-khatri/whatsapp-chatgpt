<?php

namespace App\Http\Controllers;

use OpenAI;

class ChatGPT extends Controller
{
    /**
     * Fetch the response from chatgpt server
     */
    public static function response($message): string
    {
        try {
            $client = OpenAI::client(config('chatgpt.openai_secret_key'));
            $response = $client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $message,
                'max_tokens' => 600,
            ]);
            return $response->choices[0]->text;
        } catch (\Throwable $th) {
            return 'Sorry, something went wrong!';
        }
    }
}
