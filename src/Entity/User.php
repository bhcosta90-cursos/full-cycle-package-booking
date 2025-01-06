<?php

namespace Package\Entity;

use Package\Exception\EntityException;

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
            throw new EntityException('O nome do usuário não pode ser vázio');
        }

        if (empty(trim($id))) {
            throw new EntityException('O nome do usuário não pode ser vázio');
        }

        if (empty(trim($email))) {
            throw new EntityException('O e-mail do usuário não pode ser vázio');
        }
    }
}