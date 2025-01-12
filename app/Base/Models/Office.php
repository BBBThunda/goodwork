<?php

namespace App\Base\Models;

use App\Base\Contracts\HasMembers;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Office extends Group implements HasMembers
{
    protected $type = 'office';

    protected $fillable = ['name', 'description', 'owner_id', 'public'];

    /**
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(\App\Base\Models\User::class, 'office_user', 'office_id', 'user_id');
    }

    public function cycles()
    {
        return $this->morphMany(Cycle::class, 'cyclable');
    }

    public function permissions()
    {
        return $this->morphToMany(Permission::class, 'group', 'role_has_permission');
    }

    public function roles()
    {
        return $this->morphToMany(Role::class, 'group', 'role_has_permission');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function settings()
    {
        return $this->hasOne(OfficeSetting::class, 'office_id')->withDefault();
    }

    public function makePublic()
    {
        $this->update(['public' => true]);
    }

    public function makePrivate()
    {
        $this->update(['public' => false]);
    }
}
