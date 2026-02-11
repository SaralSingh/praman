<?php

namespace App\Http\Controllers\Api\Private;

use App\Models\User;
use App\Models\Person;
use App\Models\Presence;
use App\Models\ListModel;
use Illuminate\Http\Request;
use App\Models\PramanSession;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => $request->user()->only(['id', 'name', 'email'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createList(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $list = ListModel::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'List created successfully',
            'data' => $list
        ], 201);
    }



    public function getLists(Request $request)
    {
        $lists = ListModel::where('user_id', $request->user()->id)
            ->select('id', 'name')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $lists
        ]);
    }

    public function addPerson(Request $request, $listId)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100']
        ]);

        $person = Person::create([
            'list_id' => $listId,
            'name' => $validated['name']
        ]);

        return response()->json([
            'status' => true,
            'data' => $person
        ], 201);
    }


    public function bulkAddPeople(Request $request, $listId)
    {
        $validated = $request->validate([
            'names' => ['required', 'array'],
            'names.*' => ['required', 'string', 'max:100']
        ]);

        // Optional safety: ensure list belongs to this user
        $list = $request->user()->lists()->where('id', $listId)->firstOrFail();

        $data = [];

        foreach ($validated['names'] as $name) {
            $data[] = [
                'list_id' => $listId,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Person::insert($data);

        return response()->json([
            'status' => true,
            'message' => 'People added successfully'
        ], 201);
    }


    public function getPeople($listId)
    {
        $people = Person::where('list_id', $listId)->get();

        return response()->json([
            'status' => true,
            'data' => $people
        ]);
    }


    public function startSession(Request $request, $listId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $today = Carbon::today()->toDateString();
        $title = trim($request->title);

        // Check existing session
        $session = PramanSession::where('list_id', $listId)
            ->where('session_date', $today)
            ->where('title', $title)
            ->first();

        // If not exists → create
        if (!$session) {
            $session = PramanSession::create([
                'list_id' => $listId,
                'session_date' => $today,
                'title' => $title,
                'status' => 'active',
            ]);

            // 🔥 IMPORTANT PART — create attendance sheet
            $peopleIds = Person::where('list_id', $listId)->pluck('id');

            $rows = $peopleIds->map(function ($pid) use ($session) {
                return [
                    'praman_session_id' => $session->id,
                    'person_id' => $pid,
                    'is_present' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            Presence::insert($rows);
        }

        return response()->json([
            'data' => $session
        ]);
    }

    public function getSessions($listId)
    {
        $sessions = PramanSession::where('list_id', $listId)
            ->orderByDesc('session_date')
            ->orderByDesc('id')
            ->get(['id', 'title', 'session_date', 'status']);

        return response()->json([
            'data' => $sessions
        ]);
    }

    public function getPeopleSession(PramanSession $session)
    {
        // Get all people of this session's list
        $people = Person::where('list_id', $session->list_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'session_date' => $session->session_date,
                'status' => $session->status,
            ],
            'people' => $people,
        ]);
    }

    public function storePresence(Request $request, PramanSession $session)
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'is_present' => 'required|boolean',
            'final_save' => 'nullable|boolean'
        ]);

        if ($session->status === 'closed') {
            return response()->json([
                'message' => 'Session already closed'
            ], 403);
        }

        Presence::where('praman_session_id', $session->id)
            ->where('person_id', $request->person_id)
            ->update([
                'is_present' => $request->is_present
            ]);

        // If this is last call → close session
        if ($request->final_save) {
            $session->update(['status' => 'closed']);
        }

        return response()->json(['message' => 'Updated']);
    }

public function getSessionAttendance(string $id)
{
    $session = PramanSession::select('id', 'title', 'session_date', 'status')
        ->findOrFail($id);

    $presences = Presence::with(['person:id,name'])
        ->where('praman_session_id', $id)
        ->get();

    $people = $presences->map(function ($p) {
        return [
            'id' => $p->person->id,
            'name' => $p->person->name,
            'is_present' => $p->is_present,
        ];
    });

    return response()->json([
        'status' => true,
        'session' => [
            'id' => $session->id,
            'title' => $session->title,
            'session_date' => $session->session_date,
            'status' => $session->status,
        ],
        'people' => $people
    ], 200);
}


public function loadSessionNames(string $id)
{
    $sessions = PramanSession::where('list_id', $id)
        ->select('id', 'title', 'session_date')
        ->orderBy('session_date', 'desc')
        ->get();

    return response()->json([
        'status' => true,
        'sessions' => $sessions
    ], 200);
}


public function deleteList(ListModel $list)
{
    // 1) Ownership via policy
    $this->authorize('delete', $list);

    // 2) Historical integrity rule
    if ($list->sessions()->exists()) {
        return response()->json([
            'message' => 'This list has session history and cannot be deleted.'
        ], 422);
    }

    // 3) Soft delete (archive)
    $list->delete();

    return response()->json([
        'message' => 'List archived successfully.'
    ]);
}





    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
