<?php

    class Room{

        //basic room dimentions in metric (meters)
        public $widthM;
        public $lengthM;
        public $heightM;
        public $desks;
        public $widthCM;
        public $lengthCM;
        public $heightCM;
        public $wpDist;
        public $area;

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

        public $maxLumiSpacing;
        public $maxAreaCoverage;
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
            $this->widthM = $width;
            $this->heightM = $height;
            $this->lengthM = $length;
            $this->desks = $desks;
            
            
            //find germicidal intensity at 20 cm
            $this->germicidalIntensity20cm = pow(1/(20/4),2)*$this->totalGermicidalIntensity4cm;

            //convert meters to centimeters
            $this->widthCM = $this->widthM*100;
            $this->lengthCM = $this->lengthM*100;
            $this->heightCM = $this->heightM*100;

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
            $this->maxLumiSpacing = $this->wpDist*2+$this->WORKPLANEHEIGHT;
            $this->maxAreaCoverage =pow((($this->heightCM/2)*2),2);
            $this->maxAreaSide = sqrt($this->maxAreaCoverage);

            $this->lumiPerArea = $this->area/$this->maxAreaCoverage;
            $this->lumiPerW = $this->widthCM/$this->maxAreaSide;
            $this->lumiPerL = $this->lengthCM/$this->maxAreaSide;
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