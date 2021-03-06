<?php
/**
 * Class Handler
 *
 * @author Ihor Sahaydak ihorsahaydak@gmail.com
 */
class Handler
{
    public $mul;
    public $weight;
    public $sinaps;
    public $sizeX;
    public $sizeY;
    public $filename;
    public $sum;
    public $limit;
    protected $photoFileName;
    protected $wFileName;

    /**
     * Handler constructor
     *
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->sizeX     = 3;
        $this->sizeY     = 5;
        $this->limit     = 100;
        $this->wFileName = 'data/' . $filename . '.txt';
    }

    /**
     * Set name for photo
     *
     * @param $filename
     */
    public function setPhotoFileName($filename)
    {
        $this->photoFileName = 'images/' . $filename . '.png';
    }

    /**
     * Save file weight
     */
    public function saveFileWeight()
    {
        $serialize = serialize($this->weight);

        fwrite(fopen($this->wFileName,'w'), $serialize);
    }

    /**
     * Load file weight
     */
    public function loadFileWeight()
    {
        if (file_exists($this->wFileName)) {
            $this->weight = unserialize(file_get_contents($this->wFileName));
        } else {
            for ($x = 0; $x < $this->sizeX; $x++) {
                for($y = 0; $y < $this->sizeY; $y++) {
                    $this->weight[$x][$y] = '0';
                }
            }
        }
    }

    /**
     * Upload file
     */
    public function uploadFile()
    {
       $img = @imagecreatefrompng($this->photoFileName);

       for ($x = 0; $x < $this->sizeX; $x++) {
            for ($y = 0; $y < $this->sizeY; $y++) {
                $rgb                    = imagecolorat($img, $x, $y);
                $color                  = imagecolorsforindex($img, $rgb);
                $color                  = $color['red'] > 126 ? 0 : 1;
                $this->sinaps[$x][$y]   = $color;
            }
        }

        imagedestroy($img);
    }

    /**
     * Set transformed signals
     */
    public function updateMuls()
    {
        for($x = 0; $x < $this->sizeX; $x++) {
            for($y = 0; $y < $this->sizeY; $y++) {
                $this->mul[$x][$y] = $this->sinaps[$x][$y] * $this->weight[$x][$y];
            }
        }
    }

    /**
     * calculate muls sum
     */
    public function calculateMulsSum()
    {
        $this->sum = 0;

        for ($x = 0; $x < $this->sizeX; $x++) {
            for ($y = 0; $y < $this->sizeY; $y++) {
                $this->sum += $this->mul[$x][$y];
            }
        }
    }

    /**
     * Porog
     *
     * @return bool
     */
    public function isLimit()
    {
        return $this->sum >= $this->limit;
    }

    /**
     * Teach plus
     */
    public function teachPlus()
    {
        for ($x = 0; $x < $this->sizeX; $x++) {
            for ($y = 0; $y < $this->sizeY; $y++) {
                $this->weight[$x][$y] += $this->sinaps[$x][$y];
            }
        }
    }

    /**
     * Teach minus
     */
    public function teachMinus()
    {
        for($x = 0; $x < $this->sizeX; $x++) {
            for ($y = 0; $y < $this->sizeY; $y++) {
                $this->weight[$x][$y] -= $this->sinaps[$x][$y];
            }
        }
    }

    /**
     * Prepare pre dependencies
     *
     * @param $n
     */
    public function preparePreDependencies($n)
    {
        $this->setPhotoFileName($n);
        $this->loadFileWeight();
        $this->uploadFile();
        $this->updateMuls();
        $this->calculateMulsSum();
    }
}
