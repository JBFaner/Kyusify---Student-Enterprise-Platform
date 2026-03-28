<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Enterprise;
use App\Models\Message;
use App\Models\QuickReply;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function startOrGet(Request $request, $enterpriseId)
    {
        $enterprise = Enterprise::findOrFail($enterpriseId);

        // Find an existing open conversation
        $conversation = Conversation::where('customer_id', auth()->id())
            ->where('enterprise_id', $enterprise->id)
            ->whereIn('status', ['pending', 'replied'])
            ->latest()
            ->first();

        // None found — create a fresh one
        if (!$conversation) {
            $conversation = Conversation::create([
                'customer_id'    => auth()->id(),
                'enterprise_id'  => $enterprise->id,
                'product_id'     => $request->product_id ?? null,
                'status'         => 'pending',
                'last_message_at'=> now(),
            ]);
        }

        // Mark seller messages as read for customer
        $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        // Load this enterprise's quick replies (question+answer pairs)
        $quickReplies = QuickReply::where('enterprise_id', $enterprise->id)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'conversation_id' => $conversation->id,
            'status'          => $conversation->status,
            'enterprise_name' => $enterprise->name,
            'messages'        => $messages->map(fn($m) => [
                'id'        => $m->id,
                'message'   => $m->message,
                'sender_id' => $m->sender_id,
                'sender'    => $m->sender->name,
                'created_at'=> $m->created_at->format('h:i A'),
                'is_me'     => $m->sender_id === auth()->id(),
            ]),
            'quick_replies'   => $quickReplies->map(fn($qr) => [
                'id'       => $qr->id,
                'question' => $qr->question,
                'answer'   => $qr->answer,
            ]),
        ]);
    }

    public function send(Request $request, $conversationId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $conversation = Conversation::where('customer_id', auth()->id())
            ->findOrFail($conversationId);

        if ($conversation->status === 'closed') {
            return response()->json(['error' => 'Conversation is closed.'], 422);
        }

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'message'         => $request->message,
            'is_read'         => false,
        ]);

        $conversation->update([
            'status'          => 'pending',
            'last_message_at' => now(),
        ]);

        $enterprise = $conversation->enterprise()->with('user')->first();
        if ($enterprise && $enterprise->user_id) {
            \App\Helpers\NotificationHelper::send(
                $enterprise->user_id,
                'new_inquiry',
                'New Inquiry Message',
                'You received a new message from ' . auth()->user()->name . '.',
                route('seller.inquiries.index'),
                'inquiry'
            );
        }

        return response()->json([
            'id'        => $msg->id,
            'message'   => $msg->message,
            'sender_id' => $msg->sender_id,
            'sender'    => auth()->user()->name,
            'created_at'=> $msg->created_at->format('h:i A'),
            'is_me'     => true,
        ]);
    }

    /**
     * Customer clicks a quick reply button:
     * 1. Sends the QUESTION as the customer's message
     * 2. Auto-sends the ANSWER as the seller's message instantly
     */
    public function autoReply(Request $request, $conversationId)
    {
        $request->validate(['quick_reply_id' => 'required|integer']);

        $conversation = Conversation::where('customer_id', auth()->id())
            ->findOrFail($conversationId);

        if ($conversation->status === 'closed') {
            return response()->json(['error' => 'Conversation is closed.'], 422);
        }

        $quickReply = QuickReply::where('enterprise_id', $conversation->enterprise_id)
            ->findOrFail($request->quick_reply_id);

        // Get seller user (enterprise owner)
        $enterprise = $conversation->enterprise()->with('user')->first();
        $sellerId   = $enterprise->user_id;

        $now = now();

        // 1. Customer question message
        $customerMsg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'message'         => $quickReply->question,
            'is_read'         => false,
            'created_at'      => $now,
            'updated_at'      => $now,
        ]);

        // 2. Seller auto-answer message (1 second later to ensure ordering)
        $autoMsg = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $sellerId,
            'message'         => $quickReply->answer,
            'is_read'         => true, // customer is reading it right now
            'created_at'      => $now->copy()->addSecond(),
            'updated_at'      => $now->copy()->addSecond(),
        ]);

        $conversation->update([
            'status'          => 'replied',
            'last_message_at' => now(),
        ]);

        return response()->json([
            'messages' => [
                [
                    'id'        => $customerMsg->id,
                    'message'   => $customerMsg->message,
                    'sender_id' => $customerMsg->sender_id,
                    'sender'    => auth()->user()->name,
                    'created_at'=> $customerMsg->created_at->format('h:i A'),
                    'is_me'     => true,
                ],
                [
                    'id'        => $autoMsg->id,
                    'message'   => $autoMsg->message,
                    'sender_id' => $autoMsg->sender_id,
                    'sender'    => $enterprise->name,
                    'created_at'=> $autoMsg->created_at->format('h:i A'),
                    'is_me'     => false,
                ],
            ],
        ]);
    }

    public function poll(Request $request, $conversationId)
    {
        $conversation = Conversation::where('customer_id', auth()->id())
            ->findOrFail($conversationId);

        $query = $conversation->messages()->with('sender');

        if ($lastId = $request->get('last_id')) {
            $query->where('id', '>', $lastId);
        }

        $messages = $query->orderBy('created_at')->get();

        $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages->map(fn($m) => [
                'id'        => $m->id,
                'message'   => $m->message,
                'sender_id' => $m->sender_id,
                'sender'    => $m->sender->name,
                'created_at'=> $m->created_at->format('h:i A'),
                'is_me'     => $m->sender_id === auth()->id(),
            ]),
            'status' => $conversation->status,
        ]);
    }
}
