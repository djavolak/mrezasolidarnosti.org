<?php

use Skeletor\Form\InputGroup\InputGroup;
use Skeletor\Form\InputTypes\Input\Email;
use Skeletor\Form\InputTypes\Select\Collection\OptionCollection;
use Skeletor\Form\InputTypes\Select\Option;
use Skeletor\Form\InputTypes\Select\Select;
use Skeletor\Form\Renderer\TabbedFormRenderer;
use Skeletor\Form\Tab\Tab;
use Skeletor\Form\TabbedForm;

$form = new TabbedForm($data['formAction'], $data['dataAction'], $this->formTokenArray());

$email = (new Email('email', $data['model']?->email, 'Email', readOnly: $data['dataAction'] !== 'create'));

$isActiveOptions = [0 => 'Ne', 1 => 'Da'];
$isActiveSelected = $data['model'] ? (int) $data['model']->isActive : 1;
$isActiveCollection = (new OptionCollection(new Option('1', 'Da')))->fromArray($isActiveOptions, $isActiveSelected);
$isActiveSelect = (new Select('isActive', $isActiveCollection, 'Aktivan'));

$inputGroup = (new InputGroup())
    ->addInput($email)
    ->addInput($isActiveSelect);

$form->addTab((new Tab('Basic Info'))
    ->addInputGroup($inputGroup)
);

$formRenderer = new TabbedFormRenderer($form, $data['formTitle']);
?>
<?= $formRenderer->render() ?>
