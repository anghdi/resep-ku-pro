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
    public $modules = ['Ingredients', 'Recipes', 'Activity Logs', 'Management'];

    public function mount()
    {
        /**
         * PROTEKSI OWNER:
         * Pastikan kolom 'role' di tabel users koki isinya 'owner'.
         */
        // if (auth()->user()->role !== 'owner') {
        //     abort(403, 'Unauthorized. This area is for Owners only!');
        // }
    }

    public function updatingSearch() { $this->resetPage(); }

    /**
     * TOGGLE PERMISSION (Create/Update Logic)
     */
    public function togglePermission($userId, $module, $field)
    {
        $user = User::findOrFail($userId);

        // CRUD Logic: updateOrCreate
        $permission = UserPermission::updateOrCreate(
            ['user_id' => $userId, 'module' => $module],
            ['org_id' => auth()->user()->org_id]
        );

        $newValue = !$permission->$field;
        $permission->update([$field => $newValue]);

        // CCTV Logging
        $this->writeLog('Update Permission', "Changed {$field} to " . ($newValue ? 'ENABLED' : 'DISABLED') . " for {$user->email} on module {$module}");

        session()->flash('success', "Permission for {$user->name} updated successfully!");
    }

    /**
     * RESET PERMISSIONS (Delete Logic)
     */
    public function resetPermissions($userId)
    {
        $user = User::findOrFail($userId);
        UserPermission::where('user_id', $userId)->delete();

        $this->writeLog('Reset Permission', "All permissions for {$user->email} have been revoked");
        session()->flash('success', "All permissions for {$user->name} have been reset.");
    }

    private function writeLog($action, $details)
    {
        DB::table('activity_logs')->insert([
            'id' => (string) Str::uuid(),
            'org_id' => auth()->user()->org_id,
            'user_email' => auth()->user()->email,
            'action' => $action,
            'details' => $details,
            'created_at' => now(),
        ]);
    }

    public function render()
    {
        $query = User::where('org_id', auth()->user()->org_id)
            ->where('id', '!=', auth()->id());

        // Hanya jalankan REGEXP jika $search tidak kosong
        $query->when($this->search, function ($q) {
            try {
                $q->where('email', 'REGEXP', $this->search);
            } catch (\Exception $e) {
                // Jika user ngetik regex yang salah (misal: cuma ngetik '[' )
                // kita fallback ke pencarian LIKE biasa agar tidak error
                $q->where('email', 'LIKE', '%' . $this->search . '%');
            }
        });

        return view('livewire.pages.access-control', [
            'staffList' => $query->paginate(10),
            'allPermissions' => UserPermission::where('org_id', auth()->user()->org_id)->get()
        ])->layout('layouts.app');
    }
}
