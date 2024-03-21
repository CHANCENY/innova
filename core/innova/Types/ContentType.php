<?php

namespace Innova\Types;
use Innova\Configs\ConfigHandler;
use Ramsey\Uuid\Uuid;

class ContentType
{
    public array $contentType;

    public function __construct(string $content_type_name)
    {
        $this->contentType = ['bundle'=>null,'fields'=>null];
        if(empty(ContentType::load($content_type_name))) {
            $this->contentType['bundle'] = new ContentTypeSettings($content_type_name);
            $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField('Title',[
                'is_table'=>false,
                'type' => 'varchar',
                'size' => 255,
                'is_null' => false,
                'default' => 'ContentSettingsHandlers::defaultTitle',
                'display_form_settings' =>  [
                    'name' => 'title',
                    'required' => true,
                    'default_value' => '',
                    'placeholder' => 'Title',
                    'type' => 'text',
                    'label'=> [
                        'visible' => true,
                        'text' => 'Title'
                    ]
                ]

            ]);
            $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField('Body',[
                'is_table'=>false,
                'type' => 'text',
                'is_null' => true,
                'display_form_settings' =>  [
                    'name' => 'body',
                    'required' => true,
                    'default_value' => '',
                    'placeholder' => 'Enter body',
                    'type' => 'text',
                    'label'=> [
                        'visible' => true,
                        'text' => 'Body'
                    ]
                ]
            ]);
            $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField('Author By',[
                'is_table'=>false,
                'type' => 'int',
                'size'=> 11,
                'is_null' => false,
                'default_value' => 'ContentSettingsHandlers::authorDefault',
                'display_form_settings' =>  [
                    'name' => 'author',
                    'required' => true,
                    'readonly' =>true,
                    'default_value' => 'ContentSettingsHandlers::author',
                    'placeholder' => 'Author',
                    'type' => 'text',
                    'label'=> [
                        'visible' => true,
                        'text' => 'Author'
                    ]
                ]
            ]);
            $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField('Created on',[
                'is_table'=>false,
                'type' => 'timestamp',
                'is_null' => false,
                'default' => 'ContentSettingsHandlers::createOnSettings',
                'display_form_settings' =>  [
                    'name' => 'created',
                    'required' => true,
                    'readonly' =>true,
                    'default_value' => 'ContentSettingsHandlers::createdOnDefaultValue',
                    'placeholder' => 'Author on',
                    'type' => 'date',
                    'label'=> [
                        'visible' => true,
                        'text' => 'Author on'
                    ],
                ]
            ]);
            $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField('Updated on',[
                'is_table'=>false,
                'type' => 'timestamp',
                'is_null' => false,
                'default' => 'ContentSettingsHandlers::updatedOnSettings',
                'display_form_settings' =>  [
                    'name' => 'updated',
                    'required' => true,
                    'readonly' =>true,
                    'default_value' => 'ContentSettingsHandlers::updatedOnDefaultValue',
                    'placeholder' => 'Updated on',
                    'type' => 'date',
                    'label'=> [
                        'visible' => true,
                        'text' => 'Updated on'
                    ],
                ]
            ]);
            $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField('status',[
                'is_table'=>false,
                'type' => 'boolean',
                'is_null' => true,
                'default' => 'ContentSettingsHandlers::publishedSettings',
                'display_form_settings' =>  [
                    'name' => 'publish',
                    'default_value' => 'ContentSettingsHandlers::checkboxDefaultValue',
                    'placeholder' => 'Publish',
                    'type' => 'date',
                    'label'=> [
                        'visible' => true,
                        'text' => 'Publish'
                    ],
                ]
            ]);
        }else {
            throw new ContentTypeAlreadyExistException();
        }
    }

    public function setDescription(string $description): void
    {
        if($this->contentType['bundle'] instanceof ContentTypeSettings) {
            $this->contentType['bundle']->setDescription($description);
        }
    }

    public function setPermission(array $permission): void
    {
        if($this->contentType['bundle'] instanceof ContentTypeSettings) {
            $this->contentType['bundle']->setPermissions($permission);
        }
    }

    public function setField(string $field_name, array $settings):void
    {
        $this->contentType['fields'][] = (new FieldsDefinitionsConfiguration($this))->newField($field_name, $settings);
    }

    public function save(): int {
        $serialized = serialize($this);
        $base64 = base64_encode($serialized);
        $configs = ConfigHandler::config('content_configuration');
        $flag = 0;
        if(!empty($configs)) {
            foreach ($configs as $key=>$value) {
                if(!empty($value['key']) && $value['key'] === $this->contentType['bundle']->getContentTypeName()) {
                    $configs[$key]['settings'] = $base64;
                    ConfigHandler::createBackUp('content_configuration');
                    ConfigHandler::configRemove('content_configuration');
                    return ConfigHandler::create('content_configuration',$configs);
                }
            }
        }
        $configs[] = [
            'key' => $this->contentType['bundle']->getContentTypeName(),
            'settings' => $base64,
        ];
        ConfigHandler::createBackUp('content_configuration');
        ConfigHandler::configRemove('content_configuration');
        return ConfigHandler::create('content_configuration',$configs);
    }

    public static function load(string $content_type_name): ContentType|null
    {
        $config = ConfigHandler::config('content_configuration');
        if(!empty($config)) {
            $loaded = array_filter($config, function ($item) use ($content_type_name){
                if(!empty($item['key']) && $item['key'] === strtolower(str_replace(' ','_',$content_type_name))) {
                    return $item;
                }else {
                    return null;
                }
            });

            $loaded = array_filter($loaded, 'count');
            if(!empty($loaded[0])) {
                $contentType = unserialize(base64_decode($loaded[0]['settings']));
                if($contentType instanceof ContentType) {
                    return $contentType;
                }
            }
        }
        return null;
    }

}