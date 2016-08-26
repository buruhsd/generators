<?php

namespace Devlob\Generators\Commands;

use Illuminate\Console\Command;
use File;

class ViewCommand extends Command
{
    protected $signature = 'devlob:view
                            {name : Entity name. Example "Test" without quotes.}
                            {nameSingularLower : Example test}
                            {namePlural : Example Tests}
                            {namePluralLower : Example tests}
                            {fields : Fields.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create views';

    protected $viewDirectoryPath = '';

    protected $availableFields = [
        'text' => 'text',
        'textarea' => 'textarea',
        'number' => 'number',
        'date' => 'date',
        'datetime' => 'datetime',
        'file' => 'file',
        'checkbox' => 'checkbox',
        'radio' => 'radio',
        'email' => 'email',
        'password' => 'password',
        'url' => 'url',
        'select' => 'select'
    ];

    protected $name; //Test
    protected $nameSingularLower; //test
    protected $namePlural; //Tests
    protected $namePluralLower; //tests

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->viewDirectoryPath = __DIR__ . '/../Stubs/';
        // Initialize all the variables
        $this->initializeVariables($this->argument('name'));

        $viewDirectory = config('view.paths')[0];

        $path = $viewDirectory . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->namePluralLower . DIRECTORY_SEPARATOR;

        $fields = $this->argument('fields');

        if ($fields) {
            $fieldsArray = explode(',', $fields);
            $counter = 0;

            foreach ($fieldsArray as $field) {
                // Split for example age:number to 'age' and 'number'
                $itemArray = explode(':', $field);

                // Create an array of value pairs for the fields
                $formFields[$counter]['name'] = trim($itemArray[0]);
                $formFields[$counter]['type'] = trim($itemArray[1]);

                $counter++;
            }
            
            $formFieldsHtml = '';
            foreach ($formFields as $field) {
                if($field['name'] !== 'slug')
                    $formFieldsHtml .= $this->createField($field);
            }

            // Form fields and label
            $formHeadingHtml = '';
            $formBodyHtml = '';
            $formBodyHtmlForShowView = '';
            $formBodyHtmlForToggle = '';

            $counter = 1;
            foreach ($formFields as $key => $value) {
                $field = $value['name'];
                $label = ucwords(str_replace('_id', '', $field));
                $formHeadingHtml .= '<th>' . $label . '</th>' . PHP_EOL;

                if(strpos($field, '_id') !== false) {
                    $formBodyHtmlForShowView .=
                        '<div class="form-group"><b>' . $label .
                        ':</b> {!! $%%nameSingularLower%%->' . str_replace('_id', '', $field) . '->' . str_replace('_id', '', $field) . ' !!}</div>';
                }else{
                    $formBodyHtmlForShowView .=
                        '<div class="form-group"><b>' . $label .
                        ':</b> {!! $%%nameSingularLower%%->' . $field . ' !!}</div>';
                }
                
                

                if(strpos($field, '_id') !== false) {
                    $element = str_replace('_id', '', $field);
                    
                    $formBodyHtml .= '{data: \'' . $element . '.' . $element . '\', name: \'' . $element . '.' . $element . '\'},' . PHP_EOL;
                }
                else
                    $formBodyHtml .= '{data: \'' . $field . '\', name: \'' . $field . '\'},' . PHP_EOL;

                $formBodyHtmlForToggle .= '<a class="toggle-vis" data-column="' . $counter . '">' . $field . '</a> - ' . PHP_EOL;

                $counter++;
            }

            $indexReplace = [
                '%%formBodyHtml%%' => substr_replace($formBodyHtml, '', -2),
                '%%name%%' => $this->name,
                '%%nameSingularLower%%' => $this->nameSingularLower,
                '%%namePlural%%' => $this->namePlural,
                '%%namePluralLower%%' => $this->namePluralLower,
            ];

            // For index.blade.php file
            $indexFile = $this->viewDirectoryPath . 'index.blade.stub';
            $newIndexFile = $path . 'index.blade.php';
            if (!File::copy($indexFile, $newIndexFile)) {
                echo "failed to copy $indexFile...\n";
            } else {
                File::put($newIndexFile, str_replace(array_keys($indexReplace), $indexReplace, File::get($newIndexFile)));
            }

            $createReplace = [
                '%%namePluralLower%%' => $this->namePluralLower,
            ];

            // For create.blade.php file
            $createFile = $this->viewDirectoryPath . 'create.blade.stub';
            $newCreateFile = $path . 'create.blade.php';
            if (!File::copy($createFile, $newCreateFile)) {
                echo "failed to copy $createFile...\n";
            } else {
                File::put($newCreateFile, str_replace(array_keys($createReplace), $createReplace, File::get($newCreateFile)));
            }

            $editReplace = [
                '%%nameSingularLower%%' => $this->nameSingularLower,
                '%%namePluralLower%%' => $this->namePluralLower,
            ];

            // For edit.blade.php file
            $editFile = $this->viewDirectoryPath . 'edit.blade.stub';
            $newEditFile = $path . 'edit.blade.php';
            if (!File::copy($editFile, $newEditFile)) {
                echo "failed to copy $editFile...\n";
            } else {
                File::put($newEditFile, str_replace(array_keys($editReplace), $editReplace, File::get($newEditFile)));
            }

            $formReplace = [
                '%%formFieldsHtml%%' => $formFieldsHtml
            ];

            // For form.blade.php file
            $formFile = $this->viewDirectoryPath . 'form.blade.stub';
            $newFormFile = $path . 'form.blade.php';
            if (!File::copy($formFile, $newFormFile)) {
                echo "failed to copy $formFile...\n";
            } else {
                File::put($newFormFile, str_replace(array_keys($formReplace), $formReplace, File::get($newFormFile)));
            }

            $tableReplace = [
                '%%formBodyHtmlForToggle%%' => substr_replace($formBodyHtmlForToggle, '', -2),
                '%%formHeadingHtml%%' => $formHeadingHtml,
                '%%name%%' => $this->name,
                '%%namePlural%%' => $this->namePlural,
                '%%namePluralLower%%' => $this->namePluralLower,
            ];

            // For table.blade.php file
            $tableFile = $this->viewDirectoryPath . 'table.blade.stub';
            $newTableFile = $path . 'table.blade.php';
            if (!File::copy($tableFile, $newTableFile)) {
                echo "failed to copy $tableFile...\n";
            } else {
                File::put($newTableFile, str_replace(array_keys($tableReplace), $tableReplace, File::get($newTableFile)));
            }

            $showReplace = [
                '%%formBodyHtml%%' => $formBodyHtmlForShowView,
                '%%nameSingularLower%%' => $this->nameSingularLower
            ];

            // For show.blade.php file
            $showFile = $this->viewDirectoryPath . 'show.blade.stub';
            $newShowFile = $path . 'show.blade.php';
            if (!File::copy($showFile, $newShowFile)) {
                echo "failed to copy $showFile...\n";
            } else {
                File::put($newShowFile, str_replace(array_keys($showReplace), $showReplace, File::get($newShowFile)));
            }

            // For layouts/master.blade.php file
            $layoutsDirPath = base_path('resources/views/layouts/admin/');

            if (!File::isDirectory($layoutsDirPath)) File::makeDirectory($layoutsDirPath, 0777, true);

            $layoutsFile = $this->viewDirectoryPath . 'master.blade.stub';
            $newLayoutsFile = $layoutsDirPath . 'master.blade.php';

            if (!File::exists($newLayoutsFile)) {
                if (!File::copy($layoutsFile, $newLayoutsFile)) {
                    echo "failed to copy $layoutsFile...\n";
                }
            }

            // For modals
            if (!File::isDirectory($layoutsDirPath . '/modals')) File::makeDirectory($layoutsDirPath . '/modals', 0777, true);

            $modalcrudFile = $this->viewDirectoryPath . 'modal-ces.blade.php';

            If(!File::exists($layoutsDirPath . '/modals/modal-ces.blade.php')){
                if (!File::copy($modalcrudFile, $layoutsDirPath . '/modals/modal-ces.blade.php')) {
                    echo "failed to copy $layoutsFile/modals ...\n";
                }
            }

            $modaldeleteFile = $this->viewDirectoryPath . 'modal-delete.blade.php';

            If(!File::exists($layoutsDirPath . '/modals/modal-delete.blade.php')){
                if (!File::copy($modaldeleteFile, $layoutsDirPath . '/modals/modal-delete.blade.php')) {
                    echo "failed to copy $layoutsFile/modals ...\n";
                }
            }

            $this->info('Views created.');
        }
    }

    protected function createField($field)
    {
        switch ($this->availableFields[$field['type']]) {
            case 'text':
                return $this->fieldStructure($field, 'same');
                break;
            case 'textarea':
                return $this->fieldStructure($field, 'same');
                break;
            case 'number':
                return $this->fieldStructure($field, 'same');
                break;
            case 'date':
                return $this->fieldStructure($field, 'same');
                break;
            case 'datetime':
                return $this->fieldStructure($field, 'same');
                break;
            case 'file':
                return $this->fieldStructure($field, 'file');
                break;
            case 'checkbox':
                return $this->fieldStructure($field, 'same');
                break;
            case 'radio':
                return $this->fieldStructure($field, 'same');
                break;
            case 'email':
                return $this->fieldStructure($field, 'same');
                break;
            case 'password':
                return $this->fieldStructure($field, 'password');
                break;
            case 'url':
                return $this->fieldStructure($field, 'same');
                break;
            case 'select':
                return $this->fieldStructure($field, 'select');
                break;
            default: // text
                return $this->fieldStructure($field, 'same');
        }
    }

    protected function initializeVariables($entityName)
    {
        $this->name = $entityName; //Test
        $this->nameSingularLower = strtolower($entityName); //test
        $this->namePlural = str_plural($entityName); //Tests
        $this->namePluralLower = strtolower(str_plural($entityName)); //tests
    }

    protected function fieldStructure($field, $structure)
    {
        switch ($structure) {
            case 'same':
                return "<div class=\"form-group\">
                        {!! Form::label('" . $field['name'] . "', '" . ucwords($field['name']) . "') !!}
                        {!! Form::" . $field['type'] . "('" . $field['name'] . "', null, ['class' => 'form-control']) !!}
                    </div>
                " . PHP_EOL;
                break;
            case 'file':
                return "
                    <div class=\"form-group\">
                        {!! Form::label('" . $field['name'] . "', '" . ucwords($field['name']) . "') !!}
                        {!! Form::" . $field['type'] . "('" . $field['name'] . "') !!}
                    </div>
                " . PHP_EOL;
                break;
            case 'password':
                return "
                    <div class=\"form-group\">
                        {!! Form::label('" . $field['name'] . "', '" . ucwords($field['name']) . "') !!}
                        {!! Form::" . $field['type'] . "('" . $field['name'] . "', ['class' => 'form-control']) !!}
                    </div>
                " . PHP_EOL;
                break;
            case 'select':
                return "
                    <div class=\"form-group\">
                        {!! Form::label('" . $field['name'] . "', '" . ucwords(str_replace('_id', '', $field['name'])) . "') !!}
                        {!! Form::" . $field['type'] . "('" . $field['name'] . "', App\\" . ucwords(str_replace('_id', '', $field['name'])) . "::lists('"  . str_replace('_id', '', $field['name']) . "', 'id')" . ", null, ['class' => 'form-control']) !!}
                    </div>
                " . PHP_EOL;
                break;
            default:
                return "<div class=\"form-group\">
                        {!! Form::label('" . $field['name'] . "', '" . ucwords($field['name']) . "') !!}
                        {!! Form::" . $field['type'] . "('" . $field['name'] . "', null, ['class' => 'form-control']) !!}
                    </div>
                " . PHP_EOL;
        }
    }
}