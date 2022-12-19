<?php

namespace Concrete\Package\ActiveCookieConsentThirdParty\Controller\Frontend;

use Concrete\Core\Controller\Controller;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class VideoThumbnail extends Controller
{
    /* 1 Month */
    private const  LIFE_TIME = 2629743;

    public function getThumbnail($type, $id)
    {
        $path = $this->getThumbnailPath($type, $id);
        if ($path !== null) {
            return new BinaryFileResponse($path, Response::HTTP_OK, ['Content-Type' => 'image/jpeg']);
        }
        return new Response(null, Response::HTTP_NOT_FOUND);
    }

    private function getThumbnailPath($type, $id)
    {
        $filesystem = $this->app->make(Filesystem::class);
        $config = $this->app->make('config');
        $cacheDirectory = $config->get('concrete.cache.directory');
        $accThumbnailDir = $cacheDirectory . '/thumbnails/acc_videos';
        if ($filesystem->exists("{$accThumbnailDir}/{$type}__{$id}.jpg")) {
            if ((filemtime("{$accThumbnailDir}/{$type}__{$id}.jpg") + self::LIFE_TIME) > time()) {
                return "{$accThumbnailDir}/{$type}__{$id}.jpg";
            }
        }
        $perms = $config->get('concrete.filesystem.permissions');
        $directoryPermissions = array_get($perms, 'directory');
        if (is_dir($accThumbnailDir) || $filesystem->makeDirectory($accThumbnailDir, $directoryPermissions)) {
            if ($type === 'youtube') {
                if ($filesystem->copy("https://img.youtube.com/vi/{$id}/hqdefault.jpg", "{$accThumbnailDir}/{$type}__{$id}.jpg")){
                    return "{$accThumbnailDir}/{$type}__{$id}.jpg";
                }
            }

            if ($type === 'vimeo') {
                $data = @file_get_contents("https://vimeo.com/api/v2/video/$id.json");
                $data = @json_decode($data, true);
                if (is_array($data)) {
                    $thumbnailURL = $data[0]['thumbnail_large'] ?? $data[0]['thumbnail_medium'] ?? $data[0]['thumbnail_small'] ?? '';
                    if ($filesystem->copy($thumbnailURL, "{$accThumbnailDir}/{$type}__{$id}.jpg")){
                        return "{$accThumbnailDir}/{$type}__{$id}.jpg";
                    }
                }
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Controller\AbstractController::shouldRunControllerTask()
     */
    public function shouldRunControllerTask(): bool
    {
        return true;
    }
}
