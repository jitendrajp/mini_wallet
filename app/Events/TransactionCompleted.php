<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TransactionCompleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction->load(['sender', 'receiver']);
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->transaction->sender_id),
            new PrivateChannel('user.' . $this->transaction->receiver_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'transaction' => $this->transaction->toArray(),
        ];
    }
}
