<?php

use bitcodin\Bitcodin;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\Input;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\ManifestTypes;
use bitcodin\Output;
use bitcodin\GcsOutputConfig;
use bitcodin\GCSInputConfig;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new GCSInputConfig();
$inputConfig->accessKey = 'yourGCSAccessKey';
$inputConfig->secretKey = 'yourGCSSecretKey';
$inputConfig->bucket    = 'yourBucketName';
$inputConfig->objectKey = 'path/to/your/fileonbucket.mp4';
$input = Input::create($inputConfig);

/* CREATE OUTPUT CONFIG  */
$outputConfig = new GcsOutputConfig();
$outputConfig->name         = "TestGcsOutput";
$outputConfig->accessKey    = "yourGcsAccessKey";
$outputConfig->secretKey    = "yourGcsSecretKey";
$outputConfig->bucket       = "yourBucketName";
$outputConfig->prefix       = "path/to/your/outputDirectory";
$outputConfig->makePublic   = false;                            // This flag determines whether the files put on GCS will be publicly accessible via HTTP Url or not

$output = Output::create($outputConfig);

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';

/* CREATE VIDEO STREAM CONFIGS */
$videoStreamConfig1 = new VideoStreamConfig();
$videoStreamConfig1->bitrate = 4800000;
$videoStreamConfig1->height = 1080;
$videoStreamConfig1->width = 1920;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig1;

$videoStreamConfig2 = new VideoStreamConfig();
$videoStreamConfig2->bitrate = 2400000;
$videoStreamConfig2->height = 720;
$videoStreamConfig2->width = 1280;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig2;

$videoStreamConfig3 = new VideoStreamConfig();
$videoStreamConfig3->bitrate = 1200000;
$videoStreamConfig3->height = 480;
$videoStreamConfig3->width = 854;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig3;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 128000;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->output = $output;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);
