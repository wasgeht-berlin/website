<?php namespace App\Jobs;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Contracts\Bus\SelfHandling;

class CreateGitHubIssue extends Job implements SelfHandling
{
    protected $user = '';
    protected $repo = '';

    protected $title = '';
    protected $body = '';

    protected $labels = [];

    /**
     * CreateGitHubIssue constructor.
     * @param string $user
     * @param string $repo
     * @param string $title
     * @param string $body
     * @param array $labels
     */
    public function __construct($user, $repo, $title, $body, array $labels = [])
    {
        $this->user = $user;
        $this->repo = $repo;
        $this->title = $title;
        $this->body = $body;
        $this->labels = $labels;
    }

    public function handle(GitHubManager $gm)
    {
        $searchParams = sprintf('user:%s repo:%s state:open in:title %s',
            $this->user,
            $this->repo,
            $this->title
        );

        $search = $gm->api('search')->issues($searchParams, 'created', 'desc');

        if ($search['total_count'] == 0) {
            $createParams = [
                'title'  => $this->title,
                'body'   => $this->body,
            ];

            if (count($this->labels) > 0) {
                $createParams['labels'] = $this->labels;
            }

            $gm->issue()->create($this->user, $this->repo, $createParams);
        }
    }
}