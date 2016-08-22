<?php

    namespace test\output;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\Output;
    use bitcodin\S3OutputConfig;

    class S3OutputTest extends AbstractOutputTest
    {

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @test
         * @return Output
         */
        public function create()
        {
            $s3Config = $this->getKey('s3output');

            $outputConfig = new S3OutputConfig();
            $outputConfig->accessKey = $s3Config->accessKey;
            $outputConfig->secretKey = $s3Config->secretKey;
            $outputConfig->name = $s3Config->name;
            $outputConfig->bucket = $s3Config->bucket;
            $outputConfig->region = $s3Config->region;
            $outputConfig->makePublic = false;

            $output = Output::create($outputConfig);

            $this->checkOutput($output);

            return $output;
        }

        /**
         * @depends create
         *
         * @param Output $output
         *
         * @return Output
         */
        public function update(Output $output)
        {
            $output->update();
            $this->checkOutput($output);

            return $output;
        }

        /**
         * @test
         * @depends create
         *
         * @param Output $output
         *
         * @return Output
         */
        public function get(Output $output)
        {
            return $this->getOutput($output);
        }

        /**
         * @test
         * @depends get
         *
         * @param Output $output
         */
        public function delete(Output $output)
        {
            return $this->deleteOutput($output);
        }
    }
