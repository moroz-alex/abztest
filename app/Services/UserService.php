<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    public function store($data)
    {
        auth()->user()->currentAccessToken()->delete();

        $size = getimagesize($data['photo']);
        $shortSize = $size[0] < $size[1] ? $size[0] : $size[1];
        $longSize = $size[0] > $size[1] ? $size[0] : $size[1];
        $center = (int)($longSize / 2);
        $sourceImage = imagecreatefromjpeg($data['photo']);
        $newImage = imagecreatetruecolor(70, 70);
        // Обрезаем исхожное изображение до квадрата по центру, и только после(!) этого делаем ресайз до 70x70px
        imagecopyresized(
            $newImage,
            $sourceImage,
            0,
            0,
            $size[0] == $longSize ? $center - (int)($shortSize / 2) : 0,
            $size[1] == $longSize ? $center - (int)($shortSize / 2) : 0,
            70,
            70,
            $shortSize,
            $shortSize
        );
        $fileName = 'storage/tmp/' . Str::random(16) . '.jpg';
        imagejpeg($newImage, public_path($fileName, 100));
        $path = asset($fileName);

        $res = Http::withBasicAuth('api', 'H0szzy9Kp5rZGdHqMz8kcHVf3GHTg3mz')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://api.tinify.com/shrink', [
                'source' => [
                    'url' => $path,
                ]
            ]);

        if ($res->status() == 201) {
            $contents = file_get_contents($res->json()['output']['url']);
            file_put_contents(public_path($fileName), $contents);
        }

        $data['photo'] = Storage::disk('public')->putFile('/images', new File($fileName));

        unlink(public_path($fileName));

        try {
            $user = User::create($data);
        } catch (\Exception) {
            abort(500, 'User creation error');
        }

        return $user;
    }
}
