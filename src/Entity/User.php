<?php

namespace Package\Entity;

use Package\Exception\EntityExpetion;

readonly class User
{
    public function __construct(
        private(set) string $id,
        private(set) string $name,
    ) {
        $this->validate($id, $name);
    }

    protected function validate(string $id, string $name): void
    {
        if (empty(trim($name))) {
            throw new EntityExpetion('O nome do usuário não pode ser vázio');
        }

        if (empty(trim($id))) {
            throw new EntityExpetion('O nome do usuário não pode ser vázio');
        }
    }
}