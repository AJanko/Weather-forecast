<?php

namespace App\Entity;

interface ModelEntityInterface
{
    public function getTarget();

    public function getSamplesArray(): array;

    public function getTimestamp(): int;
}
