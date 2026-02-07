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
        $rules = [
            'full_name' => 'required|min:3',
            'role'      => 'required|in:owner,manager,staff',
        ];

        if ($this->role === 'owner') {
            // Owner wajib isi Brand Name
            $rules['brand_name'] = 'required|min:3';
        } else {
            // Manager/Staff wajib isi Team Org ID dan harus ada di database
            $rules['team_org_id'] = 'required|exists:users,org_id';
        }

        $this->validate($rules, [
            'team_org_id.exists'      => 'The Team Org ID you entered is not registered.',
            'team_org_id.required'    => 'Please enter the Org ID provided by your Owner.',
            'brand_name.required'     => 'Please enter your restaurant/brand name.',
        ]);

        $user = auth()->user();

        // 2. Tentukan Org ID
        if ($this->role === 'owner') {
            $finalOrgId = $this->team_org_id ?: 'ORG-' . strtoupper(\Illuminate\Support\Str::random(6));
        } else {
            $finalOrgId = $this->team_org_id;
        }

        try {
            $user->update([
                'full_name'  => $this->full_name,
                'org_id'     => $finalOrgId,
                'role'       => $this->role,
                'brand_name' => $this->role === 'owner' ? $this->brand_name : null,
                'status'     => 'active',
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole($this->role);
            }

            return redirect()->to('/dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.complete-profile')->layout('layouts.guest');;
    }
}
