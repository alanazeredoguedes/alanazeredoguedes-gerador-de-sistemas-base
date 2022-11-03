<?php

namespace App\Application\Project\ContentBundle\Controller\Base;

use App\Application\Project\ContentBundle\Service\AdminACL;
use App\Application\Project\ContentBundle\Service\ApiACL;
use App\Application\Project\ContentBundle\Service\WebACL;
use Sonata\AdminBundle\Controller\CRUDController;

class BaseAdminController extends CRUDController
{

    public function __construct(
       protected AdminACL $adminACL,
       protected ApiACL $apiACL,
       protected WebACL  $webACL
    )
    {}


    /**
     * Validate access as routes
     * @param string $actionName
     * @return void
     */
    public function validateAccess(string $actionName): void
    {
        if($this->isGranted("ROLE_SUPER_ADMIN"))
            return;

        $class = new \ReflectionClass($this);

        $roleValidate = '#_ERROR_#';

        foreach ($this->adminACL->getAdminGroupRoles() as $groupRoles) {

            if($class->getName() !== $groupRoles['controllerNamespace'])
                continue;

            foreach ($groupRoles['routes'] as $route){
                if($actionName !== $route['router'])
                    continue;

                $roleValidate = $route['role'];
            }

        }

        $this->denyAccessUnlessGranted($roleValidate);
    }

}