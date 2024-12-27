<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: Db.php
 * Created:   2024-12-26 02:17:39
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */


class Db
{
    public static function getInstance(): Db
    {
        return new self();
    }

    public function executeS(string $sql): mixed
    {
        return [];
    }
}
