<?php

namespace Innova\Types;
use Innova\Entities\User;
use Innova\modules\CurrentUser;

class ContentTypeSettings
{

    private string $description;

    private User $owner;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getBundleName(): string
    {
        return $this->bundle_name;
    }

    private array $permissions;

    private string $contentTypeName;

    public function getContentTypeName(): string
    {
        return $this->contentTypeName;
    }

    public function getContentTypeLabel(): string
    {
        return $this->contentTypeLabel;
    }

    private string $contentTypeLabel;

    /**
     * @param string $bundle_name
     */
    public function __construct(string $bundle_name)
    {
        $this->contentTypeLabel = $bundle_name;
        $this->contentTypeName = strtolower(str_replace(' ','_', $bundle_name));
        $user = (new CurrentUser());
        if($user->id()) {
            $this->owner = User::load($user->id());
        }
    }

    public function setPermissions(array $permission): void {
        if(!empty($this->permissions)) {
            $this->permissions = array_merge($this->permissions, $permission);
        }else {
            $this->permissions = $permission;
        }
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

}