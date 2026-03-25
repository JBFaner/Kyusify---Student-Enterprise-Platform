<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\QuickReply;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    private function enterprise()
    {
        return auth()->user()->enterprise;
    }

    public function index(Request $request)
    {
        $enterprise = $this->enterprise();
        if (!$enterprise) {
            return redirect()->route('seller.profile.edit');
        }

        $query = Conversation::where('enterprise_id', $enterprise->id)
            ->with(['customer', 'product', 'latestMessage'])
            ->orderByDesc('last_message_at');

        // Filter by status tab
        $filter = $request->get('filter', 'all');
        if ($filter === 'unread') {
            $query->whereHas('messages', fn($q) => $q->where('is_read', false)->where('sender_id', '!=', auth()->id()));
        } elseif (in_array($filter, ['pending', 'replied', 'closed'])) {
            $query->where('status', $filter);
        }

        // Search
        if ($search = $request->get('search')) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        $conversations = $query->get();

        // Unread counts per tab
        $allCount      = Conversation::where('enterprise_id', $enterprise->id)->count();
        $unreadCount   = Conversation::where('enterprise_id', $enterprise->id)
            ->whereHas('messages', fn($q) => $q->where('is_read', false)->where('sender_id', '!=', auth()->id()))
            ->count();
        $pendingCount  = Conversation::where('enterprise_id', $enterprise->id)->where('status', 'pending')->count();
        $repliedCount  = Conversation::where('enterprise_id', $enterprise->id)->where('status', 'replied')->count();
        $closedCount   = Conversation::where('enterprise_id', $enterprise->id)->where('status', 'closed')->count();

        $quickReplies = QuickReply::where('enterprise_id', $enterprise->id)->orderBy('sort_order')->get();

        // Active conversation
        $activeId = $request->get('conversation');
        $activeConversation = null;
        $messages = collect();
        if ($activeId) {
            $activeConversation = Conversation::where('enterprise_id', $enterprise->id)
                ->with(['customer', 'product'])
                ->findOrFail($activeId);

            // Mark unread messages as read
            $activeConversation->messages()
                ->where('is_read', false)
                ->where('sender_id', '!=', auth()->id())
                ->update(['is_read' => true]);

            $messages = $activeConversation->messages()->with('sender')->orderBy('created_at')->get();
        }

        return view('seller.inquiries.index', compact(
            'conversations', 'filter', 'quickReplies',
            'allCount', 'unreadCount', 'pendingCount', 'repliedCount', 'closedCount',
            'activeConversation', 'messages'
        ));
    }

    public function reply(Request $request, $id)
    {
        $enterprise = $this->enterprise();
        $conversation = Conversation::where('enterprise_id', $enterprise->id)->findOrFail($id);

        $request->validate(['message' => 'required|string|max:2000']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => auth()->id(),
            'message'         => $request->message,
            'is_read'         => false,
        ]);

        $conversation->update([
            'status'          => 'replied',
            'last_message_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('seller.inquiries.index', ['conversation' => $id]);
    }

    public function close($id)
    {
        $enterprise = $this->enterprise();
        $conversation = Conversation::where('enterprise_id', $enterprise->id)->findOrFail($id);
        $conversation->update(['status' => 'closed']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('seller.inquiries.index')->with('success', 'Conversation closed.');
    }

    public function messages($id)
    {
        $enterprise = $this->enterprise();
        $conversation = Conversation::where('enterprise_id', $enterprise->id)->findOrFail($id);

        // Mark as read
        $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', auth()->id())
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return response()->json([
            'messages'     => $messages->map(fn($m) => [
                'id'         => $m->id,
                'message'    => $m->message,
                'sender_id'  => $m->sender_id,
                'sender'     => $m->sender->name,
                'created_at' => $m->created_at->format('h:i A'),
                'is_me'      => $m->sender_id !== $conversation->customer_id,
            ]),
            'status'       => $conversation->status,
        ]);
    }
}
