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
        
        public $MAXDOSAGE = 23;
        public $KILLRATE90 = 0.39;
        public $KILLRATE99 = 0.78;
        public $KILLRATE99_9 = 1.20;
        public $WORKPLANEHEIGHT = 0.91;

        function __construct($width,$height,$length,$desks)
        {

            //get room dimentions
            $this->width = $width;
            $this->height = $height;
            $this->length = $length;
            $this->desks = $desks;
            
            //find germicidal intensity at 20 cm
            $this->germicidalIntensity20cm = 1/(20/4)^2*$this->totalGermicidalIntensity4cm;

            //convert meters to centimeters
            $this->widthCM = $this->widthM*10;
            $this->lengthCM = $this->lengthM*10;
            $this->heightCM = $this->heightM*10;
            
            //find workplane distance
            $this->wpDist = $this->heightCM-$this->WORKPLANEHEIGHT;

            //find intensity
            $this->intensityWP = 1/($this->wpDist/20)^2*$this->germicidalIntensity20cm;
            $this->intensityF = 1/($this->heightCM)^2*$this->germicidalIntensity20cm;
            $this->intensityH = 1/(($this->heightCM-183)/20)^2*$this->germicidalIntensity20cm;

            //find time to dose
            $this->log1TimeDoseWP = $this->KILLRATE90*100/$this->intensityWP/60;
            $this->log2TimeDoseWP = $this->KILLRATE99*100/$this->intensityWP/60;
            $this->log3TimeDoseWP = $this->KILLRATE99_9*100/$this->intensityWP/60;

            $this->log1TimeDoseF = $this->KILLRATE90*100/$this->intensityF/60;
            $this->log2TimeDoseF = $this->KILLRATE99*100/$this->intensityF/60;
            $this->log3TimeDoseF = $this->KILLRATE99_9*100/$this->intensityF/60;

            $this->log1TimeDoseH = $this->KILLRATE90*100/$this->intensityH/60;
            $this->log2TimeDoseH = $this->KILLRATE99*100/$this->intensityH/60;
            $this->log3TimeDoseH = $this->KILLRATE99_9*100/$this->intensityH/60;

            $this->workPlane8hr = $this->intensityWP*28800/1000;
            $this->floor8hr = $this->intensityF*28800/1000;

            //find triangulation distance
            $this->triDistWP = sqrt(($this->wpDist^2)*2);
            $this->triDistF = sqrt(($this->heightCM^2)*2);
            

        }

    }

    if(isset($_POST["submit"])){
        $_SESSION["room"] = new Room($_POST["width"],$_POST["height"],$_POST["length"],$_POST["desks"]);

    }

?>

<!DOCTYPE html>

<html>

    <?php include("Templates/header.php") ?>
    <section class="container form-section">
        <form action="room.php" method="POST">
            <label>Height</label>
            <input type="number" name="height">
            <label>Width</label>
            <input type="number" name="width">
            <label>Lenght</label>
            <input type="number" name="length">
            <label>Desks</label>
            <input type="number" name="desks">
            <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
        </form>
    </section>

    <?php 
    
        if(isset($_SESSION["room"])){
            echo "session";
        }

    ?>

    <?php include("Templates/footer.php") ?>
</html>