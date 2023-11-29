<?php

namespace App\Http\Controllers;

use App\ChatGptSetup;
use App\ChatGptSetupOption;
use Illuminate\Http\Request;

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
