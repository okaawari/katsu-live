<?php

namespace App\Livewire;

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public $newAvatar; // This holds the uploaded file

    public function save()
    {
        $this->validate([
            'newAvatar' => 'required|image|max:1024', // Validate the uploaded image
        ]);

        // Store the new avatar
        $filename = $this->newAvatar->store('/', 'public');

        // Update the user's avatar in the database
        auth()->user()->update([
            'avatar' => $filename,
        ]);

        // Emit a success message
        $this->emitSelf('notify-saved');
    }

    public function render()
    {
        return view('profile-picture'); // Ensure this view exists in resources/views/livewire/
    }
};

