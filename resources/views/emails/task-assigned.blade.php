<h2>Nuovo task assegnato</h2>
<p>Ciao {{ $task->assignee->name }},</p>
<p>Ti è stato assegnato il task: <strong>{{ $task->title }}</strong></p>
<p>Priorità: {{ $task->priority }}</p>
@if($task->due_at)
<p>Scadenza: {{ $task->due_at->format('d/m/Y') }}</p>
@endif