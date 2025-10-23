<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckListController extends Controller
{
    // Show view with current user's groups and items
    public function index()
    {
        $userId = Auth::id();
        $groups = Checklist::with('items')
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        return view('check.check', compact('groups'));
    }

    // Add a new group for the authenticated user
    public function addGroup(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);

        Checklist::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
        ]);

        return redirect()->back()->with('success', 'Group added successfully!');
    }

    // Delete a group — only owner can delete
    public function deleteGroup($id)
    {
        $group = Checklist::findOrFail($id);

        // Ownership check
        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $group->delete();

        return redirect()->back()->with('success', 'Group deleted successfully!');
    }

    // Add an item into a group (must belong to user)
    public function addItem(Request $request, $groupId)
    {
        $request->validate(['text' => 'required|string|max:255']);

        $group = Checklist::findOrFail($groupId);
        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        ChecklistItem::create([
            'checklist_id' => $group->id,
            'text' => $request->text,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Item added successfully!');
    }

    // Toggle item status (pending <-> finished)
    public function toggleItem($itemId)
    {
        $item = ChecklistItem::findOrFail($itemId);
        $group = $item->checklist;

        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $item->status = $item->status === 'pending' ? 'finished' : 'pending';
        $item->save();

        return redirect()->back();
    }

    // Delete a single item (owner check)
    public function deleteItem($itemId)
    {
        $item = ChecklistItem::findOrFail($itemId);
        $group = $item->checklist;

        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $item->delete();

        return redirect()->back()->with('success', 'Item deleted successfully!');
    }

    // Clear all items in a group (owner check)
    public function clearItems($groupId)
    {
        $group = Checklist::findOrFail($groupId);

        if ($group->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        ChecklistItem::where('checklist_id', $group->id)->delete();

        return redirect()->back()->with('success', 'All items cleared successfully!');
    }
}
