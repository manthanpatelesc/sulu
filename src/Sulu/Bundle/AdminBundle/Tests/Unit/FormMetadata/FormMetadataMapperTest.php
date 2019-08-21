<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\AdminBundle\Tests\Unit\FormMetadata;

use PHPUnit\Framework\TestCase;
use Sulu\Bundle\AdminBundle\FormMetadata\FormMetadata as ExternalFormMetadata;
use Sulu\Bundle\AdminBundle\FormMetadata\FormMetadataMapper;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\FieldMetadata;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\FormMetadata;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\SectionMetadata;
use Sulu\Bundle\AdminBundle\Metadata\SchemaMetadata\SchemaMetadata;
use Sulu\Component\Content\Metadata\BlockMetadata;
use Sulu\Component\Content\Metadata\ComponentMetadata;
use Sulu\Component\Content\Metadata\PropertyMetadata;
use Sulu\Component\Content\Metadata\SectionMetadata as ExternalSectionMetadata;

class FormMetadataMapperTest extends TestCase
{
    /**
     * @var FormMetadataMapper
     */
    private $formMetadataMapper;

    public function setUp()
    {
        $this->formMetadataMapper = new FormMetadataMapper();
    }

    public function testMapPropertiesEnglish()
    {
        $form = $this->createFormWithBasicProperties();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'en'));

        $this->assertCount(3, $newForm->getItems());
        $this->assertContains('property1', array_keys($newForm->getItems()));
        $this->assertContains('property2', array_keys($newForm->getItems()));
        $this->assertContains('property3', array_keys($newForm->getItems()));

        $newProperty1 = $newForm->getItems()['property1'];
        $this->assertInstanceOf(FieldMetadata::class, $newProperty1);
        $this->assertEquals('property1', $newProperty1->getName());
        $this->assertEquals('text_line', $newProperty1->getType());
        $this->assertEquals('English', $newProperty1->getLabel());
        $this->assertEquals('Description', $newProperty1->getDescription());
        $this->assertEquals('disabledCondition', $newProperty1->getDisabledCondition());
        $this->assertEquals('visibleCondition', $newProperty1->getVisibleCondition());
        $this->assertEquals(6, $newProperty1->getColSpan());
        $this->assertEquals(true, $newProperty1->isRequired());
        $this->assertEquals(10, $newProperty1->getSpaceAfter());
        $this->assertEquals('onInvalid', $newProperty1->getOnInvalid());
    }

    public function testMapPropertiesGerman()
    {
        $form = $this->createFormWithBasicProperties();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'de'));

        $this->assertCount(3, $newForm->getItems());
        $this->assertContains('property1', array_keys($newForm->getItems()));
        $this->assertContains('property2', array_keys($newForm->getItems()));
        $this->assertContains('property3', array_keys($newForm->getItems()));

        $property1 = $newForm->getItems()['property1'];
        $this->assertInstanceOf(FieldMetadata::class, $property1);
        $this->assertEquals('property1', $property1->getName());
        $this->assertEquals('text_line', $property1->getType());
        $this->assertEquals('Deutsch', $property1->getLabel());
        $this->assertEquals('Beschreibung', $property1->getDescription());
        $this->assertEquals('disabledCondition', $property1->getDisabledCondition());
        $this->assertEquals('visibleCondition', $property1->getVisibleCondition());
        $this->assertEquals(6, $property1->getColSpan());
        $this->assertEquals(true, $property1->isRequired());
        $this->assertEquals(10, $property1->getSpaceAfter());
        $this->assertEquals('onInvalid', $property1->getOnInvalid());
    }

    public function testMapPropertiesWithTags()
    {
        $form = $this->createFormWithAdvancedProperty();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'en'));

        $this->assertCount(1, $newForm->getItems());
        $this->assertContains('name', array_keys($newForm->getItems()));

        $item = $newForm->getItems()['name'];
        $this->assertInstanceOf(FieldMetadata::class, $item);

        $this->assertEquals('sulu.tag.1', $item->getTags()[0]->getName());
        $this->assertEquals(10, $item->getTags()[0]->getPriority());

        $this->assertEquals('sulu.tag.2', $item->getTags()[1]->getName());
        $this->assertEquals(20, $item->getTags()[1]->getPriority());
    }

    public function testMapPropertiesWithParametersEnglish()
    {
        $form = $this->createFormWithAdvancedProperty();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'en'));

        $this->assertCount(1, $newForm->getItems());
        $this->assertContains('name', array_keys($newForm->getItems()));

        $item = $newForm->getItems()['name'];
        $this->assertInstanceOf(FieldMetadata::class, $item);

        $this->assertCount(2, $item->getOptions());

        $this->assertEquals('label', $item->getOptions()['label']->getName());
        $this->assertEquals('string', $item->getOptions()['label']->getType());
        $this->assertEquals(1, $item->getOptions()['label']->getValue());
        $this->assertEquals('LabelEnglish', $item->getOptions()['label']->getTitle());
        $this->assertNull($item->getOptions()['label']->getPlaceholder());
        $this->assertNull($item->getOptions()['label']->getInfoText());

        $this->assertEquals('form_options', $item->getOptions()['form_options']->getName());
        $this->assertEquals('collection', $item->getOptions()['form_options']->getType());
        $this->assertEquals('webspace', $item->getOptions()['form_options']->getValue()[0]->getName());
        $this->assertEquals(10, $item->getOptions()['form_options']->getValue()[0]->getValue());
        $this->assertNull($item->getOptions()['form_options']->getValue()[0]->getType());
        $this->assertNull($item->getOptions()['form_options']->getValue()[0]->getPlaceholder());
        $this->assertNull($item->getOptions()['form_options']->getValue()[0]->getInfoText());
    }

    public function testMapPropertiesWithParametersGerman()
    {
        $form = $this->createFormWithAdvancedProperty();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'de'));

        $this->assertCount(1, $newForm->getItems());
        $this->assertContains('name', array_keys($newForm->getItems()));

        $item = $newForm->getItems()['name'];
        $this->assertInstanceOf(FieldMetadata::class, $item);

        $this->assertCount(2, $item->getOptions());

        $this->assertEquals('label', $item->getOptions()['label']->getName());
        $this->assertEquals('string', $item->getOptions()['label']->getType());
        $this->assertEquals(1, $item->getOptions()['label']->getValue());
        $this->assertEquals('LabelDeutsch', $item->getOptions()['label']->getTitle());
    }

    public function testMapChildrenWithSection()
    {
        $form = $this->createFormWithSection();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'en'));

        $this->assertCount(2, $newForm->getItems());
        $this->assertContains('section', array_keys($newForm->getItems()));
        $this->assertContains('property4', array_keys($newForm->getItems()));

        $section = $newForm->getItems()['section'];
        $this->assertInstanceOf(SectionMetadata::class, $section);
        $this->assertEquals('section', $section->getType());
        $this->assertEquals('section', $section->getName());

        $this->assertEquals('property1', $section->getItems()['property1']->getName());
        $this->assertEquals('text_line', $section->getItems()['property1']->getType());

        $this->assertEquals('property2', $section->getItems()['property2']->getName());
        $this->assertEquals('text_area', $section->getItems()['property2']->getType());

        $this->assertEquals('property3', $section->getItems()['property3']->getName());
        $this->assertEquals('checkbox', $section->getItems()['property3']->getType());
    }

    public function testMapChildrenWithBlockEnglish()
    {
        $form = $this->createFormWithBlock();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'en'));

        $this->assertCount(1, $newForm->getItems());
        $this->assertContains('block', array_keys($newForm->getItems()));

        $block = $newForm->getItems()['block'];
        $this->assertInstanceOf(FieldMetadata::class, $block);
        $this->assertEquals('block', $block->getType());
        $this->assertEquals('component1', $block->getDefaultType());

        $this->assertEquals('component1', $block->getTypes()['component1']->getName());
        $this->assertEquals('First Component', $block->getTypes()['component1']->getTitle());
        $this->assertCount(2, $block->getTypes()['component1']->getItems());
        $this->assertContains('property1', array_keys($block->getTypes()['component1']->getItems()));
        $this->assertContains('property2', array_keys($block->getTypes()['component1']->getItems()));

        $this->assertEquals('component2', $block->getTypes()['component2']->getName());
        $this->assertEquals('Second Component', $block->getTypes()['component2']->getTitle());
        $this->assertCount(2, $block->getTypes()['component2']->getItems());
        $this->assertContains('property3', array_keys($block->getTypes()['component2']->getItems()));
        $this->assertContains('property4', array_keys($block->getTypes()['component2']->getItems()));
    }

    public function testMapChildrenWithBlockGerman()
    {
        $form = $this->createFormWithBlock();

        $newForm = new FormMetadata();
        $newForm->setItems($this->formMetadataMapper->mapChildren($form->getChildren(), 'de'));

        $this->assertCount(1, $newForm->getItems());
        $this->assertContains('block', array_keys($newForm->getItems()));

        $block = $newForm->getItems()['block'];
        $this->assertInstanceOf(FieldMetadata::class, $block);
        $this->assertEquals('block', $block->getType());
        $this->assertEquals('component1', $block->getDefaultType());

        $this->assertEquals('component1', $block->getTypes()['component1']->getName());
        $this->assertEquals('Erste Komponente', $block->getTypes()['component1']->getTitle());
        $this->assertCount(2, $block->getTypes()['component1']->getItems());
        $this->assertContains('property1', array_keys($block->getTypes()['component1']->getItems()));
        $this->assertContains('property2', array_keys($block->getTypes()['component1']->getItems()));

        $this->assertEquals('component2', $block->getTypes()['component2']->getName());
        $this->assertEquals('Zweite Komponente', $block->getTypes()['component2']->getTitle());
        $this->assertCount(2, $block->getTypes()['component2']->getItems());
        $this->assertContains('property3', array_keys($block->getTypes()['component2']->getItems()));
        $this->assertContains('property4', array_keys($block->getTypes()['component2']->getItems()));
    }

    public function testMapSchema()
    {
        $form = $this->createFormWithRequiredProperties();

        $schema = $this->formMetadataMapper->mapSchema($form->getChildren());

        $this->assertInstanceOf(SchemaMetadata::class, $schema);
        $this->assertEquals([
            'required' => [
                'property1',
                'property2',
                'property3',
            ],
        ], $schema->toJsonSchema());
    }

    private function createFormWithBasicProperties(): ExternalFormMetadata
    {
        $form = new ExternalFormMetadata();
        $property1 = new PropertyMetadata('property1');
        $property1->setType('text_line');
        $property1->setTitles([
            'en' => 'English',
            'de' => 'Deutsch',
        ]);
        $property1->setDescriptions([
            'en' => 'Description',
            'de' => 'Beschreibung',
        ]);

        $property1->setDisabledCondition('disabledCondition');
        $property1->setVisibleCondition('visibleCondition');
        $property1->setColSpan(6);
        $property1->setRequired(true);
        $property1->setSpaceAfter(10);
        $property1->setOnInvalid('onInvalid');

        $property2 = new PropertyMetadata('property2');
        $property2->setType('text_area');
        $property3 = new PropertyMetadata('property3');
        $property3->setType('checkbox');
        $form->addChild($property1);
        $form->addChild($property2);
        $form->addChild($property3);

        return $form;
    }

    private function createFormWithAdvancedProperty(): ExternalFormMetadata
    {
        $form = new ExternalFormMetadata();
        $property = new PropertyMetadata('name');
        $property->setType('type');
        $property->setTags([
            'tag1' => [
                'name' => 'sulu.tag.1',
                'priority' => 10,
            ],
            'tag2' => [
                'name' => 'sulu.tag.2',
                'priority' => 20,
            ],
        ]);

        $property->setParameters([
            'param1' => [
                'name' => 'label',
                'type' => 'string',
                'meta' => [
                    'title' => [
                        'de' => 'LabelDeutsch',
                        'en' => 'LabelEnglish',
                    ],
                    'info_text' => [],
                    'placeholder' => [],
                ],
                'value' => 1,
            ],
            'param2' => [
                'name' => 'form_options',
                'type' => 'collection',
                'meta' => [],
                'value' => [
                    'value1' => [
                        'name' => 'webspace',
                        'value' => 10,
                    ],
                ],
            ],
        ]);

        $form->addChild($property);

        return $form;
    }

    private function createFormWithSection(): ExternalFormMetadata
    {
        $form = new ExternalFormMetadata();
        $section = new ExternalSectionMetadata('section');

        $property1 = new PropertyMetadata('property1');
        $property1->setType('text_line');
        $property2 = new PropertyMetadata('property2');
        $property2->setType('text_area');
        $property3 = new PropertyMetadata('property3');
        $property3->setType('checkbox');
        $property4 = new PropertyMetadata('property4');
        $property4->setType('type');

        $section->addChild($property1);
        $section->addChild($property2);
        $section->addChild($property3);

        $form->addChild($section);
        $form->addChild($property4);

        return $form;
    }

    private function createFormWithBlock(): ExternalFormMetadata
    {
        $form = new ExternalFormMetadata();
        $block = new BlockMetadata('block');

        $component1 = new ComponentMetadata('component1');
        $component1->setTitles([
            'en' => 'First Component',
            'de' => 'Erste Komponente',
        ]);

        $property1 = new PropertyMetadata('property1');
        $property1->setType('text_line');
        $property2 = new PropertyMetadata('property2');
        $property2->setType('text_area');

        $component1->addChild($property1);
        $component1->addChild($property2);

        $component2 = new ComponentMetadata('component2');
        $component2->setTitles([
            'en' => 'Second Component',
            'de' => 'Zweite Komponente',
        ]);

        $property3 = new PropertyMetadata('property3');
        $property3->setType('checkbox');
        $property4 = new PropertyMetadata('property4');
        $property4->setType('type');

        $component2->addChild($property3);
        $component2->addChild($property4);

        $block->addComponent($component1);
        $block->addComponent($component2);
        $block->defaultComponentName = 'component1';
        $block->setType('block');

        $form->addChild($block);

        return $form;
    }

    private function createFormWithRequiredProperties(): ExternalFormMetadata
    {
        $form = new ExternalFormMetadata();

        $property1 = new PropertyMetadata('property1');
        $property1->setType('text_line');
        $property1->setRequired(true);
        $property2 = new PropertyMetadata('property2');
        $property2->setType('text_area');
        $property2->setRequired(true);
        $property3 = new PropertyMetadata('property3');
        $property3->setType('checkbox');
        $property3->setRequired(true);
        $property4 = new PropertyMetadata('property4');
        $property4->setType('type');
        $property4->setRequired(false);

        $form->addChild($property1);
        $form->addChild($property2);
        $form->addChild($property3);
        $form->addChild($property4);

        return $form;
    }
}