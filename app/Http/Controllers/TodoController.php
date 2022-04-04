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
            return response()->json([
                  'status' => 'success',
                  'data' => Todo::where('user_id', Auth::user()->id)->get()
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
            $todo= Todo::create($data);
            $todo ?
            return response()->json([
                  'status' => 'success',
                  'todo' => $todo
            ]):return response()->json([
                  'status' => 'error',
                  'todo' => 'Todo not created'
            ])
      }

      /**
       * Display the specified resource.
       *
       * @param  Todo  $todo
       * @return Response
       */
      public function show($id)
      {
            return response()->json([
                  'status' => 'success',
                  'todo' => Todo::findOrFail($id),
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
            Todo::findOrFail($id)->delete();

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
            return response()->json([
                  'status' => 'success',
                  'todo' => Todo::findOrFail($id)->update(['completed'=>true,'complete_date'=>Carbon::now()]),
            ]);
      }

      /**
       * mark as incomplete todo
       */
      public function markAsIncomplete($id)
      {
            return response()->json([
                  'status' => 'success',
                  'todo' => Todo::findOrFail($id)->update(['completed'=>false,'complete_date'=>null),
            ]);
      }

}
