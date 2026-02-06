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
            'full_name'   => 'required|min:3',
            'role'        => 'required|in:owner,manager,staff',
            // Brand Name hanya wajib untuk Owner
            'brand_name'  => 'required_if:role,owner|nullable|min:3',
            // Team Org ID wajib untuk Manager/Staff dan harus terdaftar di DB
            'team_org_id' => 'required_if:role,manager,staff|nullable|exists:users,org_id',
        ], [
            'team_org_id.exists' => 'The Team Org ID you entered is not registered in our system.',
            'team_org_id.required_if' => 'Please enter the Org ID provided by your Owner.',
        ]);

        $user = auth()->user();

        // Jika Owner, buat ID baru. Jika Manager/Staff, gunakan ID yang di-paste.
        $finalOrgId = ($this->role === 'owner')
            ? 'ORG-' . strtoupper(\Illuminate\Support\Str::random(6))
            : $this->team_org_id;

        try {
            $user->update([
                'full_name'  => $this->full_name,
                'org_id'     => $finalOrgId,
                'role'       => $this->role,
                'brand_name' => $this->role === 'owner' ? $this->brand_name : null,
                'status'     => 'active',
            ]);

            // Opsional: Spatie Role (jika digunakan)
            if (method_exists($user, 'assignRole')) {
                $user->assignRole($this->role);
            }

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while saving your profile.');
        }
    }

    public function render()
    {
        return view('livewire.complete-profile')->layout('layouts.guest');;
    }
}
