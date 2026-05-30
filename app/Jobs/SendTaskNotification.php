<?php

namespace App\Jobs;

use App\Mail\TaskAssigned;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\Support\Facades\Log;

class SendTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public Task $task) {}

    public function handle(): void
    {
        if (! $this->task->assignee) {
            return;
        }

        Mail::to($this->task->assignee->email)
            ->send(new TaskAssigned($this->task));
    }

    public function failed(Throwable $e): void
    {
        Log::error('SendTaskNotification fallito', [
            'task_id' => $this->task->id,
            'error'   => $e->getMessage(),
        ]);
    }
}