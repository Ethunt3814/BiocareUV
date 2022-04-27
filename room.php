<?php
    if(!isset($_SESSION["rooms"])){
        $_SESSION["rooms"] = array();
    }
    include("config/db_connect.php");
    if(isset($_GET["id"])){
        $id = mysqli_real_escape_string($conn, $_GET["id"]);

        $sql = "SELECT * FROM rooms WHERE id = '$id'";

        $result = mysqli_query($conn, $sql);

        $room_get = mysqli_fetch_assoc($result);

        $_POST["facility"] = $room_get["Facility"];
        $_POST["name"] = $room_get["Name"];
        $_POST["height"] = $room_get["Height"];
        $_POST["width"] = $room_get["Width"];
        $_POST["length"] = $room_get["Length"];
        $_POST["desks"] = $room_get["Desks"];
        $_POST["submit"] = "submit";
    }

    

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
            
            //rounding
            $this->widthCM = round($this->widthCM,2);
            $this->heightCM = round($this->heightCM,2);
            $this->lengthCM = round($this->lengthCM,2);
            $this->germicidalIntensity20cm = round($this->germicidalIntensity20cm,2);
            $this->widthM = round($this->widthM,2);
            $this->lengthM = round($this->lengthM,2);
            $this->heightM = round($this->heightM,2);
            $this->areaF= round($this->areaF,2);
            $this->area = round($this->area,2);
            $this->wpDist = round($this->wpDist,2);
            $this->intensityWP = round($this->intensityWP,2);
            $this->intensityF = round($this->intensityF,2);
            $this->intensityH = round($this->intensityH,2);
            $this->log1TimeDoseWP = round($this->log1TimeDoseWP,2);
            $this->log2TimeDoseWP = round($this->log2TimeDoseWP,2);
            $this->log3TimeDoseWP = round($this->log3TimeDoseWP,2);
            $this->log1TimeDoseF = round($this->log1TimeDoseF,2);
            $this->log2TimeDoseF = round($this->log2TimeDoseF,2);
            $this->log3TimeDoseF = round($this->log3TimeDoseF,2);
            $this->log1TimeDoseH = round($this->log1TimeDoseH,2);
            $this->log2TimeDoseH = round($this->log2TimeDoseH,2);
            $this->log3TimeDoseH = round($this->log3TimeDoseH,2);
            $this->workPlane8hr = round($this->workPlane8hr,2);
            $this->floor8hr = round($this->floor8hr,2);
            $this->triDistWP = round($this->triDistWP,2);
            $this->triDistF = round($this->triDistF,2); 
            $this->intesityWPOverlap = round($this->intesityWPOverlap,2);
            $this->intesityFOverlap = round($this->intesityFOverlap,2);
            $this->tToDoseWP = round($this->tToDoseWP,2);
            $this->tToDoseF = round($this->tToDoseF,2);
            $this->maxLumiSpacingCM = round($this->maxLumiSpacingCM,2);
            $this->maxAreaCoverageCM = round($this->maxAreaCoverageCM,2);
            $this->maxAreaCoverageCM = round($this->maxAreaCoverageCM,2);
            $this->maxAreaCoverageF = round($this->maxAreaCoverageF,2);
            $this->maxAreaSide = round($this->maxAreaSide,2);
            $this->lumiPerArea = round($this->lumiPerArea,2);
            $this->lumiPerW = round($this->lumiPerW,2);
            $this->lumiPerL = round($this->lumiPerL,2);
            $this->optimalLumiPerA= round($this->optimalLumiPerA,2);
            $this->optimalLumiPerL= round($this->optimalLumiPerL,2);
            $this->optimalLumiPerW= round($this->optimalLumiPerW,2);
            $this->pricePerRoom = round($this->pricePerRoom,2);
            $this->pricePerDesk = round($this->pricePerDesk,2);
        }



    }

    if(isset($_POST["submit"])){
        $_SESSION["room"] = new Room(floatval($_POST["width"]),floatval($_POST["height"]),floatval($_POST["length"]),floatval($_POST["desks"]));
        array_push($_SESSION["rooms"],$_SESSION["room"]);
    }
    if(isset($_POST["Save"])){
        $_SESSION["room"] = new Room(floatval($_POST["width"]),floatval($_POST["height"]),floatval($_POST["length"]),floatval($_POST["desks"]));

        $facility = $_POST["facility"];
        $name = $_POST["name"];
        $height = $_POST["height"];
        $width = $_POST["width"];
        $length =$_POST["length"];
        $desks = $_POST["desks"];
        $price = $_SESSION["room"]->pricePerRoom;

        $sql = "SELECT * FROM rooms WHERE Facility='$facility' AND Name='$name'";

        $result= mysqli_query($conn, $sql);

        $current = mysqli_fetch_assoc($result);

        

        if(empty($current)){
            
            $sql = "INSERT INTO rooms (Facility,Name,Height,Width,Length,Desks,Price) VALUES ('$facility','$name','$height','$width','$length','$desks','$price')";
            if(mysqli_query($conn, $sql)){
                header("location: menu.php?facility=" . $facility);
            }else{
                mysqli_error($conn);
            }
        }
        else{
            $sql = "UPDATE rooms SET Height='$height',Width='$width',Length='$length',Desks='$desks',Price='$price' WHERE Facility='$facility' AND Name='$name'";
            if(mysqli_query($conn, $sql)){
                header("location: menu.php?facility=" . $facility);
            }else{
                mysqli_error($conn);
            }

        }
    }

    include("Templates/header.php");
?>

<!DOCTYPE html>

<html>
    <br>
    <div class="container" style="padding-left: 5%; padding-right: 5%;">
        <div class="container" style="padding-left: 10%; padding-right: 10%; padding-bottom: 5%; padding-top: 5%;">
            <form action="room.php" method="POST">
                <label for="facility">Facility</label>
                <input type="text" name="facility" value="<?php if(isset($_POST["facility"])){ echo $_POST["facility"];}?>">
                <label for="name">Room Name</label>
                <input type="text" name="name" value="<?php if(isset($_POST["name"])){ echo $_POST["name"];} ?>">
                <label for="height">Height</label>
                <input type="text" name="height" value="<?php if(isset($_POST["height"])){ echo $_POST["height"];} ?>">
                <label for="width">Width</label>
                <input type="text" name="width" value="<?php if(isset($_POST["width"])){ echo $_POST["width"];} ?>">
                <label for="length">Lenght</label>
                <input type="text" name="length" value="<?php if(isset($_POST["length"])){ echo $_POST["length"];} ?>">
                <label for="desks">Desks</label>
                <input type="text" name="desks" value="<?php if(isset($_POST["desks"])){ echo $_POST["desks"];} ?>">
                <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
                <input type="submit" name="Save" value="Save" class="btn brand z-depth-0" <?php if(!isset($_POST["submit"])){echo "disabled";} ?>>
            </form>
        </div>
    <?php 
        if(isset($_SESSION["room"])){
            include("display.php");
        }
    ?>
    </div>
    </div>
    <?php include("Templates/footer.php") ?>
</html>