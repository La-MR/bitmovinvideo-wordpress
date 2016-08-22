<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class VideoStreamConfig
 * @package bitcodin
 */
class VideoStreamConfig implements \JsonSerializable
{
    const PROFILE_BASELINE = 'baseline';
    const PROFILE_MAIN = 'main';
    const PROFILE_HIGH = 'high';
    const PRESET_STANDARD = 'standard';
    const PRESET_PROFESSIONAL = 'professional';
    const PRESET_PREMIUM = 'premium';
    /**
     * @var int
     */
    public $bitrate;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $defaultStreamId = 0;

    /**
     * @var string
     */
    public $codec = null;

    /**
     * @var string
     */
    public $profile = VideoStreamConfig::PROFILE_MAIN;

    /**
     * @var string
     */
    public $preset = VideoStreamConfig::PRESET_PREMIUM;

    /**
     * @var float
     */
    public $rate = null;

    /**
     * @var integer
     */
    public $bFrames = null;

    /**
     * @var integer
     */
   public $qpMin = null;

    /**
     * @var integer
     */
    public $qpMax = null;

    /**
     * @var string
     */
    public $mvPredictionMode  = null;

    /**
     * @var integer
     */
    public $mvSearchRangeMax = null;

    /**
     * @var boolean
     */
    public $noCabac = null;

    public function jsonSerialize()
    {
        $array = get_object_vars($this);
        
        if($array['rate'] == null)
            unset($array['rate']);
        if($array['codec'] == null)
            unset($array['codec']);
        if(!is_numeric($array['bFrames']))
            unset($array['bFrames']);
        if(!is_numeric($array['qpMin']))
            unset($array['qpMin']);
        if(!is_numeric($array['qpMax']))
            unset($array['qpMax']);
        if($array['mvPredictionMode'] == null)
            unset($array['mvPredictionMode']);
        if(!is_numeric($array['mvSearchRangeMax']))
            unset($array['mvSearchRangeMax']);
        if(!is_bool($array['noCabac']))
            unset($array['noCabac']);

        return $array;
    }
}
