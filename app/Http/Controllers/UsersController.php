<?php

namespace App\Http\Controllers;

use App\User;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\MsgTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    use MsgTrait;

    public function index()
    {
        if (! Auth::user()->owner) {
            return redirect()->route('dashboard')->with('error', 'Доступ сотрудникам запрещен!!!');;
        }

        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => User::orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->get()
                ->transform(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'owner' => $user->owner,
                        'deleted_at' => $user->deleted_at,
                    ];
                }),
        ]);
    }

    public function create()
    {
        if (! Auth::user()->owner) {
            return redirect()->route('dashboard')->with('error', 'Доступ сотрудникам запрещен!!!');
        }
        return Inertia::render('Users/Create');
    }

    public function store()
    {
        $messages = [
            'required' => 'Ошибка. Поле обязательное.',
            'max' => 'Ошибка. Поле должно содержать не более :max символов',
            'email' => 'Ошибка. E-mail написан не правильно',
            'unique' => 'Ошибка. Значение должно быть уникальным. Такое значение уже есть.',
        ];
        $validator = Validator::make(Request::all(), [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email:rfc,dns', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
        ], $messages);

        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }

        User::create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),

        ]);

        return Redirect::route('users')->with('success', $this->msgInsert());
    }

    public function edit(User $user)
    {
        if (! Auth::user()->owner && Auth::user()->id !== $user->id)  {
            return redirect()->route('dashboard')->with('error', 'Доступ сотрудникам запрещен!!!');;
        }
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'owner' => $user->owner,
                'deleted_at' => $user->deleted_at,
            ],
        ]);
    }

    public function update(User $user)
    {
        $messages = [
            'required' => 'Ошибка. Поле обязательное.',
            'max' => 'Ошибка. Поле должно содержать не более :max символов',
            'email' => 'Ошибка. E-mail написан не правильно',
            'unique' => 'Ошибка. Значение должно быть уникальным. Такое значение уже есть.',
        ];
        $validator = Validator::make(Request::all(), [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email:rfc,dns', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
        ], $messages);

        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user->update(Request::only('first_name', 'last_name', 'email', 'owner'));

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', $this->msgUpdate());
    }

    public function destroy(User $user)
    {
        if (! Auth::user()->owner) {
            return redirect()->route('dashboard')->with('error', 'Доступ сотрудникам запрещен!!!');
        }

		try{

            if ($user->isAdminUser()) {
                return Redirect::back()->with('error', $this->msgErrorAdminUser());
            }

            DB::beginTransaction();
            $user = User::findOrFail($user->id);
            $user->delete();
			DB::commit();

            $message = $this->msgDelete();

		} catch(\Exception $e){

			DB::rollback();
            $message = $this->msgError() .   $e->getMessage();

            return Redirect::route('users')->with('error',$message);

		}

        return Redirect::route('users')->with('success',$message);
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', $this->msgRestore());
    }
}
