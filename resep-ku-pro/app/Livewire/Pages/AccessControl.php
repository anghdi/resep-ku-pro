<?php

namespace App\Livewire\Pages;

use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class AccessControl extends Component
{
    use WithPagination;

    public $search = '';

    /**
     * Definisi Modul & Label (Disesuaikan dengan Sidebar Chef)
     */
    public $modules = [
        'ingredients' => 'Manage Ingredients',
        'categories'  => 'Manage Categories',
        'menus'       => 'Add New Menu',
        'management'  => 'Management Center',
    ];

    public function mount()
    {
        // Proteksi Owner aktifkan jika sudah masuk tahap production
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Unauthorized. Access restricted to Owners only.');
        }
    }

    public function updatingSearch() { $this->resetPage(); }

    public function togglePermission($userId, $module, $field)
    {
        try {
            $user = User::findOrFail($userId);

            // 1. Update data izin
            $permission = UserPermission::updateOrCreate(
                ['user_id' => $userId, 'module' => $module],
                ['org_id' => auth()->user()->org_id]
            );

            $newValue = !$permission->$field;
            $permission->update([$field => $newValue]);

            $this->writeLog(
                'Update Permission',
                "Changed access " . strtoupper(str_replace('can_', '', $field)) . " to " . ($newValue ? 'ENABLED' : 'DISABLED') . " for {$user->email} on module {$module}"
            );

            session()->flash('success', "Access updated!");

        } catch (\Exception $e) {
            // Jika ada error (misal: kolom tabel log tidak cocok), akan muncul di sini
            session()->flash('error', "Failed to log activity: " . $e->getMessage());
        }
    }

    /**
     * RESET PERMISSIONS (Cabut Semua Akses)
     */
    public function resetPermissions($userId)
    {
        $user = User::findOrFail($userId);
        UserPermission::where('user_id', $userId)->delete();

        $this->writeLog('Reset Permission', "All access rights for {$user->email} have been revoked");
        session()->flash('success', "Permissions for {$user->email} have been reset to default.");
    }

    private function writeLog($action, $details)
    {
            DB::table('activity_logs')->insert([
            'id'         => (string) Str::uuid(),
            'org_id'     => auth()->user()->org_id,
            'user_email' => auth()->user()->email,
            'action'     => $action,
            'details'    => $details,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function render()
    {
        $query = User::where('org_id', auth()->user()->org_id)
        ->where('id', '!=', auth()->id());

        // Filter pencarian
        $query->when($this->search, function ($q) {
            $q->where('email', 'LIKE', '%' . $this->search . '%');
        });

        return view('livewire.pages.access-control', [
            'staffList' => $query->with('permissions')->paginate(10),
        ])->layout('layouts.app');
    }
}
