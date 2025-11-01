<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $req)
{
    $msg = TaskMessage::create([
        'task_id' => $req->task_id,
        'sender_id' => auth()->id(),
        'message' => $req->message
    ]);

    broadcast(new TaskMessageSent($msg->message, auth()->user()->name, $req->task_id))->toOthers();

    return response()->json(['success' => true]);
}

}
