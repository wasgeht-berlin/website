<?php namespace App\Jobs;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Filesystem\Filesystem;

class UpdateProviders extends Job implements SelfHandling
{
    /* @var $fs Filesystem */
    protected $fs = null;
    /* @var $gh GithubManager */
    protected $gh = null;

    protected $providerStorage = 'providers/';

    protected $ghUser = 'wasgeht-berlin';

    protected $ghRepository = 'data-providers';

    public function handle(Filesystem $fs, GitHubManager $gh)
    {
        $this->fs = $fs;
        $this->gh = $gh;

        $this->update();
    }

    protected function update()
    {
        $fileListPath = $this->providerStorage . '.filelist';

        if ($this->fs->exists($fileListPath)) {
            $fileList = unserialize($this->fs->get($fileListPath));
        } else {
            $fileList = [];
        }

        $this->downloadDirectory(['path' => null], $fileList);

        foreach ($this->fs->allFiles($this->providerStorage) as $filename) {
            if (ends_with($filename, '.filelist')) continue;

            $listFileName = str_replace('providers/', '', $filename);
            if (!in_array($listFileName, array_keys($fileList))) {
                $this->fs->delete($filename);
            }
        }

        $this->fs->put($fileListPath, serialize($fileList));
    }

    protected function downloadDirectory(array $file, array &$fileList)
    {
        // NOTE: this could be further optimized by keeping track of directory hashes too
        $files = $this->gh->repo()->contents()->show($this->ghUser, $this->ghRepository, $file['path']);

        $dirName = $this->providerStorage;

        if (!is_null($file['path'])) {
            $dirName = $this->providerStorage . $file['path'];
        }

        $this->fs->makeDirectory($dirName);

        foreach ($files as $file) {
            if ($file['type'] == 'dir') {
                $this->downloadDirectory($file, $fileList);
            } else {
                $this->downloadFile($file, $fileList);
            }
        }
    }

    protected function downloadFile(array $file, array &$fileList)
    {
        $filePath = $this->providerStorage . $file['path'];

        if (!$this->fs->exists($filePath) || $fileList[$file['path']] != $file['sha']) {
            $data = $this->gh->repo()->contents()->download($this->ghUser, $this->ghRepository, $file['path']);
            $this->fs->put($filePath, $data);

            $fileList[$file['path']] = $file['sha'];
        }
    }
}
