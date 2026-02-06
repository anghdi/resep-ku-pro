<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CompleteProfile extends Component
{
    public $brand_name, $team_org_id;
    public $full_name;
    public $role = ''; // Default kosong

    public function saveProfile()
    {
        $this->validate([
        'full_name' => 'required|min:3',
        'role' => 'required|in:owner,manager,staff',
        'brand_name' => 'required_if:role,owner|min:3',
        'team_org_id' => 'required',
        ]);

        $user = auth()->user();

        $user->assignRole($this->role);

        $user->update([
            'full_name'  => $this->full_name,
            'org_id'     => $this->team_org_id,
            'role'       => $this->role,
            'brand_name' => $this->role === 'owner' ? $this->brand_name : null,
            'status'     => 'active',
        ]);
    }

    public function render()
    {
        return view('livewire.complete-profile')->layout('layouts.guest');;
    }
}
