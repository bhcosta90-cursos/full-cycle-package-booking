<?php

namespace Package\Entity;

use Package\Exception\EntityExpetion;

readonly class User
{
    public function __construct(
        private(set) string $id,
        private(set) string $name,
        private(set) string $email,
    ) {
        $this->validate($id, $name, $email);
    }

    protected function validate(string $id, string $name, string $email): void
    {
        if (empty(trim($name))) {
            throw new EntityExpetion('O nome do usuário não pode ser vázio');
        }

        if (empty(trim($id))) {
            throw new EntityExpetion('O nome do usuário não pode ser vázio');
        }

        if (empty(trim($email))) {
            throw new EntityExpetion('O e-mail do usuário não pode ser vázio');
        }
    }
}