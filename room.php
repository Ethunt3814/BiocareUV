<?php

    class Room{

        //basic room dimentions in metric (meters)
        public $widthF;
        public $lengthF;
        public $heightF;
        public $widthM;
        public $lengthM;
        public $heightM;
        public $desks;
        public $widthCM;
        public $lengthCM;
        public $heightCM;
        public $wpDist;
        public $area;
        public $areaF;

        //variables
        public $peakIntensity20cm = 3.44;
        public $germicidalIntensity20cm;
        public $totalGermicidalIntensity4cm = 3600.00;
        public $intensityWP;
        public $intensityF;
        public $intensityH;
        public $log1TimeDoseWP;
        public $log2TimeDoseWP;
        public $log3TimeDoseWP;
        public $log1TimeDoseF;
        public $log2TimeDoseF;
        public $log3TimeDoseF;
        public $log1TimeDoseH;
        public $log2TimeDoseH;
        public $log3TimeDoseH;
        public $workPlane8hr;
        public $floor8hr;
        public $triDistWP;
        public $triDistF;

        public $intesityWPOverlap;
        public $tToDoseWP;
        public $intesityFOverlap;
        public $tToDoseF;

        public $maxLumiSpacingCM;
        public $maxAreaCoverageCM;
        public $maxLumiSpacingF;
        public $maxAreaSide;
        public $lumiPerArea;
        public $lumiPerW;
        public $lumiPerL;
        public $optimalLumiPerW;
        public $optimalLumiPerL;
        public $optimalLumiPerA;
        public $pricePerRoom;
        public $pricePerDesk;
        
        public $MAXDOSAGE = 23;
        public $KILLRATE90 = 0.39;
        public $KILLRATE99 = 0.78;
        public $KILLRATE99_9 = 1.20;
        public $WORKPLANEHEIGHT = 91.0;
        public $PRICEPERUNIT = 600;

        function __construct($width,$height,$length,$desks)
        {

            //get room dimentions
            $this->widthF = $width;
            $this->heightF = $height;
            $this->lengthF = $length;
            $this->desks = $desks;
            
            //convert to metric
            $this->widthCM = $width*30.48;
            $this->heightCM = $height*30.48;
            $this->lengthCM = $length*30.48;
            
            //find germicidal intensity at 20 cm
            $this->germicidalIntensity20cm = pow(1/(20/4),2)*$this->totalGermicidalIntensity4cm;

            //convert meters to centimeters
            $this->widthM = $this->widthCM/100;
            $this->lengthM = $this->lengthCM/100;
            $this->heightM = $this->heightCM/100;

            //area calculations
            $this->areaF= $width*$length;
            $this->area = $this->widthCM*$this->heightCM;
            
            //find workplane distance
            $this->wpDist = $this->heightCM-$this->WORKPLANEHEIGHT;

            //find intensity
            $this->intensityWP = 1/pow(($this->wpDist/20),2)*$this->germicidalIntensity20cm;
            $this->intensityF = 1/pow(($this->heightCM/20),2)*$this->germicidalIntensity20cm;
            $this->intensityH = 1/pow((($this->heightCM-183)/20),2)*$this->germicidalIntensity20cm;

            //find time to dose
            $this->log1TimeDoseWP = $this->KILLRATE90*1000/$this->intensityWP/60;
            $this->log2TimeDoseWP = $this->KILLRATE99*1000/$this->intensityWP/60;
            $this->log3TimeDoseWP = $this->KILLRATE99_9*1000/$this->intensityWP/60;

            $this->log1TimeDoseF = $this->KILLRATE90*1000/$this->intensityF/60;
            $this->log2TimeDoseF = $this->KILLRATE99*1000/$this->intensityF/60;
            $this->log3TimeDoseF = $this->KILLRATE99_9*1000/$this->intensityF/60;

            $this->log1TimeDoseH = $this->KILLRATE90*1000/$this->intensityH/60;
            $this->log2TimeDoseH = $this->KILLRATE99*1000/$this->intensityH/60;
            $this->log3TimeDoseH = $this->KILLRATE99_9*1000/$this->intensityH/60;

            $this->workPlane8hr = $this->intensityWP*28800/1000;
            $this->floor8hr = $this->intensityF*28800/1000;

            //find triangulation distance
            $this->triDistWP = sqrt(pow($this->wpDist,2)*2);
            $this->triDistF = sqrt(pow($this->heightCM,2)*2);
            
            //intensity overlap
            $this->intesityWPOverlap = 1/pow(($this->triDistWP/20),2)*$this->germicidalIntensity20cm;
            $this->intesityFOverlap = 1/pow(($this->triDistF/20),2)*$this->germicidalIntensity20cm;

            $this->tToDoseWP =$this->KILLRATE99*1000/$this->intesityWPOverlap/60;
            $this->tToDoseF =$this->KILLRATE99*1000/$this->intesityFOverlap/60;

            //Luminaire Spacing 
            //needs fixing
            $this->maxLumiSpacingCM = $this->wpDist*2+$this->WORKPLANEHEIGHT;
            $this->maxAreaCoverageCM = ($this->wpDist+$this->WORKPLANEHEIGHT/2)*2;
            $this->maxAreaCoverageCM = pow($this->maxAreaCoverageCM,2);
            $this->maxAreaCoverageF = $this->maxAreaCoverageCM/pow(30.48,2);
            $this->maxAreaSide = sqrt($this->maxAreaCoverageF);

            $this->lumiPerArea = $this->areaF/$this->maxAreaCoverageF;
            $this->lumiPerW = $this->widthF/$this->maxAreaSide;
            $this->lumiPerL = $this->lengthF/$this->maxAreaSide;
            $this->optimalLumiPerA= round($this->lumiPerArea);
            $this->optimalLumiPerL= round($this->lumiPerL);
            $this->optimalLumiPerW= round($this->lumiPerW);

            //Pricing
            $this->pricePerRoom = $this->optimalLumiPerA*$this->PRICEPERUNIT;
            $this->pricePerDesk = $this->pricePerRoom/$this->desks;

        }


    }

    if(isset($_POST["submit"])){
        $_SESSION["room"] = new Room(floatval($_POST["width"]),floatval($_POST["height"]),floatval($_POST["length"]),floatval($_POST["desks"]));
        
    }

?>

<!DOCTYPE html>

<html>

    <?php include("Templates/header.php") ?>
    <section class="container form-section">
        <form action="room.php" method="POST">
            <label>Height</label>
            <input type="text" name="height">
            <label>Width</label>
            <input type="text" name="width">
            <label>Lenght</label>
            <input type="text" name="length">
            <label>Desks</label>
            <input type="text" name="desks">
            <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
        </form>
    </section class="container">
    <?php 
    
        if(isset($_SESSION["room"])){
            include("display.php");
        }

    ?>

    <?php include("Templates/footer.php") ?>
</html>