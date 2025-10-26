<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q    = (string) $request->query('q', '');
        $tier = (string) $request->query('tier', '');

        $users = User::query()
            ->when($q, function ($query, $q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($tier, fn ($query) => $query->where('tier', $tier))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // quick-add user (UI button)
        $now   = now()->format('YmdHis');
        $email = "new+{$now}@example.test";

        $user = User::create([
            'name'              => 'New User',
            'email'             => $email,
            // relies on User::$casts['password']='hashed' (Laravel 10+) — otherwise use bcrypt(Str::password(12))
            'password'          => Str::password(12),
            'tier'              => 'user',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('status', "User #{$user->id} created");
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'tier'  => 'sometimes|required|in:user,paid,admin',
        ]);

        $user->fill($validated)->save();

        return back()->with('status', 'User updated.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors('You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('status', 'User deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'users_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'name', 'email', 'tier,', 'created_at']);
            User::orderBy('id')->chunk(500, function ($chunk) use ($out) {
                foreach ($chunk as $u) {
                    fputcsv($out, [$u->id, $u->name, $u->email, $u->tier, $u->created_at]);
                }
            });
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
