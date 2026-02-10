<?php
namespace App\Livewire\Events;
use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.app')]
class Index extends Component
{
public ?Event $eventToDelete = null;
protected $listeners = ['refreshEvents' => '$refresh'];
public function confirmDelete(Event $event)
{
$this->eventToDelete = $event;
$this->dispatch('open-modal', 'confirm-delete-event');
}
public function delete()
{
if (! $this->eventToDelete) return;
$this->eventToDelete->delete();
$this->eventToDelete = null;
$this->dispatch('close-modal', 'confirm-delete-event');
$this->dispatch('flash', message: 'Događaj je obrisan.');
}
public function render()
{
return view('livewire.events.index', [
'events' => Event::query()->latest()->get(),
]);
}
}