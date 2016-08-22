<?php

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\AudioMetaData;
    use bitcodin\AudioStreamConfig;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\HttpInputConfig;
    use bitcodin\Input;
    use bitcodin\Job;
    use bitcodin\JobConfig;
    use bitcodin\JobSpeedTypes;
    use bitcodin\ManifestTypes;
    use bitcodin\VideoStreamConfig;
    use test\job\AbstractJobTest;

    class JobAudioOnlyTest extends AbstractJobTest
    {

        const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/Sintel-two-audio-streams-short.mkv';
        const URL_FILE_AUDIO_ONLY = 'http://bitbucketireland.s3.amazonaws.com/Sintel-two-audio-streams-audio-only-short.mkv';

        public function setUp()
        {
            parent::setUp();
        }

        /** TEST JOB CREATION */
        public function testAudioOnlyJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);


            /* CREATE ENCODING PROFILE */
            $encodingProfile = $this->getMultiLanguageEncodingProfile();

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            $jobConfig->speed = JobSpeedTypes::STANDARD;


            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }


        public function testMultiAudioStreamAudioOnlyInputJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE_AUDIO_ONLY;
            $input = Input::create($inputConfig);

            $audioMetaDataJustSound = new AudioMetaData();
            $audioMetaDataJustSound->defaultStreamId = 0;
            $audioMetaDataJustSound->label = 'Just Sound';
            $audioMetaDataJustSound->language = 'de';

            $audioMetaDataSoundAndVoice = new AudioMetaData();
            $audioMetaDataSoundAndVoice->defaultStreamId = 1;
            $audioMetaDataSoundAndVoice->label = 'Sound and Voice';
            $audioMetaDataSoundAndVoice->language = 'en';

            // CREATE AUDIO STREAM CONFIGS
            $audioStreamConfigSoundHigh = new AudioStreamConfig();
            $audioStreamConfigSoundHigh->bitrate = 256000;
            $audioStreamConfigSoundHigh->defaultStreamId = 0;

            $audioStreamConfigSoundAndVoiceHigh = new AudioStreamConfig();
            $audioStreamConfigSoundAndVoiceHigh->bitrate = 256000;
            $audioStreamConfigSoundAndVoiceHigh->defaultStreamId = 1;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = 'TestEncodingProfile_' . $this->getName() . '@JobMultiLanguageTest';
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundHigh;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundAndVoiceHigh;

            // CREATE ENCODING PROFILE
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->speed = JobSpeedTypes::STANDARD;
            $jobConfig->audioMetaData[] = $audioMetaDataJustSound;
            $jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;

            // CREATE JOB
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        public function testMultiAudioStreamAudioOnlyJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);

            $audioMetaDataJustSound = new AudioMetaData();
            $audioMetaDataJustSound->defaultStreamId = 0;
            $audioMetaDataJustSound->label = 'Just Sound';
            $audioMetaDataJustSound->language = 'de';

            $audioMetaDataSoundAndVoice = new AudioMetaData();
            $audioMetaDataSoundAndVoice->defaultStreamId = 1;
            $audioMetaDataSoundAndVoice->label = 'Sound and Voice';
            $audioMetaDataSoundAndVoice->language = 'en';

            // CREATE AUDIO STREAM CONFIGS
            $audioStreamConfigSoundHigh = new AudioStreamConfig();
            $audioStreamConfigSoundHigh->bitrate = 256000;
            $audioStreamConfigSoundHigh->defaultStreamId = 0;

            $audioStreamConfigSoundAndVoiceHigh = new AudioStreamConfig();
            $audioStreamConfigSoundAndVoiceHigh->bitrate = 256000;
            $audioStreamConfigSoundAndVoiceHigh->defaultStreamId = 1;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = 'TestEncodingProfile_' . $this->getName() . '@JobMultiLanguageTest';
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundHigh;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundAndVoiceHigh;

            // CREATE ENCODING PROFILE
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            //$jobConfig->manifestTypes[] = ManifestTypes::M3U8; // TODO
            $jobConfig->speed = JobSpeedTypes::STANDARD;
            $jobConfig->audioMetaData[] = $audioMetaDataJustSound;
            $jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;

            // CREATE JOB
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }


        public function testVideoOnlyJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);

            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = 'TestEncodingProfile_' . $this->getName() . '@JobMultiLanguageTest';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->speed = JobSpeedTypes::STANDARD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends testMultiAudioStreamAudioOnlyJob
         */
        public function testUpdateMultiAudioStreamAudioOnlyJob(Job $job)
        {
            return $this->updateJob($job);
        }


        /**
         * @return EncodingProfile
         */
        private function getMultiLanguageEncodingProfile()
        {
            $audioStreamConfigGermanLow = new AudioStreamConfig();
            $audioStreamConfigGermanLow->bitrate = 156000;
            $audioStreamConfigGermanLow->defaultStreamId = 0;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = 'TestEncodingProfile_' . $this->getName() . '@JobMultiLanguageTest';

            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigGermanLow;

            // CREATE ENCODING PROFILE
            return EncodingProfile::create($encodingProfileConfig);
        }

    }
