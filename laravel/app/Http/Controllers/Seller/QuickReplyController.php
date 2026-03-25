<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\QuickReply;
use Illuminate\Http\Request;

class QuickReplyController extends Controller
{
    private function enterprise()
    {
        return auth()->user()->enterprise;
    }

    public function index()
    {
        $enterprise   = $this->enterprise();
        $quickReplies = QuickReply::where('enterprise_id', $enterprise->id)
            ->orderBy('sort_order')
            ->get();

        return view('seller.inquiries.quick-replies', compact('quickReplies'));
    }

    public function store(Request $request)
    {
        $enterprise = $this->enterprise();
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string|max:1000',
        ]);

        $maxOrder = QuickReply::where('enterprise_id', $enterprise->id)->max('sort_order') ?? 0;

        QuickReply::create([
            'enterprise_id' => $enterprise->id,
            'question'      => $request->question,
            'answer'        => $request->answer,
            'sort_order'    => $maxOrder + 1,
        ]);

        return back()->with('success', 'Quick reply added.');
    }

    public function update(Request $request, $id)
    {
        $enterprise = $this->enterprise();
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string|max:1000',
        ]);

        QuickReply::where('enterprise_id', $enterprise->id)->findOrFail($id)
            ->update([
                'question' => $request->question,
                'answer'   => $request->answer,
            ]);

        return back()->with('success', 'Quick reply updated.');
    }

    public function destroy($id)
    {
        $enterprise = $this->enterprise();
        QuickReply::where('enterprise_id', $enterprise->id)->findOrFail($id)->delete();

        return back()->with('success', 'Quick reply deleted.');
    }

    public function reorder(Request $request)
    {
        $enterprise = $this->enterprise();
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->order as $position => $id) {
            QuickReply::where('enterprise_id', $enterprise->id)
                ->where('id', $id)
                ->update(['sort_order' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
