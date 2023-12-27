<?php

namespace Innova\interfaces;
interface DashboardInterface
{
    public function usersCount(): int;

    public function routeCount(): int;

    public function storageCount(): int;

    public function fileCount(): int;

    public function authentication(): string;

    public function csrfSecurity(): bool;

    public function mailSetting(): bool;

    public function errorSetting(): bool;

    public function errorCount(): int;

    public function modulesCount(): int;

    public function administrators(): array;
}