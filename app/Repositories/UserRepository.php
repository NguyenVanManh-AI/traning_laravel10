<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserInterface
{
    public function getModel()
    {
        return User::class;
    }

    // public function getAllUsers($filter)
    public static function getAllUsers($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
        // $data = $this->model
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('email', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('name', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(isset($filter->role), function ($query) use ($filter) { 
                if ($filter->role !== 'all') {
                    $query->where('users.role', $filter->role);
                }
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            });

        return $data;
    }
}

