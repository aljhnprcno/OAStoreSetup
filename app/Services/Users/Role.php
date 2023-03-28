<?php
/**
 * File Role.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version
 */
namespace App\Services\Users;

use App\Services\Users\Acl;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Class Role
 *
 * @property Permission[] $permissions
 * @property string $name
 * @package App\Laravue\Models
 */
class Role extends SpatieRole
{
    /**
     * Check whether current role is admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->name === Acl::ROLE_ADMIN;
    }
}
