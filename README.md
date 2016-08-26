# Installation
1) Run 
    
    composer require devlob/generators

2) Add the following service providers to config/app.php

    Devlob\Generators\DevlobGeneratorsServiceProvider::class,
    Collective\Html\HtmlServiceProvider::class,
    Yajra\Datatables\DatatablesServiceProvider::class,

3) Add the following aliases to config/app.php

    'Form' => Collective\Html\FormFacade::class,
    'Html' => Collective\Html\HtmlFacade::class,
    'Datatables' => Yajra\Datatables\Facades\Datatables::class,

4) Run 
   
    php artisan vendor:publish

5) Copy the the route group to routes.php

    Route::group(['as' => 'admin::'], function(){
      # Admin resource controllers START
      # Admin datatables START
    });

6) Add the following line of code in app/providers/RouteServiceProvider.php in the boot function, before parent::boot($router);

    \Route::singularResourceParameters();


Now you are ready. Run php artisan and see if you get the new devlob commands.

# Example
This will create crud operations for book and author.

    php artisan devlob:devlob Book --fields="book:text, pages:number, author_id:select, slug:text"
    php artisan devlob:devlob Author --fields="author:text, location:text"
    
Make sure you create migrations for these cruds.

    php artisan make:migration create_books_table --create=books
    php artisan make:migration create_authors_table --create=authors
    
In the books migration include this code for the up function

    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id');
            $table->string('book');
            $table->string('slug');
            $table->integer('pages');
            $table->timestamps();
        });
    }
    
In the authors migration include this code for the up function

    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('author');
            $table->string('location');
            $table->timestamps();
        });
    }
    
Run
    
    php artisan migrate
    
You are ready. Go to /books or /authors to view the index page of the corresponding cruds.
