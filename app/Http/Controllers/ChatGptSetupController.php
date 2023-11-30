<?php

namespace App\Http\Controllers;

use App\ChatGptSetup;
use App\ChatGptSetupOption;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzClient;

class ChatGptSetupController extends Controller
{

    public function index(Request $request) {
        return ChatGptSetup::paginate(25);
    }
    public function addPrompt(Request $request) {
        $inputs = $request->except('values');
        $chat_gpt_setup = ChatGptSetup::create($inputs);

        if(count($request->input('values')) > 0) {
            $values = [];
            foreach($request->input('values') as $val) {
                $values[] = [
                    'name' => empty(explode('-', $val)[0]) ? null:explode('-', $val)[0],
                    'value' => empty(explode('-', $val)[1]) ? null:explode('-', $val)[1],
                ];
            }

            $results = $this->groupKeyArray($values);

            foreach( $results as $key => $result ){
                ChatGptSetupOption::create([
                    'chat_gpt_setup_id' => $chat_gpt_setup->id,
                    'name' => $key,
                    'value' => json_encode($result),
                ]);
            }

        }

        return response()->json(['success' => true], 200);
    }

    public function generateGpt(Request $request) {
        $prompt = $request->input('prompt');
        $values = $request->input('values');

        // Decode JSON string to get values as an associative array
        $data = json_decode($values, true);

        // Find all placeholders in the prompt using a regular expression
        preg_match_all("/\[(.*?)\]/", $prompt, $matches);

        // Replace each placeholder with the corresponding value
        foreach ($matches[1] as $placeholder) {
            if (isset($data[$placeholder])) {
                $prompt = str_replace("[$placeholder]", $data[$placeholder], $prompt);
            }
        }

        $messages = [];
        $messages[] = ['role' => 'system', 'content' => 'You are an experienced writer specializing in articles.'];
        $messages[] = ['role' => 'user', 'content' => $prompt];

        try {
            $client = new GuzzClient();

            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer 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',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo-16k',
                    'messages' => $messages,
                    'temperature' => 1,
                    'max_tokens' => 8000,
                    'top_p' => 1,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0
                ],
            ]);

            $completion = json_decode($response->getBody(), true);

            $content = $completion['choices'][0]['message']['content'];

            $htmlContent = nl2br($content);

            return response()->json(['success' => true, 'data' => $htmlContent], 200);

        } catch (\Exception $e) {
            // An exception occurred during the API request
            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, "maximum context length is") !== false) {
                $startIndex = strpos($errorMessage, "This model's maximum context length is");
                $endIndex = strpos($errorMessage, 'tokens') + 6;
                $extractedMessage = substr($errorMessage, $startIndex, $endIndex - $startIndex);

                // Extract the requested token count
                $requestedTokensStartIndex = strpos($errorMessage, 'requested') + 10;
                $requestedTokensEndIndex = strpos($errorMessage, 'tokens', $requestedTokensStartIndex);
                $requestedTokens = substr($errorMessage, $requestedTokensStartIndex, $requestedTokensEndIndex - $requestedTokensStartIndex);

                $extractedMessage .= ' (requested ' . $requestedTokens . ' tokens). Request prompt is too long (Prompt + Content English/Content Translated).';

                // Return an error response with the extracted message
                return response()->json(['error' => $extractedMessage], 500);
            } else {
                // Return a generic error response
                return response()->json(['error' => 'An error occurred while generating AI content.'], 500);
            }
        }

    }

    private function groupKeyArray($values) {
        return array_reduce($values, function($carry, $item) {
            $name = $item['name'];
            $carry[$name][] = $item['value']; // Only store the 'value' key
            return $carry;
        }, []);
    }

    public function updatePrompt(Request $request){

        $inputs = $request->only('name', 'prompt');
        $chat_gpt_setup = ChatGptSetup::find($request->id);
        $chat_gpt_setup->update($inputs);

        if(count($request->input('values')) > 0) {
            $values = [];
            foreach($request->input('values') as $val) {
                $values[] = [
                    'name' => empty(explode('-', $val)[0]) ? null:explode('-', $val)[0],
                    'value' => empty(explode('-', $val)[1]) ? null:explode('-', $val)[1],
                ];
            }

            $results = $this->groupKeyArray($values);

            // clear all options
            ChatGptSetupOption::where('chat_gpt_setup_id', $request->id)->delete();

            foreach( $results as $key => $result ){
                ChatGptSetupOption::create([
                    'chat_gpt_setup_id' => $chat_gpt_setup->id,
                    'name' => $key,
                    'value' => json_encode($result),
                ]);
            }

        }

        return response()->json(['success' => true], 200);
    }

    public function showPrompt() {
        $chat_gpt_setup = ChatGptSetup::with('options')->get();
        return response()->json($chat_gpt_setup,200);
    }

    public function edit($id) {
        $chat_gpt_setup = ChatGptSetup::with('options')->findOrFail($id);

        if($chat_gpt_setup) {
            return response()->json(['success'=> true, 'result' => $chat_gpt_setup],200);
        } else {
            return response()->json(['success'=> false], 404);
        }
    }

    public function deletePrompt(Request $request) {
        $chat_gpt_setup = ChatGptSetup::find($request->input('id'));
        $chat_gpt_setup->delete();
        $chat_gpt_setup_option = ChatGptSetupOption::where('chat_gpt_setup_id', $request->input('id'));
        $chat_gpt_setup_option->delete();
    }
}
