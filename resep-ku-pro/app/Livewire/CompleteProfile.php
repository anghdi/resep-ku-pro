<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CompleteProfile extends Component
{
    public $brand_name, $team_org_id;
    public $role = ''; // Default kosong

    public function saveProfile()
    {
        $this->validate([
            'role' => 'required|in:owner,staff',
            'brand_name' => $this->role === 'owner' ? 'required|min:3' : 'nullable',
            'team_org_id' => $this->role === 'staff' ? 'required' : 'nullable',
        ]);

        $user = auth()->user();
        $user->update([
            'role' => $this->role,
            'org_id' => $this->role === 'owner' ? $this->brand_name : $this->team_org_id,
        ]);

        return redirect()->route('dashboard');
    }

    public function saveRole($selected)
    {
        // Validasi berdasarkan kartu yang diklik
        $this->validate([
            'brand_name'  => $selected === 'owner' ? 'required|min:3' : 'nullable',
            'team_org_id' => $selected === 'staff' ? 'required' : 'nullable',
        ]);

        $user = Auth::user();

        $user->update([
            'role'   => $selected,
            'org_id' => ($selected === 'owner') ? $this->brand_name : $this->team_org_id,
        ]);

        return redirect()->route('dashboard');
    }
    public function render()
    {
        return view('livewire.complete-profile')->layout('layouts.guest');;
    }
}
