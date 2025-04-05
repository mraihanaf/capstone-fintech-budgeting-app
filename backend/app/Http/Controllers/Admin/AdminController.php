<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Log;

class AdminController extends Controller
{
    public function getUsers() {
        $users = User::paginate(10);

        return response()->json([
            'message' => 'Get user success.',
            'data' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
            ]
        ]);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['message' => 'User retrieved successfully', 'data' => $user], 200);
    }

    public function deactivateUser($email) {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $user->status = 'deactive';
        $user->save();

        return response()->json([
            'message' => 'User deactive success.'
        ], 200);
    }

    // TRANSACTION
    public function getAllTransactions()
    {
        $transactions = Transaction::with(['user', 'category'])->paginate(10);

        return response()->json([
            'message' => 'Transactions retrieved successfully',
            'data' => $transactions
        ], 200);
    }



    public function deleteTransaction($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found.'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'message' => 'Transaction deleted successfully.'
        ], 200);
    }

    // CATEGORY

    public function getAllCategories()
    {
        $categories = Category::all();
        return response()->json(['message' => 'Categories retrieved successfully', 'data' => $categories], 200);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.'
        ], 200);
    }

    // LOG
    public function getLogs() {
        $logs = Log::all();
        return response()->json(['message' => 'Logs retrieved successfully', 'data' => $logs], 200);
    }
}
