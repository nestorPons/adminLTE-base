<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class Users extends Component
{
    use WithPagination;
    const UPDATE = 'update', STORE = 'store';
    protected $paginationTheme = 'bootstrap';
    public
        $roles,
        $permission,
        $selected_id,
        $keyWord,
        $name,
        $email,
        $two_factor_secret,
        $two_factor_recovery_codes,
        $current_team_id,
        $profile_photo_path;

    public function render()
    {
    
        $user = Auth::user();
        if ($user->hasRole('super')) {
            // Usuario es 'super', puede ver todos los roles
            $this->roles = Role::all();
        } elseif ($user->hasRole('admin')) {
            // Usuario es 'admin', puede ver todos los roles excepto 'super'
            $this->roles = Role::where('name', '!=', 'super')->get();
        } elseif ($user->hasRole('user')) {
            // Usuario es 'user', puede ver 'user' y 'client'
            $this->roles = Role::whereIn('name', ['user', 'client'])->get();
        } else {
            // Por defecto, o en caso de que no cumpla ninguna de las condiciones anteriores
            $this->roles = collect(); // Devuelve una colección vacía
        }
        $keyWord = '%' . $this->keyWord . '%';

        // Obtenemos el rol del usuario autenticado
        $userRole = $user->roles->pluck('name')->first(); // Asumiendo que un usuario tiene un solo rol

        return view('livewire.users.view', [
            'users' => User::latest()
                ->where(function ($query) use ($keyWord) {
                    $query->orWhere('name', 'LIKE', $keyWord)
                        ->orWhere('email', 'LIKE', $keyWord)
                        ->orWhere('two_factor_secret', 'LIKE', $keyWord)
                        ->orWhere('two_factor_recovery_codes', 'LIKE', $keyWord)
                        ->orWhere('current_team_id', 'LIKE', $keyWord)
                        ->orWhere('profile_photo_path', 'LIKE', $keyWord);
                })
                ->when($userRole !== 'super', function ($query) use ($user, $userRole) {
                    // Aplicamos la condición correcta para el rol 'admin'
                    if ($userRole === 'admin') {
                        // Excluimos usuarios con rol 'super'
                        $query->whereDoesntHave('roles', function ($subQuery) {
                            $subQuery->where('name', 'super');
                        });
                    } else {
                        // Para otros roles que no son 'super' o 'admin', limitamos a su propio usuario
                        $query->where('id', $user->id);
                    }
                })
                ->paginate(15),
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->name = null;
        $this->email = null;
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->current_team_id = null;
        $this->profile_photo_path = null;
    }

    public function save($action)
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required',
            'permission' => 'required',
        ]);
        //TODO :: edicion de los permisos si se tiene un permiso igual o superior
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'two_factor_secret' => $this->two_factor_secret,
            'two_factor_recovery_codes' => $this->two_factor_recovery_codes,
            'current_team_id' => $this->current_team_id,
            'profile_photo_path' => $this->profile_photo_path
        ];
        if ($this->selected_id) {
            $record = User::find($this->selected_id);
            $action == self::UPDATE ? $record->update($data) : $record->create($data);
            $role = Role::where('id', $this->permission)->first();
            $record->syncRoles([$role]); // Asigna el nuevo role al usuario

            $this->resetInput();
            $this->dispatch('closeModal');
            $this->dispatch('showMessage', title:__('Saved'), type: 'success');
        }
    }
    public function store()
    {
        $this->save(self::STORE);
    }

    public function update()
    {
        $this->save(self::UPDATE);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->two_factor_secret = $user->two_factor_secret;
        $this->two_factor_recovery_codes = $user->two_factor_recovery_codes;
        $this->current_team_id = $user->current_team_id;
        $this->profile_photo_path = $user->profile_photo_path;
        $this->permission = $user->getRole()->id ?? null;
    }

    public function destroy($id)
    {
        if ($id) {
            User::where('id', $id)->delete();
        }
    }

   
}
