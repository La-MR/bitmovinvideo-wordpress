<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 01.07.15
 * Time: 15:31
 */


use bitcodin\Bitcodin;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\ManifestTypes;
use bitcodin\Output;
use bitcodin\FtpOutputConfig;

require_once __DIR__ . '/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('YOURAPIKEYHERE'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel-original-short.mkv';
$input = Input::create($inputConfig);
echo "Input successfully created! \n";
echo json_encode($input) . "\n";

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
echo "Encoding-Profile successfully created! \n";
echo json_encode($encodingProfile) . "\n";

/* CREATE OUTPUT */

$outputConfig = new FtpOutputConfig();
$outputConfig->name = "FTP Output Destination";
$outputConfig->host = getKey('ftpServer');
$outputConfig->username = getKey('ftpUser');
$outputConfig->password = getKey('ftpPassword');

$output = Output::create($outputConfig);
echo "Output successfully created! \n";
echo json_encode($output) . "\n";

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->output = $output;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

echo "\n\nCreate Encoding...\n\n";

/* WAIT TIL JOB IS FINISHED */
do {
    $job->update();
    echo "\r" . date_create()->format('d.m.Y H:i:s') . ' - Job: ' . $job->jobId . ' Status[' . $job->status . "]";
    sleep(2);
} while ($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);

echo "\n\nWait for Transfer...\n\n";

/* WAIT TIL TRANSFER IS FINISHED */
do {
    $date = "\r" . date_create()->format('d.m.Y H:i:s');
    try {
        $transfers = $job->getTransfers();
        $finishedTransfer = 0;
        foreach ($transfers as $transfer) {
            echo $date . ' - Transfer: JobID ' . $transfer->id . ' Progress[' . $transfer->progress . "] Status[" . $transfer->status . "]";
            if ($transfer->progress == 100)
                $finishedTransfer++;
        }
        sleep(2);
    } catch (\bitcodin\exceptions\BitcodinResourceNotFoundException $e) {
        echo $date . " - Transfer: Waiting for Transfer...";
        $transfers = array();
        sleep(2);
    } catch (\Exception $e) {
        echo "Unexpected Error\n";
    }
} while (empty($transfers) || $finishedTransfer < count($transfers));

echo "\n\nTransfer finished...\n\n";

foreach ($transfers as $transfer) {
    echo $transfer->createdAt . ' - Transfer: JobID ' . $transfer->id . ' URL: ' . $transfer->outputProfile->outputUrl . "\n";
}


/* HELPER FUNCTION */
function getKey($key)
{
    return json_decode(file_get_contents(__DIR__ . '/../test/config.json'))->{$key};
}
