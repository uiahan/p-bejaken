<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PenggunaRequest;
use App\Models\User;
use App\Support\NomorHp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    private const PER_HALAMAN = 10;

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $role = $request->query('role');
        $role = is_string($role) && array_key_exists($role, User::LABEL_ROLE) ? $role : null;

        $daftar = User::query()
            ->when($role, fn ($w) => $w->where('role', $role))
            ->when($q !== '', function ($w) use ($q) {
                $like = '%' . addcslashes($q, '%_\\') . '%';
                $hp = '%' . addcslashes(NomorHp::normalisasi($q), '%_\\') . '%';

                $w->where(function ($x) use ($like, $hp, $q) {
                    $x->where('name', 'like', $like)->orWhere('email', 'like', $like);
                    if (preg_match('/^[\d+\s-]{3,}$/', $q)) {
                        $x->orWhere('no_hp', 'like', $hp);
                    }
                });
            })
            ->orderByRaw("case role when 'superadmin' then 0 when 'admin' then 1 else 2 end")
            ->latest('id')
            ->paginate(self::PER_HALAMAN)
            ->withQueryString();

        return view('features.admin.pengguna.index', [
            'daftar' => $daftar,
            'q' => $q,
            'role' => $role,
            'hitung' => User::selectRaw('role, count(*) as n')->groupBy('role')->pluck('n', 'role'),
        ]);
    }

    public function create()
    {
        return view('features.admin.pengguna.create', [
            'pengguna' => new User(['role' => User::ROLE_ADMIN]),
        ]);
    }

    public function store(PenggunaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_hp' => $data['phone'],
            'alamat' => $data['alamat'] ?? null,
            'role' => $data['role'],
            'password' => $data['password'], 
        ]);

        return redirect()
            ->route('admin.pengguna.index')
            ->with('toast', ['type' => 'success', 'message' => "Pengguna {$user->name} berhasil ditambahkan."]);
    }

    public function edit(User $pengguna)
    {
        return view('features.admin.pengguna.edit', ['pengguna' => $pengguna]);
    }

    public function update(PenggunaRequest $request, User $pengguna): RedirectResponse
    {
        $data = $request->validated();

        $pengguna->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_hp' => $data['phone'],
            'alamat' => $data['alamat'] ?? null,
        ]);

        if (isset($data['role'])) {
            $pengguna->role = $data['role'];
        }
        if (! empty($data['password'])) {
            $pengguna->password = $data['password'];
        }

        $pengguna->save();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('toast', ['type' => 'success', 'message' => "Data {$pengguna->name} berhasil disimpan."]);
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->is($request->user())) {
            return $this->gagal('Kamu tidak bisa menghapus akunmu sendiri.');
        }

        if ($pengguna->mutasiStoks()->exists()) {
            return $this->gagal("{$pengguna->name} punya riwayat mutasi stok, jadi tidak bisa dihapus.");
        }

        $nama = $pengguna->name;
        $pengguna->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('toast', ['type' => 'success', 'message' => "Pengguna {$nama} dihapus."]);
    }

    private function gagal(string $pesan): RedirectResponse
    {
        return redirect()
            ->route('admin.pengguna.index')
            ->with('toast', ['type' => 'error', 'message' => $pesan]);
    }
}