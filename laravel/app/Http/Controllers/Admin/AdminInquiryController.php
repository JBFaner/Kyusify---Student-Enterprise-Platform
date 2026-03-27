<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Enterprise;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInquiryController extends Controller
{
    public function index(Request $request)
    {
        // Stats
        $totalConversations   = Conversation::count();
        $pendingConversations = Conversation::where('status', 'pending')->count();
        $repliedConversations = Conversation::where('status', 'replied')->count();
        $closedConversations  = Conversation::where('status', 'closed')->count();
        $todayConversations   = Conversation::whereDate('created_at', today())->count();
        $totalMessages        = Message::count();

        // Conversations query with filters
        $filter = $request->input('filter', 'all');
        $search = $request->input('search', '');

        $query = Conversation::with(['customer', 'enterprise', 'product', 'latestMessage'])
            ->orderByDesc('last_message_at');

        if (in_array($filter, ['pending', 'replied', 'closed'])) {
            $query->where('status', $filter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', fn($q2) => $q2->where('name', 'like', "%$search%"))
                  ->orWhereHas('enterprise', fn($q2) => $q2->where('name', 'like', "%$search%"))
                  ->orWhere('id', $search);
            });
        }

        $conversations = $query->paginate(20)->withQueryString();

        // Active conversation
        $activeConversation = null;
        $messages = collect();
        if ($activeId = $request->input('conversation')) {
            $activeConversation = Conversation::with(['customer', 'enterprise.user', 'product'])->find($activeId);
            if ($activeConversation) {
                $messages = $activeConversation->messages()->with('sender')->orderBy('created_at')->get();
            }
        }

        // Seller performance
        $sellerStats = Enterprise::withCount([
            'orders',
        ])->with(['user'])->whereHas('user')->get()->map(function ($e) {
            $convos = Conversation::where('enterprise_id', $e->id)->get();
            return [
                'name'    => $e->name,
                'seller'  => $e->user->name ?? '—',
                'total'   => $convos->count(),
                'pending' => $convos->where('status', 'pending')->count(),
                'replied' => $convos->where('status', 'replied')->count(),
                'closed'  => $convos->where('status', 'closed')->count(),
            ];
        })->sortByDesc('total')->take(10)->values();

        return view('admin.inquiries.index', compact(
            'totalConversations', 'pendingConversations', 'repliedConversations',
            'closedConversations', 'todayConversations', 'totalMessages',
            'conversations', 'filter', 'search',
            'activeConversation', 'messages', 'sellerStats'
        ));
    }

    public function close($id)
    {
        Conversation::findOrFail($id)->update(['status' => 'closed']);
        return back()->with('success', 'Conversation closed.');
    }

    public function reopen($id)
    {
        Conversation::findOrFail($id)->update(['status' => 'pending']);
        return back()->with('success', 'Conversation reopened.');
    }

    public function deleteMessage($id)
    {
        Message::findOrFail($id)->delete();
        return back()->with('success', 'Message deleted.');
    }
}
