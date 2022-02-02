<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class TodoController extends Controller
{
      public function __construct()
      {
            $this->middleware('auth:api');
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            $todos = Todo::where('user_id', Auth::user()->id)->get();
            return response()->json([
                  'status' => 'success',
                  'data' => $todos
            ]);
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store(Request $request)
      {
            $data = $this->validate($request, [
                  'user_id' => 'required|integer|exists:users,id',
                  'content' => 'required|string|max:255',
            ]);

            $data['completed'] = false;
            $data['complete_date'] = null;

            $todo = Todo::create($data);

            return response()->json([
                  'status' => 'success',
                  'todo' => $todo,
            ]);
      }

      /**
       * Display the specified resource.
       *
       * @param  Todo  $todo
       * @return Response
       */
      public function show($id)
      {
            $todo = Todo::findOrFail($id);
            return response()->json([
                  'status' => 'success',
                  'todo' => $todo,
            ]);
      }

      /**
       * Update the specified resource in storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function update($id)
      {
            $todo = Todo::findOrFail($id);
            $todo->completed = !$todo->completed;
            $todo->complete_date = $todo->completed ? date('Y-m-d H:i:s') : null;
            $todo->save();

            return response()->json([
                  'status' => 'success',
                  'todo' => $todo,
            ]);
      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function destroy($id)
      {
            $todo = Todo::findOrFail($id);
            $todo->delete();

            return response()->json([
                  'status' => 'success',
                  'todo' => "Todo Deleted Successfully",
            ]);
      }

      /**
       * mark as complete todo
       */
      public function markAsComplete($id)
      {
            $todo = Todo::findOrFail($id);
            $todo->completed = true;
            $todo->complete_date = Carbon::now();
            $todo->save();

            return response()->json([
                  'status' => 'success',
                  'todo' => $todo,
            ]);
      }

      /**
       * mark as incomplete todo
       */
      public function markAsIncomplete($id)
      {
            $todo = Todo::findOrFail($id);
            $todo->completed = false;
            $todo->complete_date = null;
            $todo->save();

            return response()->json([
                  'status' => 'success',
                  'todo' => $todo,
            ]);
      }

}
