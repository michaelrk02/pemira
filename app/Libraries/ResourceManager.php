<?php

namespace App\Libraries;

class ResourceManager {

    protected $directory;

    public function __construct() {
        $paths = config('Paths');

        $this->directory = $paths->writableDirectory.'/uploads/';
    }

    public function exists($id) {
        $metadataPath = $this->resolvePath($id).'.meta';
        $contentsPath = $this->resolvePath($id).'.blob';
        return is_readable($metadataPath) && is_readable($contentsPath);
    }

    public function getMetadata($id) {
        $path = $this->resolvePath($id).'.meta';
        $metadata = @file_get_contents($path);
        $metadata = @json_decode($metadata, TRUE);
        return $metadata;
    }

    public function getContents($id) {
        $path = $this->resolvePath($id).'.blob';
        $contents = @file_get_contents($path);
        return $contents;
    }

    public function getStream($id, $dst) {
        $contentsPath = $this->resolvePath($id).'.blob';
        $stream = fopen($contentsPath, 'r');
        if ($stream !== FALSE) {
            while (!feof($stream)) {
                if (($buffer = fread($stream, 512)) !== FALSE) {
                    fwrite($dst, $buffer);
                }
            }
            fclose($stream);
            return TRUE;
        }
        return FALSE;
    }

    public function putMetadata($id, $metadata) {
        $metadataPath = $this->resolvePath($id).'.meta';
        @file_put_contents($metadataPath, @json_encode($metadata));
        return $this->exists($id);
    }

    public function putContents($id, $contents) {
        $contentsPath = $this->resolvePath($id).'.blob';
        @file_put_contents($contentsPath, $buffer);
        return $this->exists($id);
    }

    public function putStream($id, $src) {
        $contentsPath = $this->resolvePath($id).'.blob';
        $stream = fopen($contentsPath, 'w');
        if ($stream !== FALSE) {
            while (!feof($src)) {
                if (($buffer = fread($src, 512)) !== FALSE) {
                    fwrite($stream, $buffer);
                }
            }
            fclose($stream);
            return TRUE;
        }
        return FALSE;
    }

    public function getResourceList() {
        $resources = [];

        $files = scandir($this->directory);
        foreach ($files as $file) {
            if (substr($file, -5) === '.meta') {
                $resource = [];
                $resource['name'] = substr($file, 0, strlen($file) - 5);
                $resource['size'] = number_format((@filesize($this->resolvePath($resource['name']).'.blob') / 1024), 0).' KB';
                $resource['mime'] = @$this->getMetadata($resource['name'])['mime'] ?? 'N/A';
                $resources[] = $resource;
            }
        }

        return $resources;
    }

    public function delete($id) {
        $metadataPath = $this->resolvePath($id).'.meta';
        $blobPath = $this->resolvePath($id).'.blob';
        if (file_exists($metadataPath) && file_exists($blobPath)) {
            return @unlink($metadataPath) && @unlink($blobPath);
        }
        return FALSE;
    }

    protected function resolvePath($id) {
        return $this->directory.basename($id);
    }

}

