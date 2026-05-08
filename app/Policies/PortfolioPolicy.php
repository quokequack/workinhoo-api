<?php

namespace App\Policies;

use App\Models\Prestador\Portfolio;
use App\Models\Usuario\Usuario;

class PortfolioPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario, Portfolio $portfolio): bool
    {
        return $portfolio->prestador->usuario_id === $usuario->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, Portfolio $portfolio): bool
    {
        return $portfolio->prestador->usuario_id === $usuario->id;
    }
}
