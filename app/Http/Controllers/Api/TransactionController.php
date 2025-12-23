<?php

namespace App\Http\Controllers\Api;

use App\Events\TransactionCompleted;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $transactions = Transaction::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with(['sender', 'receiver'])
            ->paginate(10);

        return response()->json([
            'balance' => $user->balance,
            'transactions' => $transactions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|integer|exists:users,id|not_in:' . auth('api')->id(),
            'amount' => 'required|numeric|min:0.01',
        ], [
            'receiver_id.required' => 'Please select Recipient User',
            'receiver_id.integer' => 'Invalid Recipient User',
            'receiver_id.exists' => 'Recipient does not exist',
            'receiver_id.not_in' => 'You cannot send money to yourself',
            'amount.required' => 'Please enter an amount',
            'amount.numeric' => 'Amount must be a number',
            'amount.min' => 'Amount must be at least 0.01',
        ]);
        $senderId = auth('api')->id();
        $receiverId = (int)$data['receiver_id'];
        $amount = round((float)$data['amount'], 2);
        $commission = round($amount * 0.015, 2);
        $totalDebit = round($amount + $commission, 2);
        $ids = [$senderId, $receiverId];

        sort($ids);
        DB::beginTransaction();
        try {
            $users = User::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');
            $sender = $users[$senderId];
            $receiver = $users[$receiverId];
            if (bccomp($sender->balance, $totalDebit, 2) < 0) {
                DB::rollBack();
                return response()->json(['error' => 'Insufficient balance'], 422);
            }
            $sender->balance = bcsub($sender->balance, $totalDebit, 2);
            $receiver->balance = bcadd($receiver->balance, $amount, 2);
            $sender->save();
            $receiver->save();
            $tx = Transaction::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'amount' => $amount,
                'commission_fee' => $commission,
                'status' => 'completed',
            ]);
            DB::commit();
            event(new TransactionCompleted($tx));
            return response()->json(['transaction' => $tx, 'balance' => $sender->balance]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transfer failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function addBalance(Request $request)
    {
        $request->validate([
            'balance' => 'required|numeric|min:1',
        ]);

        $amount = (float)$request->balance;

        auth('api')->user()->increment('balance', $amount);

        return response()->json([
            'message' => 'Balance updated',
            'new_balance' => auth('api')->user()->balance,
        ]);
    }
}
