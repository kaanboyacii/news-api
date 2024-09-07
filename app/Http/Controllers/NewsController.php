<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    // Tüm haberleri getirme
    public function index()
    {
        Log::info('Tüm haberler getirildi.');
        return News::all();
    }

    // Yeni Haber oluşturma
    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $imagePath = 'public/images/' . $imageName;
            $image->move(storage_path('app/public/images'), $imageName);
            // Görseli yeniden boyutlandırma
            $this->resizeImage(storage_path('app/public/images/' . $imageName), 800, 800);
            $data['image'] = $imageName;
        }

        $news = News::create($data);
        Log::info('Haber başarılı şekilde oluşturuldu.', ['news' => $news]);

        return response()->json($news, 201);
    }

    // Haber güncelleme
    public function update(UpdateNewsRequest $request, News $news)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $imagePath = 'public/images/' . $imageName;
            $image->move(storage_path('app/public/images'), $imageName);
            // Görseli yeniden boyutlandırma
            $this->resizeImage(storage_path('app/public/images/' . $imageName), 800, 800);
            $data['image'] = $imageName;
        }

        $news->update($data);
        Log::info('Haber başarılı şekilde güncellendi.', ['news' => $news]);

        return response()->json($news);
    }

    // Haber gösterme
    public function show(News $news)
    {
        Log::info('ID ile getirilen haber.', ['news' => $news]);
        return $news;
    }

    //Haber silme
    public function destroy(News $news)
    {
        $news->delete();
        Log::info('Haber başarılı şekilde silindi.', ['news_id' => $news->id]);
        return response()->json(null, 204);
    }

    //Haber aratma
    public function search(Request $request)
    {
        $query = News::query();
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->input('content') . '%');
        }
        $news = $query->get();
        Log::info('Arama gerçekleştirildi.', ['results_count' => $news->count()]);
        return response()->json($news);
    }

    // Resim boyutlandırma methodu
    private function resizeImage($filePath, $maxWidth, $maxHeight)
    {
        list($width, $height) = getimagesize($filePath);
        $ratio = $width / $height;

        if ($width > $maxWidth || $height > $maxHeight) {
            if ($width > $height) {
                $newWidth = $maxWidth;
                $newHeight = $maxWidth / $ratio;
            } else {
                $newHeight = $maxHeight;
                $newWidth = $maxHeight * $ratio;
            }

            $imageResized = imagecreatetruecolor($newWidth, $newHeight);

            switch (mime_content_type($filePath)) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($filePath);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($filePath);
                    break;
                default:
                    throw new \Exception('Desteklenmeyen görsel formatı');
            }

            imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            switch (mime_content_type($filePath)) {
                case 'image/jpeg':
                    imagejpeg($imageResized, $filePath, 90);
                    break;
                case 'image/png':
                    imagepng($imageResized, $filePath);
                    break;
                case 'image/webp':
                    imagewebp($imageResized, $filePath);
                    break;
            }

            imagedestroy($image);
            imagedestroy($imageResized);
        }
    }
}
