<?php

namespace App\Livewire\Pages;

use App\Models\Outlets;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;

#[Title('Management Center')]
class ManagementCenter extends Component
{
    public $outlet_name;
    public $selected_outlet_id; // Simpan ID yang sedang diedit
    public $is_editing = false;  // Penanda mode edit

    // Aturan validasi sesuai best practice
    protected $rules = [
        'outlet_name' => 'required|min:3|max:50',
    ];

    public function mount()
    {
        $user = auth()->user();
        $current_org_id = $user->org_id;
        if (!preg_match('/[0-9]/', $current_org_id)) {

        // Buat kode baru: Nilai Asli + 5 Karakter Acak (Huruf & Angka)
        // Contoh: Jika aslinya 'PRO' -> Jadi 'PROK92A1'
        $newRandomCode = $current_org_id . strtoupper(Str::random(5));

        // $user->update([
        //     'org_id' => $newRandomCode
        // ]);

        // Outlets::where('org_id', $current_org_id)->update([
        //     'org_id' => $newRandomCode
        // ]);

        // session()->flash('success', 'Your unique organization ID has been generated!');
    }
    }

    public function saveOutlet()
    {
        $this->validate();

        try {
            Outlets::create([
                'name' => $this->outlet_name,
                'org_id' => auth()->user()->org_id, // Ikat ke organisasi owner
            ]);

            $this->reset('outlet_name');
            session()->flash('success', 'Outlet successfully added!');
        } catch (\Exception $e) {
            session()->flash('error', '' . $e->getMessage());
        }
    }

    public function deleteOutlet($id)
    {
        Outlets::find($id)->delete();
        session()->flash('success', 'Outlet telah dihapus.');
    }

    public function editOutlet($id)
    {
        $outlet = Outlets::findOrFail($id);
        $this->selected_outlet_id = $id;
        $this->outlet_name = $outlet->name;
        $this->is_editing = true;

        // Opsional: Scroll ke atas agar koki lihat formnya
        $this->dispatch('scroll-to-top');
    }

    public function updateOutlet()
    {
        $this->validate();

        try {
            $outlet = Outlets::findOrFail($this->selected_outlet_id);
            $outlet->update(['name' => $this->outlet_name]);

            $this->cancelEdit(); // Reset state
            session()->flash('success', 'Nama outlet berhasil diperbarui!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui outlet.');
        }
    }

    public function cancelEdit()
    {
        $this->reset(['outlet_name', 'selected_outlet_id', 'is_editing']);
    }

    // public function generateNewCode()
    // {
    //     $newCode = 'RSK-' . strtoupper(Str::random(6));

    //     try {
    //         $user = auth()->user();
    //         $user->update(['org_id' => $newCode]);

    //         session()->flash('success', 'New organization code generated!');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Failed to update code.');
    //     }
    // }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.management-center', [
            'outlets' => Outlets::where('org_id', auth()->user()->org_id)->get(),
            'team' => User::where('org_id', auth()->user()->org_id)->get(),
        ]);
    }
}
