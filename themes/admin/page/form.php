<?php

use Skeletor\Form\InputGroup\InputGroup;
use Skeletor\Form\InputGroup\InputGroupWidth;
use Skeletor\Form\InputTypes\ContentEditor\ContentEditor;
use Skeletor\Form\InputTypes\File\Image;
use Skeletor\Form\InputTypes\Input\Text;
use Skeletor\Form\InputTypes\Select\Collection\OptionCollection;
use Skeletor\Form\InputTypes\Select\Select;
use Skeletor\Form\InputTypes\TextArea\TextArea;
use Skeletor\Form\Renderer\TabbedFormRenderer;
use Skeletor\Form\Tab\Tab;
use Skeletor\Form\TabbedForm;
use Skeletor\Page\Entity\Page;

$form = new TabbedForm($data['formAction'], $data['dataAction'], $this->formTokenArray());

$action = $data['dataAction'] === 'create' ? 'Create' : 'Edit';

$statusCollection = (new OptionCollection())->fromArray(Page::getHRStatuses(), $data['model']?->status);


$title = (new Text('title', $data['model']?->title, 'Title', 'Title'))
    ->required('Title is required')
    ->minLength(2, 'Title must be at least 2 characters');


$slug = (new Text('slug', $data['model']?->slug, 'Slug', 'Slug'));

$featuredImage = (
new Image(
    'featuredImageId',
    'Choose Image',
    'Featured Image',
    $data['model']?->featuredImage?->filename ? '/images' . $data['model']?->featuredImage?->filename : '',
    $data['model']?->featuredImage?->id,
));


$statusSelect = (new Select('status', $statusCollection, 'Status'))
    ->required('Status is required', $statusCollection->getDefaultOption()->getValue());

$isLoginProtectedCheckbox = (new \Skeletor\Form\InputTypes\Input\Checkbox('isLoginProtected', $data['model']?->isLoginProtected ?? 0, 'Login Protected'));

$languageInput = new \Skeletor\Form\InputTypes\Input\Hidden('languageCode', $data['model']?->languageCode ?? 'sr');

$groupOne = (new InputGroup(width: InputGroupWidth::FULL_WIDTH))
    ->addInput($title)
    ->addInput($slug)
    ->addInput($statusSelect)
    ->addInput($isLoginProtectedCheckbox)
    ->addInput($languageInput)
    ->addInput($featuredImage);

if ($data['model']?->blockData) {
    $content = $data['model']?->blockData ? json_encode($data['model']?->blockData) : json_encode([]);
}
$contentEditor = new ContentEditor($content ?? '');
$groupTwo = (new InputGroup(width: InputGroupWidth::FULL_WIDTH))
    ->addInput($contentEditor);

$seoTitle = (new Text('seoTitle', $data['model']?->seoTitle, 'SEO Title'))->required('SEO Title is required');
$seoDescription = (new Text('seoDescription', $data['model']?->seoDescription, 'SEO Description'))->required('SEO Description is required');
$seoImage = (new Image(
    'seoImageId',
    'Choose Image',
    'SEO Image',
    $data['model']?->seoImage?->filename ? '/images' . $data['model']?->seoImage?->filename : '',
    $data['model']?->seoImage?->id,
))->required('SEO Image is required');
$groupThree = (new InputGroup(width: InputGroupWidth::FULL_WIDTH))
    ->addInput($seoTitle)
    ->addInput($seoDescription)
    ->addInput($seoImage);

$basicInfoTab = (new Tab('Basic Information'))->addInputGroup($groupOne);
$contentTab = (new Tab('Content'))->addInputGroup($groupTwo);
$seoTab = (new Tab('SEO'))->addInputGroup($groupThree);

$form->addTab($basicInfoTab);
$form->addTab($contentTab);
$form->addTab($seoTab);


$formRenderer = new TabbedFormRenderer($form, $data['formTitle']);

?>
<?= $formRenderer->render() ?>