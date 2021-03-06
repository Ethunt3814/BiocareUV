<html>
    <div class="container">
        <h3>Room details</h3>
        <table>
            <tr>
                <th>width in meters</th>
                <th><?php echo $_SESSION["room"]->widthM ?> m</th>
            </tr>
            <tr>
                <th>length in meters</th>
                <th><?php echo $_SESSION["room"]->lengthM ?> m</th>
            </tr>
            <tr>
                <th>Height in meters</th>
                <th><?php echo $_SESSION["room"]->heightM ?> m</th>
            </tr>
            <tr>
                <th>width in centimeters</th>
                <th><?php echo $_SESSION["room"]->widthCM ?> cm</th>
            </tr>
            <tr>
                <th>length in centimeters</th>
                <th><?php echo $_SESSION["room"]->lengthCM ?> cm</th>
            </tr>
            <tr>
                <th>Height in centimeters</th>
                <th><?php echo $_SESSION["room"]->heightCM ?> cm</th>
            </tr>
            <tr>
                <th>work plane distance</th>
                <th><?php echo $_SESSION["room"]->wpDist?> cm</th>
            </tr>
            <tr>
                <th>Area</th>
                <th><?php echo $_SESSION["room"]->area?> cm^2</th>
            </tr>
            <tr>
                <th>Number of desks</th>
                <th><?php echo $_SESSION["room"]->desks ?></th>
            </tr>
            
        </table>
    </div>

    <div class="container">
        <h3>Constants</h3>
        <table>
            <tr>
                <th>Max Daily Dosage</th>
                <th>23</th>
            </tr>
            <tr>
                <th>Dosage to achieve 90%(Log1) CORONAVIRUS kill rate</th>
                <th><?php echo $_SESSION["room"]->KILLRATE90 ?> mj/cm^2</th>
            </tr>
            <tr>
                <th>Dosage to achieve 99%(Log2) CORONAVIRUS kill rate</th>
                <th><?php echo $_SESSION["room"]->KILLRATE99 ?> mj/cm^2</th>
            </tr>
            <tr>
                <th>Dosage to achieve 99.9%(Log3) CORONAVIRUS kill rate</th>
                <th><?php echo $_SESSION["room"]->KILLRATE99_9?> mj/cm^2</th>
            </tr>
            <tr>
                <th>Workplane Height</th>
                <th><?php echo $_SESSION["room"]->WORKPLANEHEIGHT ?> cm</th>
            </tr>
        </table>
    </div>

    <div class="container">
        <h3>Measured Values</h3>
        <table>
            <tr>
                <th>Peak Intensity (222nm only) @ 20cm:</th>
                <th><?php echo $_SESSION["room"]->peakIntensity20cm ?> uW/cm^2</th>
            </tr>
            <tr>
                <th>Total Germicidal Intensity @ 20cm:</th>
                <th><?php echo $_SESSION["room"]->germicidalIntensity20cm ?> uW/cm^2</th>
            </tr>
            <tr>
                <th>Total Germicidal Intensity @ 4cm:</th>
                <th><?php echo $_SESSION["room"]->totalGermicidalIntensity4cm ?> uW/cm^2</th>
            </tr>
        </table>
    </div>

    <div class="container">
        <h3>Luminaires</h3>
        <table>
        </tr>
            <tr>
                <th>Room square feet</th>
                <th><?php echo $_SESSION["room"]->areaF?> sq. feet</th>
            </tr>
            <tr>
                <th>Optimal max luminaire spacing</th>
                <th><?php echo $_SESSION["room"]->maxLumiSpacingCM?> cm</th>
            </tr>
            <tr>
                <th>Optimal max area coverage</th>
                <th><?php echo $_SESSION["room"]->maxAreaCoverageCM?> sq. cm</th>
            </tr>
            <tr>
                <th>Max area side</th>
                <th><?php echo $_SESSION["room"]->maxAreaSide?> sq. feet</th>
            </tr>
            <tr>
                <th>Luminaires per area</th>
                <th><?php echo $_SESSION["room"]->lumiPerArea?></th>
            </tr>
            <tr>
                <th>Luminaires per W</th>
                <th><?php echo $_SESSION["room"]->lumiPerW?></th>
            </tr>
            <tr>
                <th>luminaires per L</th>
                <th><?php echo $_SESSION["room"]->lumiPerL?></th>
            </tr>
            <tr>
                <th>optimal per A</th>
                <th><?php echo $_SESSION["room"]->optimalLumiPerA?></th>
            </tr>
            <tr>
                <th>optimal per L</th>
                <th><?php echo $_SESSION["room"]->optimalLumiPerL?></th>
            </tr>
            <tr>
                <th>optimal per W</th>
                <th><?php echo $_SESSION["room"]->optimalLumiPerW?></th>
            </tr>
            <tr>
                <th>Price per room</th>
                <th><?php echo $_SESSION["room"]->pricePerRoom?></th>
            </tr>
            <tr>
                <th>Price per desk</th>
                <th><?php echo $_SESSION["room"]->pricePerDesk?></th>
            </tr>
         
        </table>
    </div>

    <div class="container">
        <h3>Intensity and doses</h3>
        <table>
            <tr>
                <th>Intensity at workplane</th>
                <th><?php echo $_SESSION["room"]->intensityWP ?> uW/cm^2</th>
            </tr>
            <tr>
                <th>Intensity at floor</th>
                <th><?php echo $_SESSION["room"]->intensityF?> uW/cm^2</th>
            </tr>
            <tr>
                <th>Intensity at human height</th>
                <th><?php echo $_SESSION["room"]->intensityH?> uW/cm^2</th>
            </tr>
            <tr>
                <th>Time to dose at work plane log1</th>
                <th><?php echo $_SESSION["room"]->log1TimeDoseWP?> min</th>
            </tr>
            <tr>
                <th>Time to dose at work plane log2</th>
                <th><?php echo $_SESSION["room"]->log2TimeDoseWP?> min</th>
            </tr>
            <tr>
                <th>Time to dose at work plane log3</th>
                <th><?php echo $_SESSION["room"]->log3TimeDoseWP?> min</th>
            </tr>
            <tr>
                <th>Time to dose at floor log1</th>
                <th><?php echo $_SESSION["room"]->log1TimeDoseF?> min</th>
            </tr>
            <tr>
                <th>Time to dose at floor log2</th>
                <th><?php echo $_SESSION["room"]->log2TimeDoseF?> min</th>
            </tr>
            <tr>
                <th>Time to dose at floor log3</th>
                <th><?php echo $_SESSION["room"]->log3TimeDoseF?> min</th>
            </tr>
            <tr>
                <th>Time to dose at human height log1</th>
                <th><?php echo $_SESSION["room"]->log1TimeDoseH?> min</th>
            </tr>
            <tr>
                <th>Time to dose at human height log2</th>
                <th><?php echo $_SESSION["room"]->log2TimeDoseH?> min</th>
            </tr>
            <tr>
                <th>Time to dose at human height log3</th>
                <th><?php echo $_SESSION["room"]->log3TimeDoseH?> min</th>
            </tr>
            <tr>
                <th>8hr dose at floor level</th>
                <th><?php echo $_SESSION["room"]->floor8hr?> mj/cm^2</th>
            </tr>
            <tr>
                <th>8hr dose at work plane</th>
                <th><?php echo $_SESSION["room"]->workPlane8hr?> mj/cm^2</th>
            </tr>
            <tr>
                <th>Triangulation distance at work plane</th>
                <th><?php echo $_SESSION["room"]->triDistWP?> cm</th>
            </tr>
            <tr>
                <th>Triangulation distance at floor</th>
                <th><?php echo $_SESSION["room"]->triDistF?> cm</th>
            </tr>
            <tr>
                <th>Intensity at workplane overlap</th>
                <th><?php echo $_SESSION["room"]->intesityWPOverlap?></th>
            </tr>
            <tr>
                <th>Intensity at floor overlap</th>
                <th><?php echo $_SESSION["room"]->intesityFOverlap?></th>
            </tr>
            <tr>
                <th>Time to dose at workplane overlap</th>
                <th><?php echo $_SESSION["room"]->tToDoseWP?></th>
            </tr>
            <tr>
                <th>Time to dose at floor overlap</th>
                <th><?php echo $_SESSION["room"]->tToDoseF?></th>
            </tr>
        </table>
    </div>
</html>