<?php

namespace Innova\Types;
class FieldStorageSettings
{

    /**
     * @param array $settings
     */
    public function __construct(private array $settings)
    {
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    public function formSettings(): array {
        return $this->settings['display_form_settings'] ?? [];
    }
}