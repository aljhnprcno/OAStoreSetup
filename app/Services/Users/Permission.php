<?php
/**
 * File Permission.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */
namespace App\Services\Users;

use App\Services\Base\Scopes\OrderByCreatedAtScope;
use App\Services\Users\Acl;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Class Permission
 *
 * @package App\Laravue\Models
 */
class Permission extends SpatiePermission
{
    /**
     * To exclude permission management from the list
     *
     * @param $query
     * @return Builder
     */
    public function scopeAllowed($query)
    {
        return $query->where('name', '!=', Acl::PERMISSION_VIEW_MENU_USERS);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByCreatedAtScope);
    }
}
