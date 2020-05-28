<?php

namespace PodPoint\I18n\Aws;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

class Client
{
    /**
     * @var S3Client
     */
    public $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'credentials' => [
                'key'    => env('AWS_KEY'),
                'secret' => env('AWS_SECRET')
            ],
            'region' => 'eu-west-1',
            'version' => 'latest',
        ]);
    }

    public function filesystem(string $bucket): Filesystem
    {
        $adapter = new AwsS3Adapter($this->client, $bucket);

        return new Filesystem($adapter);
    }
}
