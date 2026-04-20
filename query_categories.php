require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = new Laravel\Lumen\Application(dirname(__DIR__));
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);
$categories = \App\Models\Category::all();
foreach($categories as $cat) {
    echo 'ID: ' . $cat->id . ', Name: ' . $cat->name . PHP_EOL;
}
