<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes;
        return response()->json($notes, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        
        $data['user_id'] = auth()->user()->id;
        
        $note = Note::create($data);

        return response()->json($note, 201);
    }


    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);
        
        $data = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            ]);
            
            
        $note->update($data);

        return response()->json($note, 200);
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return response()->json(['message' => 'Note deleted successfully'], 200);
    }
}
