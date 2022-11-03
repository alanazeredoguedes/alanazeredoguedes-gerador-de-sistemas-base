<?php

namespace App\Application\Internit\CursoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApplicationInternitCursoBundle extends Bundle
{
    /** {@inheritdoc} */
    public function getParent()
    {
        return 'ApplicationInternitCursoBundle';
    }
}