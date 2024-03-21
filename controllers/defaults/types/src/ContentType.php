<?php
namespace Innova\Controller\routers\types\src;

use Innova\Types\ContentTypeStorage;
use Innova\Types\Fields\ActionButtons;
use Innova\Types\Fields\FieldWrapper;
use Innova\Types\Fields\WrapperForm;
use Innova\Types\FieldsDefinitionsConfiguration;
use Innova\Types\FieldStorageSettings;

use Innova\Types\ContentSettingsHandlers;

class ContentType
{

    public function page(): mixed {
        $storage = new ContentTypeStorage('articles');
        $storage->query();
        return "<h2>Chance Nyasulu</h2>";
    }

}